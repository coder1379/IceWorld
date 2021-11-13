<?php

namespace common\services\area;

use Yii;


/**
 * This is the model class for table "{{%area}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $parent_id
 * @property integer $show_sort
 * @property integer $is_delete
 */
class AreaModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    public $statusPredefine=["1"=>"正常","2"=>"冻结"];

    /*
    * @关系内容写入
    */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['name'], 'required'],
            [['status','type', 'parent_id', 'show_sort', 'is_delete'], 'integer'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','status','type','parent_id','show_sort',],//创建场景

        'update' => ['name','status','type','parent_id','show_sort',],//修改场景

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
            'name' => '地区名称',
            'status' => '状态',
            'type' => '地区类型',
            'parent_id' => '上级区域',
            'show_sort' => '排序',
            'is_delete' => '删除标记',
        ];
    }

    /**
     * @inheritdoc
     * @return AreaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AreaQuery(get_called_class());
    }
}
