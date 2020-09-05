<?php

namespace common\services\systemconfig;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\services\systemconfig\SystemConfigModel;

/**
 * SystemConfigSearch represents the model behind the search form about `common\services\systemconfig\SystemConfigModel`.
 */
class SystemConfigSearch extends SystemConfigModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'add_time', 'update_time'], 'integer'],
            [['name', 'c_val', 'desc'], 'safe'],
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
        $query = SystemConfigModel::find();

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
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'c_val', $this->c_val])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        $query->addOrderBy('id desc');
        
        return $dataProvider;
    }
}
