<?php

namespace common\services\user;

use Yii;

class UserApiModel extends \common\services\user\UserModel
{

    //apiModel独有function 用于控制哪些字段输出到前端
    public function fieldsScenarios()
    {
        return [
            'list' => ['id','name','mobile','qq','truename','account','email','wx_openid','wx_unionid','add_time','reg_ip','last_login_ip','token','token_out_time','last_login_time','head_portrait','birthday','sex','inviter_user_id','add_admin_id','introduce','status','type','user_id',],//列表

            'detail' => ['id','name','mobile','qq','truename','account','email','wx_openid','wx_unionid','add_time','reg_ip','last_login_ip','token','token_out_time','last_login_time','head_portrait','birthday','sex','inviter_user_id','add_admin_id','introduce','status','type','user_id',],//详情
        ];
    }

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function rules()
    {
        return [
            [['name'], 'required'],
            [['add_time', 'token_out_time', 'last_login_time', 'sex', 'inviter_user_id', 'add_admin_id', 'status', 'type', 'is_delete', 'user_id'], 'integer'],
            [['birthday'], 'safe'],
            [['name', 'truename', 'account', 'wx_openid', 'wx_unionid'], 'string', 'max' => 50],
            [['login_password'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['qq'], 'string', 'max' => 15],
            [['email', 'reg_ip', 'last_login_ip'], 'string', 'max' => 30],
            [['token'], 'string', 'max' => 56],
            [['head_portrait', 'introduce'], 'string', 'max' => 255],
        ];
    }*/

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function scenarios()
    {
        return [
            'create' => ['name','login_password','mobile','qq','truename','account','email','wx_openid','wx_unionid','reg_ip','last_login_ip','token','token_out_time','last_login_time','head_portrait','birthday','sex','inviter_user_id','add_admin_id','introduce','status','type','user_id',],//创建场景

            'update' => ['name','login_password','mobile','qq','truename','account','email','wx_openid','wx_unionid','reg_ip','last_login_ip','token','token_out_time','last_login_time','head_portrait','birthday','sex','inviter_user_id','add_admin_id','introduce','status','type','user_id',],//修改场景

            'delete' => ['is_delete'],//删除场景
        ];
    }*/


    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '昵称',
            'login_password' => '密码',
            'mobile' => '手机号',
            'qq' => 'QQ',
            'truename' => '真名',
            'account' => '用户名',
            'email' => '邮箱',
            'wx_openid' => '微信OPENID',
            'wx_unionid' => '微信unionid',
            'add_time' => '注册时间',
            'reg_ip' => '注册IP',
            'last_login_ip' => '最后登录IP',
            'token' => 'token',
            'token_out_time' => 'token_过期时间',
            'last_login_time' => '最后登录时间',
            'head_portrait' => '头像',
            'birthday' => '生日',
            'sex' => '性别',
            'inviter_user_id' => '邀请人',
            'add_admin_id' => '添加人',
            'introduce' => '自我介绍',
            'status' => '状态',
            'type' => '类型',
            'is_delete' => '是否删除',
            'user_id' => '用户ID',
            ];
    }*/

}
