<?php

namespace common\services\user;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $mobile
 * @property string $username
 * @property string $login_password
 * @property integer $status
 * @property integer $type
 * @property integer $level
 * @property string $realname
 * @property string $email
 * @property string $avatar
 * @property string $introduce
 * @property integer $sex
 * @property integer $birthday
 * @property string $district
 * @property string $title
 * @property string $token
 * @property integer $add_time
 */
class UserModel extends \yii\db\ActiveRecord
{
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
            [['status', 'type', 'level', 'sex', 'birthday', 'add_time'], 'integer'],
            [['name', 'username', 'realname', 'district', 'title'], 'string', 'max' => 30],
            [['mobile'], 'string', 'max' => 20],
            [['login_password'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 50],
            [['avatar'], 'string', 'max' => 255],
            [['introduce'], 'string', 'max' => 200],
            [['token'], 'string', 'max' => 100],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','mobile','username','login_password','status','type','level','realname','email','avatar','introduce','sex','birthday','district','title','token','add_time',],//创建场景

        'update' => ['name','mobile','username','login_password','status','type','level','realname','email','avatar','introduce','sex','birthday','district','title','token','add_time',],//修改场景

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
            'name' => '昵称',
            'mobile' => '手机号',
            'username' => '用户名',
            'login_password' => '密码',
            'status' => '状态',
            'type' => '用户类型',
            'level' => '等级',
            'realname' => '真实姓名',
            'email' => '邮箱',
            'avatar' => '头像',
            'introduce' => '自我介绍',
            'sex' => '性别',
            'birthday' => '生日',
            'district' => '地区',
            'title' => '头衔',
            'token' => 'token',
            'add_time' => '注册时间',
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

    /*
    * @配置信息写入
    */
    //对应字段:status,备注：状态
    public $statusPredefine=["0"=>"未设置","1"=>"正常","2"=>"冻结"];
    //对应字段:type,备注：用户类型
    public $typePredefine=["0"=>"未设置","1"=>"注册用户","2"=>"特权用户"];
    //对应字段:sex,备注：性别
    public $sexPredefine=["0"=>"未设置","1"=>"男","2"=>"女"];


    /*
    * @关系内容写入
    */



}
