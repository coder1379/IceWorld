<?php

namespace common\services\administrator;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdministratorSearch represents the model behind the search form about `common\services\administrator\AdministratorModel`.
 */
class AdministratorSearch extends AdministratorModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role_id', 'group_id', 'area_id', 'add_admin_id', 'show_sort', 'type', 'status', 'online', 'is_delete', 'is_admin'], 'integer'],
            [['login_username', 'avatar', 'realname', 'nickname', 'mobile', 'remark', 'email', 'qq', 'wechat', 'company', 'login_password', 'token', 'add_time', 'last_login_time', 'last_login_ip'], 'safe'],
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
        $query = AdministratorModel::find();

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
            'role_id' => $this->role_id,
            'group_id' => $this->group_id,
            'area_id' => $this->area_id,
            'add_admin_id' => $this->add_admin_id,
            'add_time' => $this->add_time,
            'show_sort' => $this->show_sort,
            'type' => $this->type,
            'status' => $this->status,
            'last_login_time' => $this->last_login_time,
            'online' => $this->online,
            'is_delete' => 0,
            'is_admin' => $this->is_admin,
        ]);

        $query->andFilterWhere(['like', 'login_username', $this->login_username])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'qq', $this->qq])
            ->andFilterWhere(['like', 'wechat', $this->wechat])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'login_password', $this->login_password])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'last_login_ip', $this->last_login_ip]);

        $query->with('adminRoleRecord')->with('adminGroupRecord')->with('areaRecord')->with('addAdminRecord');

        $query->addOrderBy('id desc');

        return $dataProvider;
    }
}
