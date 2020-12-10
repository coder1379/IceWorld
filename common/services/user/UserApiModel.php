<?php

namespace common\services\user;

use Yii;

class UserApiModel extends \common\services\user\UserModel
{

    //apiModel独有function 用于控制哪些字段输出到前端
    public function fieldsScenarios()
    {
        return [
            'list' => ['id','name','status','type','level','realname','avatar','introduce','sex','birthday','district','title','add_time',],//列表

            'detail' => ['id','name','status','type','level','realname','avatar','introduce','sex','birthday','district','title','add_time',],//详情
        ];
    }

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function rules()
    {
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
    }*/

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function scenarios()
    {
        return [
            'create' => ['name','mobile','username','login_password','status','type','level','realname','email','avatar','introduce','sex','birthday','district','title','token',],//创建场景

            'update' => ['name','mobile','username','login_password','status','type','level','realname','email','avatar','introduce','sex','birthday','district','title','token',],//修改场景

            'delete' => ['status'],//删除场景 status = -1
        ];
    }*/


    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function attributeLabels()
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
    }*/

}
