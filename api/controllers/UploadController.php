<?php

namespace api\controllers;

use Yii;
use common\ComBase;
use common\controllers\AuthApiCommonContoller;
use common\lib\upload\UploadBase64;
use common\services\upload\UploadLogic;
use yii\helpers\Json;

/**
 * 上传
 * @package api\controllers
 */
class UploadController extends AuthApiCommonContoller
{

    /**
     * 二级制图片文件上传
     * @param array file_data 文件对象 1
     * @return json yes {"data":{"short_url":"[string] 短路径","url":"[string] 路径"}}
     * @throws \OSS\Core\OssException
     */
    public function actionImage()
    {
        if (!isset($_FILES['file_data'])) {
            return Json::encode(ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR));
        }
        $uploadLogic = new UploadLogic('','image');//上传图片类型
        $result = $uploadLogic->upload($_FILES['Filedata'], Yii::$app->params['uploadMode'],'app');
        return Json::encode($result);
    }

    /**
     * 上传图片base64
     * @param base64 file_data 文件 1
     * @return json yes {"data":{"short_url":"[string] 短路径","url":"[string] 路径"}}
     */
    public function actionImagebase64()
    {
        $imgStr = $this->post('file_data');
        if (empty($imgStr) || !preg_match('/^data:image\/(jpeg|png|jpg|bmp|gif);base64,/', $imgStr)) {
            return Json::encode(ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR));
        }

        $img = str_replace(['data:image/jpeg;base64,','data:image/jpg;base64,','data:image/png;base64,','data:image/gif;base64,','data:image/bmp;base64,'], '', $imgStr);

        $img = base64_decode($img);
        $uploadLogic = new UploadBase64();
        $result = $uploadLogic->uploadImageResource($img,'app');
        return Json::encode($result);
    }
}