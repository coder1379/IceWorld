<?php

namespace common\services\site;

use common\services\user\UserApiModel;
use Yii;
use common\services\user\UserModel;

class SiteApiModel extends \common\services\site\SiteModel
{

    //apiModel独有function 用于控制哪些字段输出到前端
    public function fieldsScenarios()
    {
        return [
            'list' => ['id','name','introduce','seo_title','seo_keywords','seo_description','telphone','mobile','qq','email','img_url','cover','content','about_us','add_time','status','user_id',],//列表

            'detail' => ['id','name','introduce','seo_title','seo_keywords','seo_description','telphone','mobile','qq','email','img_url','cover','content','about_us','add_time','status','user_id',],//详情
        ];
    }

    /**
     * 覆盖父类方法
     * @return \yii\db\ActiveQuery
     */
    public function getUserRecord()
    {
        return $this->hasOne(UserApiModel::class, ['id' => 'user_id']);
    }

    public function getInviterUserRecordList()
    {
        return $this->hasMany(UserModel::class, ['inviter_user_id' => 'user_id']);
    }

    public function getStatusStr(){
        return $this->statusPredefine[$this->status];
    }


    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function rules()
    {
        return [
            [['name'], 'required'],
            [['content', 'about_us'], 'string'],
            [['add_time'], 'safe'],
            [['status', 'user_id', 'type', 'is_delete'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['introduce', 'seo_title', 'seo_keywords', 'seo_description', 'img_url', 'cover'], 'string', 'max' => 255],
            [['telphone'], 'string', 'max' => 20],
            [['mobile'], 'string', 'max' => 11],
            [['qq'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 30],
        ];
    }

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    public function scenarios()
    {
        return [
            'create' => ['name','introduce','seo_title','seo_keywords','seo_description','telphone','mobile','qq','email','img_url','cover','content','about_us','add_time','status','user_id','type',],//创建场景

            'update' => ['name','introduce','seo_title','seo_keywords','seo_description','telphone','mobile','qq','email','img_url','cover','content','about_us','add_time','status','user_id','type',],//修改场景

            'delete' => ['is_delete'],//删除场景
        ];
    }


    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '网站名称',
            'introduce' => '网站简介',
            'seo_title' => 'SEO标题',
            'seo_keywords' => 'SEO关键字',
            'seo_description' => 'SEO描述',
            'telphone' => '联系电话',
            'mobile' => '手机号',
            'qq' => 'QQ',
            'email' => '邮箱',
            'img_url' => 'logo',
            'cover' => '封面',
            'content' => '详细介绍',
            'about_us' => '关于我们',
            'add_time' => '添加时间',
            'status' => '状态',
            'user_id' => '用户',
            'type' => '类型',
            'is_delete' => '是否删除',
            ];
    }*/

}
