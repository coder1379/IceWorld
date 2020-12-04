<?php

namespace common\services\message;

use Yii;
use common\services\user\UserModel;

/**
 * This is the model class for table "{{%mobile_sms}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $object_id
 * @property integer $object_type
 * @property integer $user_id
 * @property integer $area_num
 * @property string $mobile
 * @property string $other_mobiles
 * @property string $content
 * @property string $params_json
 * @property integer $send_time
 * @property integer $send_num
 * @property integer $type
 * @property integer $send_type
 * @property integer $sms_type
 * @property string $template
 * @property string $feedback
 * @property string $remark
 * @property integer $add_time
 * @property integer $status
 */
class MobileSmsModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:object_type,备注：短信对象类型
    public $objectTypePredefine=["0"=>"无","1"=>"用户","2"=>"订单"];
    //对应字段:type,备注：类型
    public $typePredefine=["1"=>"验证码","2"=>"通知","3"=>"消息"];
    //对应字段:send_type,备注：发送类型
    public $sendTypePredefine=["0"=>"未指定","1"=>"用户发起","2"=>"管理员发起","3"=>"任务发起"];
    //对应字段:sms_type,备注：短信渠道
    public $smsTypePredefine=["1"=>"阿里","2"=>"互亿","9"=>"其他"];
    //对应字段:status,备注：状态
    public $statusPredefine=["0"=>"不发送","1"=>"发送成功","2"=>"待发送","3"=>"发送失败"];


    /*
    * @关系内容写入
    */
     //对应字段：user_id,接收用户
     public function getUserRecord()
     {
        return $this->hasOne(UserModel::class, ['id' => 'user_id']);
     }

     //获取user_id,接收用户 的LIST
     public function getUserRecordList(){
            return [];
            //根据实际使用完善下方获取列表功能
            /*
            $array = UserModel::find()->select('id,name')->andWhere(['>','status',ComBase::DB_IS_DELETE_VAL])->orderBy("id desc")->limit(100)->asArray()->all();
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
            [['object_id', 'object_type', 'user_id', 'area_num', 'send_time', 'send_num', 'type', 'send_type', 'sms_type', 'add_time', 'status'], 'integer'],
            [['mobile'], 'required'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['other_mobiles', 'params_json', 'template', 'feedback'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 250],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','object_id','object_type','user_id','area_num','mobile','other_mobiles','content','params_json','send_time','send_num','type','send_type','sms_type','template','feedback','remark','add_time','status',],//创建场景

        'update' => ['name','object_id','object_type','user_id','area_num','mobile','other_mobiles','content','params_json','send_time','send_num','type','send_type','sms_type','template','feedback','remark','add_time','status',],//修改场景

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
            'name' => '短信名称',
            'object_id' => '短信对象ID',
            'object_type' => '短信对象类型',
            'user_id' => '接收用户',
            'area_num' => '地区号',
            'mobile' => '手机号',
            'other_mobiles' => '其他接收手机号',
            'content' => '发送内容',
            'params_json' => '附加参数',
            'send_time' => '发送时间',
            'send_num' => '发送次数',
            'type' => '类型',
            'send_type' => '发送类型',
            'sms_type' => '短信渠道',
            'template' => '发送模板',
            'feedback' => '发送反馈',
            'remark' => '短信备注',
            'add_time' => '添加时间',
            'status' => '状态',
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
