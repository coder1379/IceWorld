<?php

namespace common\services\site;

use Yii;
use common\services\user\UserModel;

/**
 * This is the model class for table "{{%site}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $introduce
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property string $telphone
 * @property string $mobile
 * @property string $qq
 * @property string $email
 * @property string $img_url
 * @property string $cover
 * @property string $content
 * @property string $about_us
 * @property string $add_time
 * @property integer $status
 * @property integer $user_id
 * @property integer $type
 * @property integer $is_delete
 */
class SiteModel extends \yii\db\ActiveRecord
{

    /*
     * @配置信息写入
     */
    //对应字段:status,备注：状态
    public $statusPredefine=["1"=>"启用","2"=>"停用"];
    //对应字段:type,备注：类型
    public $typePredefine=["1"=>"常用","2"=>"特殊"];


    /*
    * @关系内容写入
    */
     //对应字段：user_id,用户
     public function getUserRecord()
     {
        return $this->hasOne(UserModel::className(), ['id' => 'user_id']);
     }

     //获取user_id,用户 的LIST
     public function getUserRecordList(){
            $array = UserModel::find()->select('id,name')->where(['is_delete'=>0])->orderBy("id desc")->limit(100)->asArray()->all();
            $newArr = [];

            if(empty($array)!=true){
                foreach($array as $v){
                $newArr[$v["id"]]=$v["name"];
                }
            }
            return $newArr;
      }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%site}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    
        ////////////字段验证规则
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
            [['img_url','content','user_id'], 'required'],
        ];
    }

    public function scenarios()
    {
        ///////模型使用场景
                return [
        'create' => ['name','introduce','seo_title','seo_keywords','seo_description','telphone','mobile','qq','email','img_url','cover','content','about_us','add_time','status','user_id','type',],//创建场景

        'update' => ['name','introduce','seo_title','seo_keywords','seo_description','telphone','mobile','qq','email','img_url','cover','content','about_us','add_time','status','user_id','type',],//修改场景

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
    }

    /**
     * @inheritdoc
     * @return SiteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SiteQuery(get_called_class());
    }
}
