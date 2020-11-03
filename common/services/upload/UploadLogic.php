<?php
namespace common\services\upload;

use common\ComBase;
use common\lib\UploadOSS;
use Yii;

/**
 * Class UploadLogic
 * @package common\services\upload
 */
class UploadLogic
{
    //阿里云oss配置
    protected $ossConfig;

    //缓存文件名称
    protected $tempFile;

    //缓存文件名称详细信息
    protected $tempFileParts;

    //缓存文件后缀格式
    protected $tempFileExt;

    //上传的文件类型
    protected $fileType;

    //本地存储的路径
    protected $filePath;

    //后缀白名单
    protected $fileExt = [
        'image' => ['jpg', 'jpeg', 'png', 'bmp', 'gif'],
        'zip' => ['zip', 'rar', '7z', 'tar.gz', 'tar'],
        'audio' => ['mp3', 'mp4', 'avi', 'wma', 'wav', 'ape', 'flac'],
        'file' => ['doc', 'rtf', 'docx', 'ppt', 'xlsx'],
    ];

    /**
     * UploadLogic constructor.
     */
    public function __construct($ossBucket='',$fileType='image')
    {
        //设置oss配置
        $this->ossConfig = Yii::$app->params['oss'];
        if(!empty($ossBucket)){
            $this->ossConfig['bucket'] = $ossBucket;
        }

        //设置默认图片格式
        $this->setFileType($fileType);
    }

    /**
     * 文件上传
     * @param $file 要上传的文件
     * @param string $storageServer oss是阿里云存储 local是本地存储
     * @return array
     * @throws \OSS\Core\OssException
     */
    public function upload($file, $storageServer = 'oss')
    {
        //获取缓存文件信息
        $this->tempFile = $file['tmp_name'];
        $this->tempFileParts = pathinfo($file['name']);
        $this->tempFileExt = strtolower($this->tempFileParts['extension']);
        //对上传的文件类型进行判断
        if (!$this->_hasFileExt($this->tempFileExt)) {
            return ComBase::getReturnArray([], ComBase::CODE_PARAM_FORMAT_ERROR, '不支持该类型的文件');
        }
        switch ($storageServer) {
            //本地化存储
            case 'local':
                //设置本地的存储位置
                if (empty($this->filePath)) {
                    $this->setUploadPath();
                }
                $this->filePath .= '.' . $this->tempFileExt;
                $result = $this->_localUpload();
                break;
            //阿里云存储
            case 'oss':
                $result = $this->_ossUpload();
                break;
            default:
                $result = ComBase::getReturnArray([], ComBase::CODE_PARAM_FORMAT_ERROR, '错误的存储方式:' . $storageServer);
        }
        return $result;
    }

    /**
     * 删除已上传的文件
     * @param $file 文件路径
     * @param string $storageServer oss是阿里云存储 local是本地存储
     * @return array
     * @throws \OSS\Core\OssException
     */
    public function delete($file, $storageServer = 'oss')
    {
        switch ($storageServer) {
            case 'local':
                //功能待开发
                exit;
            case 'oss':
                $result = $this->_ossDelete($file);
                break;
            default:
                $result = ComBase::getReturnArray([], 411, '错误的存储方式');
        }
        return $result;
    }

    /**
     * 本地化存储
     * @return array
     */
    private function _localUpload()
    {
        //获取绝对路径
        $absolutePath = ComBase::getUploadRootPath() . $this->filePath;
        $mkdirPath = dirname($absolutePath);
        //创建文件夹
        if (!file_exists($mkdirPath) && !mkdir($mkdirPath, 0644, true)) {
            return ComBase::getReturnArray([], 414, '没有足够的上传权限');
        }
        if (!move_uploaded_file($this->tempFile, $absolutePath)) {
            return ComBase::getReturnArray([], 415, '上传文件失败');
        }
        $url = Yii::$app->params['local_static_link'] . $this->filePath;
        return ComBase::getReturnArray(['url' => $url]);
    }

    /**
     * 阿里云OSS存储
     * @return array
     * @throws \OSS\Core\OssException
     */
    private function _ossUpload()
    {
        $oss = new UploadOSS();
        $ossName = $oss->getOssName('.' . $this->tempFileExt);
        $result = $oss->upload($ossName, $this->tempFile);
        unlink($this->tempFile);
        if (!$result) {
            return ComBase::getReturnArray([], ComBase::CODE_SERVER_BUSY, '文件上传失败');
        }
        $url = $oss->getOssUrl($ossName);
        return ComBase::getReturnArray(['url' => $url]);
    }

    /**
     * 本地文件上传至阿里云OSS端
     * @param $file
     * @return array
     * @throws \OSS\Core\OssException
     */
    public function localToOss($file)
    {
        if (!file_exists($file)) {
            return ComBase::getReturnArray([], 417, '没有找到要上传的文件');
        }
        $this->tempFile = $file;
        $this->tempFileParts = pathinfo($file);
        $this->tempFileExt = strtolower($this->tempFileParts['extension']);
        return $this->_ossUpload();
    }

    /**
     * 删除阿里云OSS上面存储的文件
     * @param $file
     * @return array
     * @throws \OSS\Core\OssException
     */
    private function _ossDelete($file)
    {
        $oss = new UploadOSS();
        if ($oss->delete($file)) {
            return ComBase::getReturnArray([]);
        }
        return ComBase::getReturnArray([], 411, '删除文件失败');
    }

    /**
     * 设置本地存储路径
     * @param null $dir
     * @return bool
     */
    public function setUploadPath($dir = null)
    {
        if (empty($dir)) {
            $this->filePath = $this->getUploadPath('upload');
        } else {
            $this->filePath = $dir;
        }
        return true;
    }

    public function getUploadPath($dirName = 'upload'){
        $filePath = '/'.$dirName.'/' . date('Ymd');
        $filePath .= '/' . md5(time() .'_'. mt_rand(1000000, 9000000).ComBase::getMd5Key());
        return $filePath;
    }

    /**
     * 设置文件上传类型
     * @param string $fileType 支持的类型:image, zip, audio, file
     * @return bool
     */
    public function setFileType($fileType = 'image')
    {
        if (in_array($fileType, array_keys($this->fileExt))) {
            $this->fileType = $fileType;
            return true;
        }
        return false;
    }

    /**
     * 判断上传类型是否合法
     * @param $ext
     * @return bool
     */
    private function _hasFileExt($ext)
    {
        if (in_array($ext, $this->fileExt[$this->fileType],true)) {
            return true;
        }
        return false;
    }
}