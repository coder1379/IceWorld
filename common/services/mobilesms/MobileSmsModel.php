<?php

namespace common\services\mobilesms;

use Yii;
use common\services\user\UserModel;

/**
 * This is the model class for table "{{%mobile_sms}}".
 *
 * @property integer $id
 * @property integer $object_id
 * @property integer $object_type
 * @property integer $user_id
 * @property string $access_ip
 * @property string $mobile
 * @property string $contents
 * @property string $params_json
 * @property integer $status
 * @property string $add_time
 * @property string $send_time
 * @property integer $send_number
 * @property integer $type
 * @property integer $send_type
 * @property integer $sms_type
 * @property string $template
 * @property string $feedback
 * @property string $remark
 * @property integer $is_delete
 */
class MobileSmsModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:object_type,备注：消息对象类型
    public $objectTypePredefine=["1"=>"订单","99"=>"其他"];
    //对应字段:status,备注：状态
    public $statusPredefine=["0"=>"未发送","1"=>"发送中","2"=>"发送成功","4"=>"发送失败"];
    //对应字段:type,备注：类型
    public $typePredefine=["1"=>"验证码","2"=>"通知"];
    //对应字段:send_type,备注：发送类型
    public $sendTypePredefine=["0"=>"未指定","1"=>"用户发起","2"=>"管理员发起","3"=>"任务发起"];
    //对应字段:sms_type,备注：消息类型
    public $smsTypePredefine=["1"=>"阿里短信","2"=>"互亿短信","9"=>"其他"];


    /*
    * @关系内容写入
    */
     //对应字段：user_id,用户
     public function getUserRecord()
     {
        return $this->hasOne(UserModel::className(), ['id' => 'user_id']);
     }

     //获取user_id,用户 的LIST
     public function getUserRecordList(){
            $array = UserModel::find()->select('id,name')->where(['is_delete'=>0])->orderBy("id desc")->limit(100)->asArray()->all();
            $newArr = [];

            if(empty($array)!=true){
                foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
                }
            }
            return $newArr;
      }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mobile_sms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['object_id', 'object_type', 'user_id', 'status', 'send_number', 'type', 'send_type', 'sms_type', 'is_delete'], 'integer'],
            [['mobile', 'contents'], 'required'],
            [['contents'], 'string'],
            [['add_time', 'send_time'], 'safe'],
            [['access_ip', 'mobile'], 'string', 'max' => 20],
            [['params_json', 'feedback'], 'string', 'max' => 255],
            [['template'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 250],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['object_id','object_type','user_id','access_ip','mobile','contents','params_json','status','add_time','send_time','send_number','type','send_type','sms_type','template','feedback','remark',],//创建场景

        'update' => ['object_id','object_type','user_id','access_ip','mobile','contents','params_json','status','add_time','send_time','send_number','type','send_type','sms_type','template','feedback','remark',],//修改场景

        'delete' => ['is_delete'],//删除场景
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => '消息对象ID',
            'object_type' => '消息对象类型',
            'user_id' => '用户',
            'access_ip' => '访问IP',
            'mobile' => '手机号',
            'contents' => '发送内容',
            'params_json' => '其他参数json',
            'status' => '状态',
            'add_time' => '添加时间',
            'send_time' => '发送时间',
            'send_number' => '发送次数',
            'type' => '类型',
            'send_type' => '发送类型',
            'sms_type' => '消息类型',
            'template' => '发送模板',
            'feedback' => '反馈',
            'remark' => '短信备注',
            'is_delete' => '是否删除',
        ];
    }

    /**
     * @inheritdoc
     * @return MobileSmsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MobileSmsQuery(get_called_class());
    }
}
