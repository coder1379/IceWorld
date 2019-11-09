<?php

namespace common\services\adminauth;

use Yii;
use common\services\administrator\AdministratorModel;

/**
 * This is the model class for table "{{%admin_auth}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $auth_flag
 * @property integer $parent_id
 * @property string $other_auth_url
 * @property integer $type
 * @property integer $status
 * @property integer $add_admin_id
 * @property string $add_time
 * @property integer $show_sort
 * @property integer $is_delete
 */
class AdminAuthModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:type,备注：类型
    public $typePredefine=["1"=>"controller","2"=>"action"];
    //对应字段:status,备注：状态
    public $statusPredefine=["1"=>"启用","2"=>"冻结"];


    /*
    * @关系内容写入
    */
     //对应字段：parent_id,上级
     public function getParentAdminAuthRecord()
     {
        return $this->hasOne(AdminAuthModel::className(), ['id' => 'parent_id']);
     }

     //获取parent_id,上级 的LIST
     public function getParentAdminAuthRecordList(){
            $array = AdminAuthModel::find()->select('id,name')->where(['is_delete'=>0,'type'=>1])->orderBy("id desc")->limit(100)->asArray()->all();
            $newArr = [];
            $newArr[0]='请选择上级';
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
        return '{{%admin_auth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['name', 'auth_flag'], 'required'],
            [['parent_id', 'type', 'status', 'add_admin_id', 'show_sort', 'is_delete'], 'integer'],
            [['add_time'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['auth_flag'], 'string', 'max' => 30],
            [['other_auth_url'], 'string', 'max' => 250],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','auth_flag','parent_id','other_auth_url','type','status','add_admin_id','add_time','show_sort',],//创建场景

        'update' => ['name','auth_flag','parent_id','other_auth_url','type','status','add_admin_id','add_time','show_sort',],//修改场景

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
            'name' => '名称',
            'auth_flag' => 'controller/action',
            'parent_id' => '上级',
            'other_auth_url' => '其他权限',
            'type' => '类型',
            'status' => '状态',
            'add_admin_id' => '添加人',
            'add_time' => '添加时间',
            'show_sort' => '排序',
            'is_delete' => '是否删除',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminAuthQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminAuthQuery(get_called_class());
    }
}
