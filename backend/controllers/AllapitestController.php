<?php

namespace backend\controllers;

use common\ComBase;
use common\lib\ApiReflection;
use common\lib\Curl;
use Yii;
use yii\helpers\FileHelper;
use yii\db\Schema;

class AllapitestController extends AuthController
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
        set_time_limit(300);
        ini_set('memory_limit', '1024M');
        $allTestList = [];

        $token = '149446cb838662d213ad26f128b993984f382a59';
        $apiRootUrl = Yii::$app->params['api_root_url'];
        //site
        $createJson = $this->postCurl(['name'=>'abc','img_url'=>'abccc','content'=>'content 测试内容','user_id'=>40,'token'=>$token],$apiRootUrl.'site/create');
        $allTestList['site']['create'] = $createJson;

        $updateJson = $this->postCurl(['id'=>$createJson['res']['data']['id']??0,'name'=>'abc','img_url'=>'abccc123','content'=>'content 测试内容xx','user_id'=>40,'token'=>$token],$apiRootUrl.'site/update');
        $allTestList['site']['update'] = $updateJson;

        $detailJson = $this->postCurl(['id'=>$createJson['res']['data']['id']??0,'token'=>$token],$apiRootUrl.'site/detail');
        $allTestList['site']['detail'] = $detailJson;

        $listJson = $this->postCurl(['token'=>$token],$apiRootUrl.'site/list');
        $allTestList['site']['list'] = $listJson;

        $deleteJson = $this->postCurl(['id'=>$createJson['res']['data']['id']??0,'token'=>$token],$apiRootUrl.'site/delete');
        $allTestList['site']['delete'] = $deleteJson;

        return $this->render('index', ['testList' => $allTestList]);
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
