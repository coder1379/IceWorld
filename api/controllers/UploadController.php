<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24 0024
 * Time: 15:04
 */

namespace api\controllers;

use common\BaseCommon;
use common\services\upload\UploadLogic;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * Class UploadController
 * @package api\controllers
 */
class UploadController extends Controller
{

    public function actionUploadImage()
    {
        $common = new BaseCommon();
        if (!isset($_FILES['Filedata'])) {
            return Json::encode($common->getJsonArray([], 410, '参数错误'));
        }
        $uploadLogic = new UploadLogic('uploadimg');
        $result = $uploadLogic->upload($_FILES['Filedata'], 'oss');
        return Json::encode($result);
    }


    public function actionMiniProgramUploadImage()
    {

        if (!isset($_FILES['FileData'])) {
            Json::echoJson([], 410, '参数错误');
        }
        $common = new BaseCommon();
        $uploadLogic = new UploadLogic('upload-image');
        $result = $uploadLogic->upload($_FILES['FileData'], 'oss');
        return Json::encode($result);
    }
}