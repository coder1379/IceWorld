<?php

namespace common\services\admin;

use Yii;
use common\services\admin\AdminRoleModel;
use common\services\admin\AdminGroupModel;
use common\services\area\AreaModel;

/**
 * This is the model class for table "{{%administrator}}".
 *
 * @property integer $id
 * @property string $login_username
 * @property string $avatar
 * @property string $realname
 * @property string $nickname
 * @property string $mobile
 * @property string $remark
 * @property string $email
 * @property string $qq
 * @property string $wechat
 * @property string $company
 * @property integer $role_id
 * @property integer $group_id
 * @property integer $area_id
 * @property string $login_password
 * @property string $token
 * @property integer $add_admin_id
 * @property string $add_time
 * @property integer $show_sort
 * @property integer $type
 * @property integer $status
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property integer $online
 * @property integer $is_delete
 * @property integer $is_admin
 */
class AdministratorModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:type,备注：类型
    public $typePredefine=["1"=>"普通","2"=>"特殊"];
    //对应字段:status,备注：状态
    public $statusPredefine=["1"=>"启用","2"=>"冻结"];
    //对应字段:online,备注：在线状态
    public $onlinePredefine=["1"=>"离线","2"=>"在线"];


    /*
    * @关系内容写入
    */
     //对应字段：role_id,角色
     public function getAdminRoleRecord()
     {
        return $this->hasOne(AdminRoleModel::className(), ['id' => 'role_id']);
     }

     //获取role_id,角色 的LIST
     public function getAdminRoleRecordList(){
            $array = AdminRoleModel::find()->select('id,name')->where(['is_delete'=>0])->orderBy("id desc")->limit(100)->asArray()->all();
            $newArr = [];

            if(empty($array)!=true){
                foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
                }
            }
            return $newArr;
      }

     //对应字段：group_id,分组
     public function getAdminGroupRecord()
     {
        return $this->hasOne(AdminGroupModel::className(), ['id' => 'group_id']);
     }

     //获取group_id,分组 的LIST
     public function getAdminGroupRecordList(){
            $array = AdminGroupModel::find()->select('id,name')->where(['is_delete'=>0])->orderBy("id desc")->limit(100)->asArray()->all();
            $newArr = [];

            if(empty($array)!=true){
                foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
                }
            }
            return $newArr;
      }

     //对应字段：area_id,城市
     public function getAreaRecord()
     {
        return $this->hasOne(AreaModel::className(), ['id' => 'area_id']);
     }

     //获取area_id,城市 的LIST
     public function getAreaRecordList(){
            $array = AreaModel::find()->select('id,name')->where(['is_delete'=>0])->orderBy("id desc")->limit(100)->asArray()->all();
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
        return '{{%administrator}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['login_username', 'nickname', 'role_id', 'login_password'], 'required'],
            [['role_id', 'group_id', 'area_id', 'add_admin_id', 'show_sort', 'type', 'status', 'online', 'is_delete', 'is_admin'], 'integer'],
            [['add_time', 'last_login_time'], 'safe'],
            [['login_username', 'realname', 'nickname', 'company'], 'string', 'max' => 20],
            [['avatar'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['remark'], 'string', 'max' => 250],
            [['email', 'qq'], 'string', 'max' => 30],
            [['wechat', 'login_password', 'token'], 'string', 'max' => 50],
            [['last_login_ip'], 'string', 'max' => 15],
            [['login_username'], 'unique'],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['login_username','avatar','realname','nickname','mobile','remark','email','qq','wechat','company','role_id','group_id','area_id','login_password','token','add_admin_id','add_time','show_sort','type','status','last_login_time','last_login_ip','online','is_admin',],//创建场景

        'update' => ['login_username','avatar','realname','nickname','mobile','remark','email','qq','wechat','company','role_id','group_id','area_id','login_password','token','add_admin_id','add_time','show_sort','type','status','last_login_time','last_login_ip','online','is_admin',],//修改场景

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
            'login_username' => '登录用户名',
            'avatar' => '头像',
            'realname' => '真实姓名',
            'nickname' => '姓名',
            'mobile' => '手机号',
            'remark' => '备注',
            'email' => '邮箱',
            'qq' => 'QQ',
            'wechat' => '微信',
            'company' => '公司',
            'role_id' => '角色',
            'group_id' => '分组',
            'area_id' => '城市',
            'login_password' => '密码',
            'token' => 'token',
            'add_admin_id' => '添加人',
            'add_time' => '添加时间',
            'show_sort' => '排序',
            'type' => '类型',
            'status' => '状态',
            'last_login_time' => '最后登陆时间',
            'last_login_ip' => '最后登陆IP',
            'online' => '在线状态',
            'is_delete' => '是否删除',
            'is_admin' => '是否为超级管理员',
        ];
    }

    /**
     * @inheritdoc
     * @return AdministratorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdministratorQuery(get_called_class());
    }
}
