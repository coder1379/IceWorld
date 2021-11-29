<?php

namespace common\services\visitor;

use Yii;
use common\ComBase;
use common\services\application\AppModel;
use common\services\sourcechannel\SourceChannelModel;

/**
 * This is the model class for table "{{%device_visitor}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $device_code
 * @property integer $app_id
 * @property integer $source_channel_id
 * @property integer $type
 * @property string $system
 * @property string $model
 * @property string $device_desc
 * @property string $district
 * @property string $ip
 * @property integer $add_time
 * @property integer $convert_time
 */
class DeviceVisitorModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%device_visitor}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['user_id', 'app_id', 'source_channel_id', 'type', 'add_time', 'convert_time'], 'integer'],
            [['device_code'], 'required'],
            [['device_code', 'ip'], 'string', 'max' => 64],
            [['system', 'model', 'district'], 'string', 'max' => 30],
            [['device_desc'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['user_id','device_code','app_id','source_channel_id','type','system','model','device_desc','district','ip','add_time','convert_time',],//创建场景

        'update' => ['user_id','device_code','app_id','source_channel_id','type','system','model','device_desc','district','ip','add_time','convert_time',],//修改场景

        'delete' => ['status'],//删除场景
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '设备游客ID',
            'user_id' => '用户ID',
            'device_code' => '设备唯一码',
            'app_id' => '应用',
            'source_channel_id' => '渠道',
            'type' => '设备分类',
            'system' => '设备系统',
            'model' => '设备型号',
            'device_desc' => '设备描述',
            'district' => '地区',
            'ip' => 'IP',
            'add_time' => '生成时间',
            'convert_time' => '转化时间',
        ];
    }

    /**
     * @inheritdoc
     * @return DeviceVisitorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DeviceVisitorQuery(get_called_class());
    }

    /*
    * @配置信息写入
    */
    //对应字段:type,备注：设备分类
    public $typePredefine=["0"=>"未知","1"=>"移动端","2"=>"PC端","3"=>"浏览器"];


    /*
    * @关系内容写入
    */
    //对应字段：app_id,应用
    public function getAppRecord()
    {
        return $this->hasOne(AppModel::class, ['id' => 'app_id']);
    }

    //获取app_id,应用 的LIST
    public function getAppRecordList(){
        return [];
        //根据实际使用完善下方获取列表功能
        /*
        $array = AppModel::find()->select('id,name')->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE])->orderBy("id desc")->limit(100)->asArray()->all();
        $newArr = [];

        if(empty($array)!=true){
            foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
            }
        }
        return $newArr;
        */
    }

       //对应字段：source_channel_id,渠道
    public function getSourceChannelRecord()
    {
        return $this->hasOne(SourceChannelModel::class, ['id' => 'source_channel_id']);
    }

    //获取source_channel_id,渠道 的LIST
    public function getSourceChannelRecordList(){
        return [];
        //根据实际使用完善下方获取列表功能
        /*
        $array = SourceChannelModel::find()->select('id,name')->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE])->orderBy("id desc")->limit(100)->asArray()->all();
        $newArr = [];

        if(empty($array)!=true){
            foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
            }
        }
        return $newArr;
        */
    }

    /**
     * 游客名称拼接
     */
    public function getName(){
        return $this->typePredefine[$this->type].'_'.$this->system . '_' . $this->model . '_' . $this->ip;
    }

   


}
