<?php

namespace common\services\adminrole;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdminRoleSearch represents the model behind the search form about `common\services\adminrole\AdminRoleModel`.
 */
class AdminRoleSearch extends AdminRoleModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'add_admin_id', 'show_sort', 'is_delete'], 'integer'],
            [['name', 'auth_list', 'other_auth_list', 'add_time'], 'safe'],
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
        $query = AdminRoleModel::find();

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
            'type' => $this->type,
            'status' => $this->status,
            'add_admin_id' => $this->add_admin_id,
            'add_time' => $this->add_time,
            'show_sort' => $this->show_sort,
            'is_delete' => 0,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'auth_list', $this->auth_list])
            ->andFilterWhere(['like', 'other_auth_list', $this->other_auth_list]);

        $query->with('addAdminRecord');

        $query->addOrderBy('id desc');

        return $dataProvider;
    }
}
