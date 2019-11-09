<?php

namespace common\services\user;

use Yii;
use common\services\administrator\AdministratorModel;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $login_password
 * @property string $mobile
 * @property string $qq
 * @property string $truename
 * @property string $account
 * @property string $email
 * @property string $wx_openid
 * @property string $wx_unionid
 * @property string $add_time
 * @property string $reg_ip
 * @property string $last_login_ip
 * @property string $token
 * @property string $token_out_time
 * @property string $last_login_time
 * @property string $head_portrait
 * @property string $birthday
 * @property integer $sex
 * @property integer $inviter_user_id
 * @property integer $add_admin_id
 * @property string $introduce
 * @property integer $status
 * @property integer $type
 * @property integer $is_delete
 */
class UserModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:sex,备注：性别
    public $sexPredefine=["0"=>"未选择","1"=>"男","2"=>"女"];
    //对应字段:status,备注：状态
    public $statusPredefine=["1"=>"启用","2"=>"冻结"];
    //对应字段:type,备注：类型
    public $typePredefine=["1"=>"普通","2"=>"特殊"];


    /*
    * @关系内容写入
    */
     //对应字段：inviter_user_id,邀请人
     public function getInviterUserRecord()
     {
        return $this->hasOne(UserModel::className(), ['id' => 'inviter_user_id']);
     }

     //获取inviter_user_id,邀请人 的LIST
     public function getInviterUserRecordList(){
            $array = UserModel::find()->select('id,name')->where(['is_delete'=>0])->orderBy("id desc")->limit(100)->asArray()->all();
            $newArr = [];

            if(empty($array)!=true){
                foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
                }
            }
            return $newArr;
      }

     //对应字段：add_admin_id,添加人
     public function getAddAdminRecord()
     {
        return $this->hasOne(AdministratorModel::className(), ['id' => 'add_admin_id']);
     }

     //获取add_admin_id,添加人 的LIST
     public function getAddAdminRecordList(){
            $array = AdministratorModel::find()->select('id,nickname')->where(['is_delete'=>0])->orderBy("id desc")->limit(100)->asArray()->all();
            $newArr = [];

            if(empty($array)!=true){
                foreach($array as $v){
                $newArr[$v["id"]]=$v["nickname"];
                }
            }
            return $newArr;
      }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['name'], 'required'],
            [['add_time', 'token_out_time', 'last_login_time', 'birthday'], 'safe'],
            [['sex', 'inviter_user_id', 'add_admin_id', 'status', 'type', 'is_delete'], 'integer'],
            [['name', 'truename', 'account', 'wx_openid', 'wx_unionid'], 'string', 'max' => 50],
            [['login_password'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['qq'], 'string', 'max' => 15],
            [['email', 'reg_ip', 'last_login_ip'], 'string', 'max' => 30],
            [['token'], 'string', 'max' => 56],
            [['head_portrait', 'introduce'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','login_password','mobile','qq','truename','account','email','wx_openid','wx_unionid','add_time','reg_ip','last_login_ip','token','token_out_time','last_login_time','head_portrait','birthday','sex','inviter_user_id','add_admin_id','introduce','status','type',],//创建场景

        'update' => ['name','login_password','mobile','qq','truename','account','email','wx_openid','wx_unionid','add_time','reg_ip','last_login_ip','token','token_out_time','last_login_time','head_portrait','birthday','sex','inviter_user_id','add_admin_id','introduce','status','type',],//修改场景

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
            'name' => '昵称',
            'login_password' => '密码',
            'mobile' => '手机号',
            'qq' => 'QQ',
            'truename' => '真名',
            'account' => '用户名',
            'email' => '邮箱',
            'wx_openid' => '微信OPENID',
            'wx_unionid' => '微信unionid',
            'add_time' => '注册时间',
            'reg_ip' => '注册IP',
            'last_login_ip' => '最后登录IP',
            'token' => 'token',
            'token_out_time' => 'token_过期时间',
            'last_login_time' => '最后登录时间',
            'head_portrait' => '头像',
            'birthday' => '生日',
            'sex' => '性别',
            'inviter_user_id' => '邀请人',
            'add_admin_id' => '添加人',
            'introduce' => '自我介绍',
            'status' => '状态',
            'type' => '类型',
            'is_delete' => '是否删除',
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
