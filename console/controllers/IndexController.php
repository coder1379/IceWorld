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
     * 系统巡视,每5分钟一次,发生异常钉钉通知相关人员
     */
    //*/5 * * * * /usr/local/php74/bin/php /data/wwwroot/projectroot/yii index/systemtour
    public function actionSystemtour()
    {
        $errorList = [];
        $httpClient = new Client(['verify' => false, 'timeout' => 10]);
        $envStr = '环境';
        if (YII_ENV === 'prod') {
            $envStr = '*生产环境*';
        } else {
            $envStr = '测试环境';
        }

        try {
            //如果是生产环境检查api是否能够正常访问
            $text = $httpClient->get(Yii::$app->params['api_root_url'])->getBody()->getContents();
            $textJson = Json::decode($text);
            $code = $textJson['code'] ?? 400;
            if (intval($code) > 0) {
                //无法访问放入错误数组
                $errorList[] = 'api接口访问异常';
            }
        } catch (\Exception $exce) {
            $errorList[] = 'api接口检查异常:' . $exce->getMessage();
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
                    $errorList[] = '巡检周期内发生严重错误数量(' . $errorLogCount . ')个,请注意检查';
                }
                $configModel->c_val = $lasdId;
                $configModel->update(false);
            }

        } catch (\Exception $exce) {
            $errorList[] = '日志检查发生异常:' . $exce->getMessage();
        }

        //错误队列不为空，发送钉钉推送
        if (!empty($errorList)) {
            $ding = new DingTalkRobot();
            $ding->accessToken = $ding->accessTokenLog;
            $message = $envStr . ' ' . $ding->keyLog . ' ' . implode("\n\n", $errorList)."\n";
            $ding->sendTextMsg($message, [], true);
        } else {
            Yii::warning('巡检完成未发现异常');
        }

    }
}