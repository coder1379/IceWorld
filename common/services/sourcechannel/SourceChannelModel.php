<?php

namespace common\services\sourcechannel;

use Yii;
use common\ComBase;
use common\services\application\AppModel;
use common\services\area\AreaModel;

/**
 * This is the model class for table "{{%source_channel}}".
 *
 * @property integer $id
 * @property integer $app_id
 * @property string $name
 * @property string $channel_code
 * @property integer $status
 * @property integer $type
 * @property string $img_url
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property string $address
 * @property string $liaison
 * @property string $phone
 * @property string $mobile
 * @property string $email
 * @property string $qq
 * @property string $weixin
 * @property string $weibo
 * @property string $remark
 * @property string $keywords
 * @property string $description
 * @property string $details
 * @property integer $add_time
 * @property integer $update_time
 */
class SourceChannelModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%source_channel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['name'], 'required'],
            [['app_id', 'status', 'type', 'province_id', 'city_id', 'area_id', 'add_time', 'update_time'], 'integer'],
            [['details'], 'string'],
            [['name', 'channel_code'], 'string', 'max' => 32],
            [['img_url', 'remark', 'keywords', 'description'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 100],
            [['liaison', 'phone', 'mobile'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 50],
            [['qq', 'weixin', 'weibo'], 'string', 'max' => 30],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['app_id','name','channel_code','status','type','img_url','province_id','city_id','area_id','address','liaison','phone','mobile','email','qq','weixin','weibo','remark','keywords','description','details','add_time','update_time',],//创建场景

        'update' => ['app_id','name','channel_code','status','type','img_url','province_id','city_id','area_id','address','liaison','phone','mobile','email','qq','weixin','weibo','remark','keywords','description','details','add_time','update_time',],//修改场景

        'delete' => ['status'],//删除场景
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '渠道ID',
            'app_id' => '应用',
            'name' => '渠道名称',
            'channel_code' => '渠道唯一码',
            'status' => '渠道状态',
            'type' => '渠道分类',
            'img_url' => '渠道配图',
            'province_id' => '省',
            'city_id' => '市',
            'area_id' => '区',
            'address' => '地址',
            'liaison' => '联络人',
            'phone' => '电话',
            'mobile' => '手机号',
            'email' => '邮箱',
            'qq' => 'QQ',
            'weixin' => '微信',
            'weibo' => '微博',
            'remark' => '备注',
            'keywords' => '关键字',
            'description' => '描述',
            'details' => '详细介绍',
            'add_time' => '添加时间',
            'update_time' => '修改时间',
        ];
    }

    /**
     * @inheritdoc
     * @return SourceChannelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SourceChannelQuery(get_called_class());
    }

    /*
    * @配置信息写入
    */
    //对应字段:status,备注：渠道状态
    public $statusPredefine=["1"=>"正常","2"=>"冻结"];
    //对应字段:type,备注：渠道分类
    public $typePredefine=["0"=>"未知","1"=>"普通","2"=>"特别"];


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

       //对应字段：province_id,省
    public function getProvinceRecord()
    {
        return $this->hasOne(AreaModel::class, ['id' => 'province_id']);
    }

    //获取province_id,省 的LIST
    public function getProvinceRecordList(){
        return [];
        //根据实际使用完善下方获取列表功能
        /*
        $array = AreaModel::find()->select('id,name')->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE])->orderBy("id desc")->limit(100)->asArray()->all();
        $newArr = [];

        if(empty($array)!=true){
            foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
            }
        }
        return $newArr;
        */
    }

       //对应字段：city_id,市
    public function getCityRecord()
    {
        return $this->hasOne(AreaModel::class, ['id' => 'city_id']);
    }

    //获取city_id,市 的LIST
    public function getCityRecordList(){
        return [];
        //根据实际使用完善下方获取列表功能
        /*
        $array = AreaModel::find()->select('id,name')->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE])->orderBy("id desc")->limit(100)->asArray()->all();
        $newArr = [];

        if(empty($array)!=true){
            foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
            }
        }
        return $newArr;
        */
    }

       //对应字段：area_id,区
    public function getAreaRecord()
    {
        return $this->hasOne(AreaModel::class, ['id' => 'area_id']);
    }

    //获取area_id,区 的LIST
    public function getAreaRecordList(){
        return [];
        //根据实际使用完善下方获取列表功能
        /*
        $array = AreaModel::find()->select('id,name')->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE])->orderBy("id desc")->limit(100)->asArray()->all();
        $newArr = [];

        if(empty($array)!=true){
            foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
            }
        }
        return $newArr;
        */
    }

   


}
