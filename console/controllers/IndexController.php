<?php
namespace console\controllers;

use common\lib\DingTalkRobot;
use common\services\systemconfig\SystemConfigModel;
use GuzzleHttp\Client;
use Yii;
use yii\console\Controller;
use yii\helpers\Json;

class IndexController extends Controller
{
    /**
     * 系统巡视,每分钟一次,发生异常钉钉通知相关人员
     */
    //* * * * * /usr/local/php74/bin/php /data/wwwroot/projectroot/yii index/systemtour
    public function actionSystemtour()
    {
        $errorList = [];
        $httpClient = new Client(['verify' => false, 'timeout' => 10]);
        $envStr = null;
        $projectName = Yii::$app->params['project_name'];
        if (YII_ENV === 'prod') {
            $envStr = '*X*('.$projectName.')生产环境]*X*';
        }else if(YII_ENV === 'befprod'){
            $envStr = '('.$projectName.')预发布环境';
        } else {
            $envStr = '('.$projectName.')测试环境';
        }

        try {
            //检测api接口是否能够正常访问 可自行扩展更多接口和参数
            $text = $httpClient->get(Yii::$app->params['api_root_url'])->getBody()->getContents();
            $textJson = Json::decode($text);
            $code = $textJson['code'] ?? 400;
            if (intval($code) > 0) {
                //无法访问放入错误数组
                $errorList[] = 'api接口访问异常';
            }
        } catch (\Exception $exce) {
            $errorList[] = 'api接口检查catch:' . $exce->getMessage();
        }

        try {
            //错误日志检查
            $configModel = SystemConfigModel::findOne(['name' => 'error_log_select_id']);
            $logId = $configModel->c_val;
            $lastIdData = Yii::$app->db->createCommand('select id from {{%log}} where id>:id and level=1 order by id desc limit 1', [':id' => $logId])->queryOne();
            $lasdId = $lastIdData['id'] ?? 0;
            if ($lasdId > 0) {
                $errorLogCountData = Yii::$app->db->createCommand('select count(*) as cnt from {{%log}} where id>:id and id<=:lastId and level=1', [':id' => $logId, ':lastId' => $lasdId])->queryOne();
                $errorLogCount = $errorLogCountData['cnt'] ?? 0;
                if ($errorLogCount > 0) {
                    $errorList[] = '巡检周期内发生严重错误数量(' . $errorLogCount . ')个,注意查看';
                }
                $configModel->c_val = $lasdId;
                $configModel->update(false);
            }

        } catch (\Exception $exce) {
            $errorList[] = '错误日志检查catch:' . $exce->getMessage();
        }

        //错误队列不为空，发送钉钉推送
        //异常日志 为钉钉推送关键字，必须包含此文字或指定的钉钉关键字
        if (!empty($errorList)) {
            $ding = new DingTalkRobot();
            $ding->accessToken = Yii::$app->params['dingding_log_robot_token'];
            $message = $envStr . ' 异常日志 ' . implode("\n\n", $errorList)."\n";
            $isAtAll = false;
            //生产错误直接at所有人
            if (YII_ENV === 'prod') {
                $isAtAll = true;
            }
            $sendJsonStr = $ding->sendTextMsg($message, [], $isAtAll);
            $sendData = json_decode($sendJsonStr, true);
            if(!empty($sendData['errcode']) && $sendData['errcode']>0){
                Yii::error('钉钉异常日志发送错误:'.$sendJsonStr);
            }
        }

    }
}