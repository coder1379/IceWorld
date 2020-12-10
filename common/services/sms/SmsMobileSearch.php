<?php

namespace common\services\sms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\ComBase;
use common\services\sms\SmsMobileModel;

/**
 * SmsMobileSearch represents the model behind the search form about `common\services\sms\SmsMobileModel`.
 */
class SmsMobileSearch extends SmsMobileModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'user_id', 'area_num', 'send_time', 'send_num', 'type', 'send_type', 'sms_type', 'add_time', 'status'], 'integer'],
            [['name', 'mobile', 'other_mobiles', 'content', 'params_json', 'template', 'feedback', 'remark'], 'safe'],
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
        $query = SmsMobileModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'user_id' => $this->user_id,
            'area_num' => $this->area_num,
            'send_time' => $this->send_time,
            'send_num' => $this->send_num,
            'type' => $this->type,
            'send_type' => $this->send_type,
            'sms_type' => $this->sms_type,
            'add_time' => $this->add_time,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'other_mobiles', $this->other_mobiles])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'params_json', $this->params_json])
            ->andFilterWhere(['like', 'template', $this->template])
            ->andFilterWhere(['like', 'feedback', $this->feedback])
            ->andFilterWhere(['like', 'remark', $this->remark]);


        $query->andWhere(['>','status',ComBase::DB_IS_DELETE_VAL]);//自动加入删除过滤

        $query->with('userRecord');
        
            $query->addOrderBy('id desc');

            
        return $dataProvider;
    }
}
