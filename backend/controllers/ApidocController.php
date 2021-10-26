<?php

namespace backend\controllers;

use common\ComBase;
use common\lib\ApiReflection;
use Yii;
use yii\helpers\FileHelper;
use yii\db\Schema;
use yii\helpers\Inflector;

class ApidocController extends AuthController
{
    /**
     * @inheritdoc
     */
    public $layout = 'main-iframe';

    /**
     * 根据代码自动生成接口文档控制器 屏蔽部分不需要的功能
     * @return mixed
     */
    public function actionIndex()
    {

        $clickName = $this->get('cname', '');

        $docList = [];
        $except = ['IndexController.php','TestController.php','SmsmobileController.php'];
        $path = FileHelper::normalizePath(Yii::getAlias('@api') . '/controllers');

        $options = [
            'filter' => function ($path) {
                if (is_file($path)) {
                    $file = basename($path);
                    if ($file[0] < 'A' || $file[0] > 'Z') {
                        return false;
                    }
                }

                return null;
            },
            'only' => ['*.php'],
            'except' => $except,
        ];

        $controllers = FileHelper::findFiles($path, $options);
        $apiReflection = new ApiReflection();
        asort($controllers);
        foreach ($controllers as $c) {
            $carr = explode("/", $c);
            $carr = explode("\\", end($carr));
            $name = str_replace('.php', '', end($carr));
            $methodList = [];
            $ref = new \ReflectionClass('\\api\\controllers\\' . $name);
            $parendRef = $ref->getParentClass();

            // 将大写转为横线
            $name = Inflector::camel2id($name);
            echo $name . PHP_EOL;

            $controllerDoc = $apiReflection->parseDocCommentTags($ref)['description'] ?? $name;
            $controllerDesString = explode("\n", $controllerDoc)[0];
            $controllerDoc = explode("\r\n", $controllerDesString)[0];
            $name = str_replace('-controller', '', strtolower($name));
            if(!empty($clickName) && $clickName == $name){
                $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
                $parentMethods = $parendRef->getMethods(\ReflectionMethod::IS_PUBLIC);
                $ownMethods = array_diff($methods, $parentMethods);

                if (!empty($ownMethods)) {
                    foreach ($ownMethods as $own) {
                        $parm = [];
                        $actionName = $own->getName();
                        $apiName = '';
                        if (strlen($actionName) > 6) {
                            $apiName = substr($actionName, 0, 6);
                        }

                        if ($apiName != 'action') {
                            continue;
                        }

                        $parm['name'] = Inflector::camel2id(substr($actionName, 6, strlen($actionName) - 6));
                        $parm['tags'] = $apiReflection->parseDocCommentTags($own);
                        if (empty($parm['tags']['description']) === false && strpos($parm['tags']['description'], 'targetDoc->') !== false) {
                            $newDocArr = explode('->', $parm['tags']['description']);
                            $apiDocClass = $newDocArr[1] ?? '';
                            $apiDocMethod = $newDocArr[2] ?? '';

                            if (empty($apiDocClass) == false && empty($apiDocMethod) == false) {
                                $targetMethod = new \ReflectionMethod($apiDocClass, $apiDocMethod);
                                $parm['tags'] = $apiReflection->parseDocCommentTags($targetMethod);
                            }

                        }

                        //$parm['params'] = $own->getParameters();//获取函数的参数列表，暂未使用
                        if(!empty($parm['tags']['param'])){
                            $parm['tags']['param'] = $apiReflection->formatParams($parm['tags']['param']);
                        }

                        if(!empty($parm['tags']['return'])){
                            $parm['tags']['return'] = $apiReflection->formatReturns($parm['tags']['return']);
                        }

                        $methodList[$parm['name']] = $parm;
                    }
                }
            }

            $docList[$name] = ['name' => $name, 'description' => $controllerDoc, 'methods' => $methodList];
        }

        return $this->render('index', ['docList' => $docList, 'cname' => $clickName,'apiRootUrl'=>Yii::$app->params['api_root_url']]);
    }



    /**
     * Displays a single WebsiteNewsModel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

}
