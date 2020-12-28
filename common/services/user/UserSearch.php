<?php

namespace common\services\user;

use common\lib\output\OutputExcel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\ComBase;
use common\services\user\UserModel;

/**
 * UserSearch represents the model behind the search form about `common\services\user\UserModel`.
 */
class UserSearch extends UserModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'type', 'level', 'sex', 'birthday', 'add_time'], 'integer'],
            [['name', 'mobile', 'username', 'login_password', 'realname', 'email', 'avatar', 'introduce', 'district', 'title', 'token'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserModel::find();

        // add conditions that should always apply here

        //判断是否为导出
        $dpArr = ['query' => $query,];
        $exportFileFlag = $params['export_file_flag']??0;
        $exportFileFlag = intval($exportFileFlag);
        if($exportFileFlag === 1){
            $query->limit(10000);
            $dpArr['pagination'] = false;
        }

        $dataProvider = new ActiveDataProvider($dpArr);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'type' => $this->type,
            'level' => $this->level,
            'sex' => $this->sex,
            'birthday' => $this->birthday,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'login_password', $this->login_password])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'introduce', $this->introduce])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'token', $this->token]);

        $query->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE]);//自动加入删除过滤
        
            $query->addOrderBy('id desc');

            //导出实际执行,自行打开扩展
            /*if($exportFileFlag===1){
                $outputObj = new OutputExcel();
                $header = ['标题1','标题2'];//导出标题
                $query->select(['id']);//控制导出字段
                $ext = $query->asArray()->all();//导出数据
                $outputObj->run('导出'.date('YmdHis',time()),$header,$ext);
            }*/
            
        return $dataProvider;
    }
}
