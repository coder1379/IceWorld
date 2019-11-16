<?php

namespace common\services\site;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\services\site\SiteModel;

/**
 * SiteSearch represents the model behind the search form about `common\services\site\SiteModel`.
 */
class SiteSearch extends SiteModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'user_id', 'type', 'is_delete'], 'integer'],
            [['name', 'introduce', 'seo_title', 'seo_keywords', 'seo_description', 'telphone', 'mobile', 'qq', 'email', 'img_url', 'cover', 'content', 'about_us', 'add_time'], 'safe'],
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
        $query = SiteModel::find();

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
            'status' => $this->status,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'is_delete' => 0,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'introduce', $this->introduce])
            ->andFilterWhere(['like', 'seo_title', $this->seo_title])
            ->andFilterWhere(['like', 'seo_keywords', $this->seo_keywords])
            ->andFilterWhere(['like', 'seo_description', $this->seo_description])
            ->andFilterWhere(['like', 'telphone', $this->telphone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'qq', $this->qq])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'img_url', $this->img_url])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'about_us', $this->about_us]);

        $query->with('userRecord');

        $query->addOrderBy('id desc');

        return $dataProvider;
    }
}
