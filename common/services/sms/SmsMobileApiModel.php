<?php

namespace common\services\sms;

use Yii;

class SmsMobileApiModel extends \common\services\sms\SmsMobileModel
{

    //apiModel独有function 用于控制哪些字段输出到前端
    public function fieldsScenarios()
    {
        return [
            'list' => ['id','name','object_id','object_type','user_id','area_code','mobile','other_mobiles','content','params_json','send_time','send_num','type','send_type','sms_type','template','feedback','remark','add_time','status',],//列表

            'detail' => ['id','name','object_id','object_type','user_id','area_code','mobile','other_mobiles','content','params_json','send_time','send_num','type','send_type','sms_type','template','feedback','remark','add_time','status',],//详情
        ];
    }

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function rules()
    {
        return [
            [['object_id', 'object_type', 'user_id', 'area_code', 'send_time', 'send_num', 'type', 'send_type', 'sms_type', 'add_time', 'status'], 'integer'],
            [['mobile'], 'required'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['other_mobiles', 'params_json', 'template', 'feedback'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 250],
        ];
    }*/

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function scenarios()
    {
        return [
            'create' => ['name','object_id','object_type','user_id','area_code','mobile','other_mobiles','content','params_json','send_time','send_num','type','send_type','sms_type','template','feedback','remark','status',],//创建场景

            'update' => ['name','object_id','object_type','user_id','area_code','mobile','other_mobiles','content','params_json','send_time','send_num','type','send_type','sms_type','template','feedback','remark','status',],//修改场景

            'delete' => ['status'],//删除场景 status = -1
        ];
    }*/


    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '短信名称',
            'object_id' => '短信对象ID',
            'object_type' => '短信对象类型',
            'user_id' => '接收用户',
            'area_code' => '地区号',
            'mobile' => '手机号',
            'other_mobiles' => '其他接收手机号',
            'content' => '发送内容',
            'params_json' => '附加参数',
            'send_time' => '发送时间',
            'send_num' => '发送次数',
            'type' => '类型',
            'send_type' => '发送类型',
            'sms_type' => '短信渠道',
            'template' => '发送模板',
            'feedback' => '发送反馈',
            'remark' => '短信备注',
            'add_time' => '添加时间',
            'status' => '状态',
            ];
    }*/

}
