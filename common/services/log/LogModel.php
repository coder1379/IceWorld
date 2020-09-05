<?php

namespace common\services\log;

use Yii;

/**
 * This is the model class for table "{{%log}}".
 *
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class LogModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:level,备注：等级
    public $levelPredefine=["1"=>"严重错误","2"=>"警告","3"=>"日志","4"=>"普通日志","5"=>"未知"];


    /*
    * @关系内容写入
    */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['level'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['level','category','log_time','prefix','message',],//创建场景

        'update' => ['level','category','log_time','prefix','message',],//修改场景

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
            'level' => '等级',
            'category' => '分类标记',
            'log_time' => '记录时间',
            'log_time_start' => '记录时间开始',
            'log_time_end' => '记录时间结束',
            'prefix' => '前缀',
            'message' => '内容',
        ];
    }

    /**
     * @inheritdoc
     * @return LogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LogQuery(get_called_class());
    }
}
