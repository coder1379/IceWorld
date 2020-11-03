<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24 0024
 * Time: 15:04
 */

namespace api\controllers;

use common\base\BaseCommon;
use common\ComBase;
use common\controllers\ApiCommonAuthContoller;
use common\lib\upload\UploadBase64;
use common\services\upload\UploadLogic;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * Class UploadController
 * @package api\controllers
 */
class UploadController extends ApiCommonAuthContoller
{

    public function actionUploadImage()
    {
        $common = new BaseCommon();
        if (!isset($_FILES['Filedata'])) {
            return Json::encode($common->getJsonArray([], 410, '参数错误'));
        }
        $uploadLogic = new UploadLogic('uploadimg');
        $result = $uploadLogic->upload($_FILES['Filedata'], Yii::$app->params['uploadMode']);
        return Json::encode($result);
    }

    /**
     * 上传图片base64
     * @param base64 $Filedata 文件 1
     * @return json yes {"data":{"short_url":"[string] 短路径","url":"[string] 路径"}}
     */
    public function actionUploadgoodsimagebase64()
    {
        $imgStr = $this->post('Filedata');
        if (empty($imgStr) || !preg_match('/^data:image\/(jpeg|png|jpg|bmp|gif);base64,/', $imgStr)) {
            return ComBase::getReturnJson([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR);
        }

        $img = str_replace(['data:image/jpeg;base64,','data:image/jpg;base64,','data:image/png;base64,','data:image/gif;base64,','data:image/bmp;base64,'], '', $imgStr);

        $img = base64_decode($img);
        $uploadLogic = new UploadBase64();
        $result = $uploadLogic->uploadImageResource($img,'goods');
        return Json::encode($result);
    }


    public function actionMiniProgramUploadImage()
    {
        if (!isset($_FILES['FileData'])) {
            Json::echoJson([], 410, '参数错误');
        }
        $uploadLogic = new UploadLogic('upload-image');
        $result = $uploadLogic->upload($_FILES['FileData'], Yii::$app->params['uploadMode']);
        return Json::encode($result);
    }
}