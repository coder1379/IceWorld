<?php

namespace common\services\admin;

use Yii;
use common\services\admin\AdministratorModel;

/**
 * This is the model class for table "{{%admin_login_log}}".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $type
 * @property integer $add_time
 * @property string $ip
 * @property string $device_desc
 * @property integer $status
 */
class AdminLoginLogModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_login_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['admin_id', 'type', 'add_time', 'status'], 'integer'],
            [['ip'], 'string', 'max' => 64],
            [['device_desc'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['admin_id','type','add_time','ip','device_desc','status',],//创建场景

        'update' => ['admin_id','type','add_time','ip','device_desc','status',],//修改场景

        'delete' => ['status'],//删除场景
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => '名称',
            'type' => '登录类型',
            'add_time' => '登录时间',
            'ip' => 'ip',
            'device_desc' => '设备描述',
            'status' => '状态',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminLoginLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminLoginLogQuery(get_called_class());
    }

    /*
    * @配置信息写入
    */
    //对应字段:type,备注：登录类型
    public $typePredefine=["0"=>"未设置","1"=>"PC","2"=>"客户端"];
    //对应字段:status,备注：状态
    public $statusPredefine=["1"=>"可见"];


    /*
    * @关系内容写入
    */
    //对应字段：admin_id,名称
    public function getAdminRecord()
    {
        return $this->hasOne(AdministratorModel::class, ['id' => 'admin_id']);
    }

    //获取admin_id,名称 的LIST
    public function getAdminRecordList(){
        return [];
        //根据实际使用完善下方获取列表功能
        /*
        $array = AdministratorModel::find()->select('id,nickname')->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE])->orderBy("id desc")->limit(100)->asArray()->all();
        $newArr = [];

        if(empty($array)!=true){
            foreach($array as $v){
                $newArr[$v["id"]]=$v["nickname"];
            }
        }
        return $newArr;
        */
    }

   


}
