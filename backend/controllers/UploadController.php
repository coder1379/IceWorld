<?php

namespace backend\controllers;

use common\services\upload\UploadLogic;
use Yii;
use common\lib\upload\Upload;
use common\BaseCommon;
use yii\helpers\Json;

/**
 * CitychannelController implements the CRUD actions for CityChannel model.
 */
class UploadController extends AuthController
{
    /**
     * @inheritdoc
     */
    public $layout = 'main';

    public $allowLoginAccess = ['wangeditor-upload-image'];

    public function beforeAction($action)
    {
        ////需要进行权限验证
        /*$admgl=new Adminglobal();
        if($admgl->logincheck()===true){
            return true;
        }else{
            $glbobj = new Globalfunc();
            echo $glbobj->responseJson(['error' => 1, 'content' => '登录已超时，请重新登录！']);
            exit();
        }*/
        return true;
    }

    public function actionIndex()
    {
        date_default_timezone_set("Asia/chongqing");
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");
        $upload = new Upload();
        $configStr = $upload->getConfig();
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $configStr), true);
        $action = Yii::$app->request->get('action');

        switch ($action) {
            case 'config':
                $result = json_encode($CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
                $result = $upload->uploadImage($CONFIG);
                break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = $upload->uploadImage($CONFIG);
                break;

            /* 列出图片 */
            case 'listimage':
                //$result = include("action_list.php");
                break;
            /* 列出文件 */
            case 'listfile':
                //$result = include("action_list.php");
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                //$result = include("action_crawler.php");
                break;

            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
            exit();
        }

    }

    public function actionAjaxuploadvideo()
    {
        $common = new BaseCommon();
        if (!isset($_FILES['Filedata'])) {
            return Json::encode($common->getJsonArray([], 411, '参数错误'));
        }

        $upload = new UploadLogic(Yii::$app->params['oss']['bucket'],'audio');
        $result = $upload->upload($_FILES['Filedata'],Yii::$app->params['uploadMode']);
        return Json::encode($result);
    }

    public function actionAjaxupload()
    {
        $common = new BaseCommon();
        if (!isset($_FILES['Filedata'])) {
            return Json::encode($common->getJsonArray([], 410, '参数错误'));
        }

        $upload = new UploadLogic(Yii::$app->params['oss']['bucket'],'image');
        $result = $upload->upload($_FILES['Filedata'],Yii::$app->params['uploadMode']);
        return Json::encode($result);
    }

    public function actionWangeditorUploadImage()
    {
        $common = new BaseCommon();
        if (!isset($_FILES['Filedata'])) {
            return Json::encode($common->getJsonArray([], 410, '参数错误'));
        }
        $uploadLogic = new UploadLogic(Yii::$app->params['oss']['bucket']);
        $result = $uploadLogic->upload($_FILES['Filedata'], Yii::$app->params['uploadMode']);
        //返回特定的格式
        $newArr = [
            'errno' => $result['code']
        ];
        if ($result['code'] == 200) {
            $newArr = [
                'errno' => 0,
                'data' => [
                    $result['data']['url']
                ]
            ];
        }
        return Json::encode($newArr);
    }
}
