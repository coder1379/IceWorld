<?php

namespace common\services\admin;

use Yii;
use common\services\admin\AdministratorModel;

/**
 * This is the model class for table "{{%admin_group}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $remark
 * @property integer $show_sort
 * @property integer $type
 * @property integer $status
 * @property string $add_time
 * @property integer $add_admin_id
 * @property integer $is_delete
 */
class AdminGroupModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:type,备注：类型
    public $typePredefine=["1"=>"普通","2"=>"特殊"];
    //对应字段:status,备注：状态
    public $statusPredefine=["1"=>"启用","2"=>"冻结"];


    /*
    * @关系内容写入
    */
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
        return '{{%admin_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['name'], 'required'],
            [['show_sort', 'type', 'status', 'add_admin_id', 'is_delete'], 'integer'],
            [['add_time'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 250],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','remark','show_sort','type','status','add_time','add_admin_id',],//创建场景

        'update' => ['name','remark','show_sort','type','status','add_time','add_admin_id',],//修改场景

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
            'name' => '分组名',
            'remark' => '备注',
            'show_sort' => '排序',
            'type' => '类型',
            'status' => '状态',
            'add_time' => '添加时间',
            'add_admin_id' => '添加人',
            'is_delete' => '是否删除',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminGroupQuery(get_called_class());
    }
}
