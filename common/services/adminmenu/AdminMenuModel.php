<?php

namespace common\services\adminmenu;

use Yii;
use common\services\administrator\AdministratorModel;

/**
 * This is the model class for table "{{%admin_menu}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $controller
 * @property string $c_action
 * @property integer $type
 * @property integer $status
 * @property string $icon
 * @property integer $parent_id
 * @property integer $m_level
 * @property integer $add_admin_id
 * @property string $add_time
 * @property integer $show_sort
 * @property integer $is_delete
 */
class AdminMenuModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:type,备注：类型
    public $typePredefine=["1"=>"普通","2"=>"特殊"];
    //对应字段:status,备注：状态
    public $statusPredefine=["1"=>"启用","2"=>"冻结"];
    //对应字段:m_level,备注：等级
    public $levelPredefine=["1"=>"顶级菜单","2"=>"二级菜单"];


    /*
    * @关系内容写入
    */
     //对应字段：parent_id,上级菜单
     public function getParentMenuRecord()
     {
        return $this->hasOne(AdminMenuModel::className(), ['id' => 'parent_id']);
     }

     //获取parent_id,上级菜单 的LIST
     public function getParentMenuRecordList(){
            $array = AdminMenuModel::find()->select('id,name')->where(['is_delete'=>0,'m_level'=>1])->orderBy("id desc")->limit(100)->asArray()->all();
            $newArr = [];
            $newArr[0]='请选择上级菜单';

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
        return '{{%admin_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['name', 'm_level'], 'required'],
            [['type', 'status', 'parent_id', 'm_level', 'add_admin_id', 'show_sort', 'is_delete'], 'integer'],
            [['add_time'], 'safe'],
            [['name', 'controller', 'c_action', 'icon'], 'string', 'max' => 20],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','controller','c_action','type','status','icon','parent_id','m_level','add_admin_id','add_time','show_sort',],//创建场景

        'update' => ['name','controller','c_action','type','status','icon','parent_id','m_level','add_admin_id','add_time','show_sort',],//修改场景

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
            'name' => '菜单名',
            'controller' => '对应controller',
            'c_action' => '对应action',
            'type' => '类型',
            'status' => '状态',
            'icon' => '图标',
            'parent_id' => '上级菜单',
            'm_level' => '等级',
            'add_admin_id' => '添加人',
            'add_time' => '添加时间',
            'show_sort' => '排序',
            'is_delete' => '是否删除',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminMenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminMenuQuery(get_called_class());
    }
}
