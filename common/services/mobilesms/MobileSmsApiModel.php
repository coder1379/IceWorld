<?php
/**
 * Created by PhpStorm.
 * User: majie
 * Date: 2018/6/7
 * Time: 17:37
 */

namespace common\services\mobilesms;


class MobileSmsApiModel extends MobileSmsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {

        ////////////字段验证规则
        return [
            [['object_id', 'object_type', 'user_id', 'status', 'send_number', 'type', 'send_type', 'sms_type', 'is_delete'], 'integer'],
            [['mobile', 'contents'], 'required'],
            [['contents'], 'string'],
            [['add_time', 'send_time'], 'safe'],
            [['access_ip', 'mobile'], 'string', 'max' => 20],
            [['params_json', 'feedback'], 'string', 'max' => 255],
            [['template'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 250],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
        return [
            'create' => ['object_id','object_type','user_id','access_ip','mobile','contents','params_json','status','add_time','send_time','send_number','type','send_type','sms_type','template','feedback','remark',],//创建场景

            'update' => ['object_id','object_type','user_id','access_ip','mobile','contents','params_json','status','add_time','send_time','send_number','type','send_type','sms_type','template','feedback','remark',],//修改场景

            'delete' => ['is_delete'],//删除场景
        ];
    }
}