<?php

namespace common\services\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
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
            [['id', 'add_time', 'token_out_time', 'last_login_time', 'sex', 'inviter_user_id', 'add_admin_id', 'status', 'type', 'is_delete', 'user_id'], 'integer'],
            [['name', 'login_password', 'mobile', 'qq', 'truename', 'account', 'email', 'wx_openid', 'wx_unionid', 'reg_ip', 'last_login_ip', 'token', 'head_portrait', 'birthday', 'introduce'], 'safe'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'add_time' => $this->add_time,
            'token_out_time' => $this->token_out_time,
            'last_login_time' => $this->last_login_time,
            'birthday' => $this->birthday,
            'sex' => $this->sex,
            'inviter_user_id' => $this->inviter_user_id,
            'add_admin_id' => $this->add_admin_id,
            'status' => $this->status,
            'type' => $this->type,
            'is_delete' => 0,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'login_password', $this->login_password])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'qq', $this->qq])
            ->andFilterWhere(['like', 'truename', $this->truename])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'wx_openid', $this->wx_openid])
            ->andFilterWhere(['like', 'wx_unionid', $this->wx_unionid])
            ->andFilterWhere(['like', 'reg_ip', $this->reg_ip])
            ->andFilterWhere(['like', 'last_login_ip', $this->last_login_ip])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'head_portrait', $this->head_portrait])
            ->andFilterWhere(['like', 'introduce', $this->introduce]);

        $query->with('addAdminRecord');
        
            $query->addOrderBy('id desc');

            
        return $dataProvider;
    }
}
