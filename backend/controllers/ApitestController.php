<?php

namespace backend\controllers;

use common\lib\ApiReflection;
use common\lib\Curl;
use Yii;

class ApitestController extends AuthController
{
    /**
     * @inheritdoc
     */
    public $layout = 'main-iframe';

    public $noLoginAccess = ['index'];

    /**
     * Lists all WebsiteNewsModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $contrl = $this->get('c', '');
        $action = $this->get('a','');
        $returnParams = [];
        $token = 0;
        if(!empty($contrl) && !empty($action)){
            $apiReflection = new ApiReflection();
            $ref = new \ReflectionClass('\\api\\controllers\\' . $contrl.'Controller');
            $parendRef = $ref->getParentClass();
                $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
                $parentMethods = $parendRef->getMethods(\ReflectionMethod::IS_PUBLIC);
                $ownMethods = array_diff($methods, $parentMethods);

                if($parendRef->getShortName() == 'ApiCommonAuthContoller'){
                    $token = 1;
                }
                if (!empty($ownMethods)) {
                    foreach ($ownMethods as $own) {
                        $parm = [];
                        $actionName = strtolower($own->getName());
                        $apiName = '';
                        if (strlen($actionName) > 6) {
                            $apiName = substr($actionName, 0, 6);
                        }

                        if ($apiName != 'action') {
                            continue;
                        }

                        $actionName = substr($actionName, 6, strlen($actionName) - 6);
                        if($actionName == $action){
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

                            if(!empty($parm['tags']['param'])){
                                $parm['tags']['param'] = $apiReflection->formatParams($parm['tags']['param']);
                            }
                            $apiRootUrl = Yii::$app->params['api_root_url'];
                            $returnParams['url'] = $apiRootUrl.$contrl.'/'.$action;
                            $returnParams['params'] = $parm['tags']['param'];
                            $returnParams['actondesc'] = $parm['tags']['description'];
                        }

                    }
                }
        }

        return $this->render('index', ['apiData' => $returnParams,'token'=>$token]);
    }

    private function postCurl($params,$url){
        $returnArr = [];
        $curlObj = new Curl();
        $curlObj->post($params)->url($url);
        $error = '';
        $content = '';
        if ($curlObj->error()) {
            $error = $curlObj->message();
        } else {
            $content = $curlObj->data();
            Yii::warning('接口全量测试:'.$url.'  params:'.json_encode($params).'  res:'.$content);
            if (json_decode($content,true) != false) {
                $newObj = json_decode($content, true);
                if ($newObj['code'] != 200) {
                    $error = $newObj['msg'];
                }
            }else{
                $error = 'json格式错误:'.$content;
            }
        }
        if (empty($error)) {
            $returnArr = ['status' => true,'res' => $newObj];
        }else{
            $returnArr = ['status' => false,'res' => $error];
        }

        return $returnArr;
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
