<?php

namespace common\services\mobilesms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\services\mobilesms\MobileSmsModel;

/**
 * MobileSmsSearch represents the model behind the search form about `common\services\mobilesms\MobileSmsModel`.
 */
class MobileSmsSearch extends MobileSmsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'user_id', 'status', 'send_number', 'type', 'send_type', 'sms_type', 'is_delete'], 'integer'],
            [['access_ip', 'mobile', 'contents', 'params_json', 'add_time', 'send_time', 'template', 'feedback', 'remark'], 'safe'],
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
        $query = MobileSmsModel::find();

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
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'add_time' => $this->add_time,
            'send_time' => $this->send_time,
            'send_number' => $this->send_number,
            'type' => $this->type,
            'send_type' => $this->send_type,
            'sms_type' => $this->sms_type,
            'is_delete' => 0,
        ]);

        $query->andFilterWhere(['like', 'access_ip', $this->access_ip])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'contents', $this->contents])
            ->andFilterWhere(['like', 'params_json', $this->params_json])
            ->andFilterWhere(['like', 'template', $this->template])
            ->andFilterWhere(['like', 'feedback', $this->feedback])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        $query->with('userRecord');

        $query->addOrderBy('id desc');

        return $dataProvider;
    }
}
