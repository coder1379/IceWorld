<?php
namespace api\controllers;

use common\ComBase;
use common\controllers\ApiCommonContoller;
use common\queues\TestJobs;
use common\services\systemconfig\SystemConfigModel;
use common\services\systemconfig\SystemConfigQuery;
use Firebase\JWT\JWT;
use Yii;
use yii\helpers\Json;
use function GuzzleHttp\Promise\all;


/**
 * Test测试接口
 */
class TestController extends ApiCommonContoller
{
	public $enableCsrfValidation = false;

    /**
     * queue测试
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionQueue(){
        exit();
        for($i=0;$i<100;$i++){
            $arr = [
                'addtime' => time(),
            ];
            Yii::$app->db->createCommand()->insert('m_testjob', $arr)->execute();
            $tid = Yii::$app->db->getLastInsertID();
            $id = Yii::$app->queue->push(new TestJobs(['params_1'=>'insert time:'.time(),'params_2'=>$tid]));
            print_r($id.'_'.$tid);
        }

        return Json::encode(ComBase::getReturnArray());
    }

    /**
     * 测试数据库写入
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionTestmysqlwrite(){
        $params = null;
        ComBase::getIntVal('id', $params);
        ComBase::getParamsErrorReturnArray();
        ComBase::getNoLoginReturnArray();
        exit();
        Yii::$app->db->createCommand("insert into {{%test_use_table}} (name,status,add_time,content) values (1,1,1,'1')")->execute();
        return Json::encode(ComBase::getReturnArray(['id' => Yii::$app->db->getLastInsertID()]));
    }

    /**
     * jwt测试
     */
    public function actionJwtentest(){
        exit();
        $qu = SystemConfigModel::find()->select()->where()->andWhere(['id'=>5])->andWhere(['>','name',5]);
        $qu->andWhere(['add_time' => 10]);
        $mo = $qu->one();

        print_r($mo);
        exit();
        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );

        $jwt = JWT::encode($payload, $key);
        $deJwt =  JWT::decode($jwt, $key, array('HS256'));
        return Json::encode(ComBase::getReturnArray(['jwt'=>$jwt,'djwt'=>$deJwt]));
    }

}
