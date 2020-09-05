<?php

namespace common\services\systemconfig;

use Yii;

/**
 * This is the model class for table "{{%system_config}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $c_val
 * @property string $desc
 * @property integer $add_time
 * @property integer $update_time
 */
class SystemConfigModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */


    /*
    * @关系内容写入
    */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['name', 'desc'], 'required'],
            [['add_time', 'update_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['c_val', 'desc'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','c_val','desc','add_time','update_time',],//创建场景

        'update' => ['name','c_val','desc','add_time','update_time',],//修改场景

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
            'name' => '配置名称',
            'c_val' => '配置值',
            'desc' => '描述',
            'add_time' => '添加时间',
            'update_time' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     * @return SystemConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SystemConfigQuery(get_called_class());
    }
}
