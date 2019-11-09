<?php

namespace backend\controllers;

use Yii;
use yii\helpers\FileHelper;


class ApidocController extends AuthController
{
    /**
     * @inheritdoc
     */
    public $layout = 'main-iframe';

    /**
     * Lists all WebsiteNewsModel models.
     * @return mixed
     */
    public function actionIndex()
    {

        $clickName = $this->get('cname','');

        $docList = [];
        $except = ['JtwController.php', 'IndexController.php','UploadController.php'];
        $path = FileHelper::normalizePath(Yii::getAlias('@api').'/controllers');

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

        foreach ($controllers as $c){
            $carr = explode("/",$c);
            $carr = explode("\\",end($carr));
            $name = str_replace('.php','',end($carr));

            $ref = new \ReflectionClass('\\api\\controllers\\'.$name);
            $parendRef = $ref->getParentClass();
            $controllerDoc = $this->parseDocCommentTags($ref)['description']??$name;
            $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
            $parentMethods = $parendRef->getMethods(\ReflectionMethod::IS_PUBLIC);
            $ownMethods = array_diff($methods,$parentMethods);
            $methodList = [];
            if(!empty($ownMethods)){
                foreach ($ownMethods as $own){
                    $parm = [];
                    $actionName = strtolower($own->getName());
                    $apiName = '';
                    if(strlen($actionName)>6){
                        $apiName = substr($actionName,0,6);
                    }

                    if($apiName!='action'){
                        continue;
                    }

                    $parm['name'] = substr($actionName,6,strlen($actionName)-6);
                    $parm['tags'] = $this->parseDocCommentTags($own);
                    if(empty($parm['tags']['description'])===false && strpos($parm['tags']['description'],'targetDoc->')!==false){
                        $newDocArr = explode('->',$parm['tags']['description']);
                        $apiDocClass = $newDocArr[1]??'';
                        $apiDocMethod = $newDocArr[2]??'';

                        if(empty($apiDocClass)==false && empty($apiDocMethod)==false){
                            $targetMethod =new \ReflectionMethod($apiDocClass, $apiDocMethod);
                            $parm['tags'] = $this->parseDocCommentTags($targetMethod);
                        }


                    }

                    $parm['params'] = $own->getParameters();

                    $methodList[$parm['name']] = $parm;
                }
            }
            $name = str_replace('controller','',strtolower($name));
            $docList[$name] = ['name'=>$name,'description'=>$controllerDoc,'methods'=>$methodList];
        }

        return $this->render('index', ['docList'=>$docList,'cname'=>$clickName]);
    }

    protected function parseDocCommentTags($reflection)
    {
        $comment = $reflection->getDocComment();
        $comment = "@description \n" . strtr(trim(preg_replace('/^\s*\**( |\t)?/m', '', trim($comment, '/'))), "\r", '');
        $parts = preg_split('/^\s*@/m', $comment, -1, PREG_SPLIT_NO_EMPTY);
        $tags = [];
        foreach ($parts as $part) {
            if (preg_match('/^(\w+)(.*)/ms', trim($part), $matches)) {
                $name = $matches[1];
                if (!isset($tags[$name])) {
                    $tags[$name] = trim($matches[2]);
                } elseif (is_array($tags[$name])) {
                    $tags[$name][] = trim($matches[2]);
                } else {
                    $tags[$name] = [$tags[$name], trim($matches[2])];
                }
            }
        }

        return $tags;
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
