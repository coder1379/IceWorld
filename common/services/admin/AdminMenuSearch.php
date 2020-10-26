<?php

namespace common\services\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdminMenuSearch represents the model behind the search form about `common\services\adminmenu\AdminMenuModel`.
 */
class AdminMenuSearch extends AdminMenuModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'parent_id', 'm_level', 'add_admin_id', 'show_sort', 'is_delete'], 'integer'],
            [['name', 'controller', 'c_action', 'icon', 'add_time'], 'safe'],
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
        $query = AdminMenuModel::find();

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
            'parent_id' => $this->parent_id,
            'm_level' => $this->m_level,
            'add_admin_id' => $this->add_admin_id,
            'add_time' => $this->add_time,
            'show_sort' => $this->show_sort,
            'is_delete' => 0,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'controller', $this->controller])
            ->andFilterWhere(['like', 'c_action', $this->c_action])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        $query->with('parentMenuRecord')->with('addAdminRecord');

        $query->addOrderBy('id desc');

        return $dataProvider;
    }
}
