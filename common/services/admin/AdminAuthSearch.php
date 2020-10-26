<?php

namespace common\services\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdminAuthSearch represents the model behind the search form about `common\services\admin\AdminAuthModel`.
 */
class AdminAuthSearch extends AdminAuthModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'type', 'status', 'add_admin_id', 'show_sort', 'is_delete'], 'integer'],
            [['name', 'auth_flag', 'other_auth_url', 'add_time'], 'safe'],
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
        $query = AdminAuthModel::find();

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
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'status' => $this->status,
            'add_admin_id' => $this->add_admin_id,
            'add_time' => $this->add_time,
            'show_sort' => $this->show_sort,
            'is_delete' => 0,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'auth_flag', $this->auth_flag])
            ->andFilterWhere(['like', 'other_auth_url', $this->other_auth_url]);

        $query->with('parentAdminAuthRecord')->with('addAdminRecord');

        $query->addOrderBy('id desc');

        return $dataProvider;
    }
}
