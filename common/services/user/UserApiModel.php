<?php

namespace common\services\user;

class UserApiModel extends UserModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
        return [
            [['add_time', 'token_out_time', 'last_login_time', 'birthday'], 'safe'],
            [['sex', 'inviter_user_id', 'add_admin_id', 'status', 'type', 'is_delete'], 'integer'],
            [['name', 'truename', 'account', 'wx_openid', 'wx_unionid'], 'string', 'max' => 50],
            [['login_password'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['mobile'],'match','pattern'=>'/^[1][358][0-9]{9}$/','message'=>'{attribute}格式错误。'],
            [['mobile'],'unique','message'=>'{attribute}已经存在。'],
            [['login_password'], 'string', 'min'=>6, 'max' => 30],
            [['qq'], 'string', 'max' => 15],
            [['email', 'reg_ip', 'last_login_ip'], 'string', 'max' => 30],
            [['token'], 'string', 'max' => 56],
            [['head_portrait', 'introduce'], 'string', 'max' => 255],
            [['name','mobile','login_password'], 'required'],
        ];
    }


    /**
     * Search Class控制输出字段
     * @return array
     */
    public function fieldsScenarios(){

        return [
            'list'=>['id','name','mobile','sex','truename','account'],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','mobile','login_password','qq','truename','account','email','head_portrait','birthday','sex','introduce',],//创建场景

        'update' => ['name','login_password','qq','truename','email','head_portrait','birthday','sex','introduce',],//修改场景

        'delete' => ['is_delete'],//删除场景
        ];
    }

}
