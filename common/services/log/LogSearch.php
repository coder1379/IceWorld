<?php

namespace common\services\log;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\services\log\LogModel;

/**
 * LogSearch represents the model behind the search form about `common\services\log\LogModel`.
 */
class LogSearch extends LogModel
{
    public $log_time_start = '';
    public $log_time_end = '';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'level'], 'integer'],
            [['category', 'prefix', 'message'], 'safe'],
            [['log_time','log_time_start','log_time_end'], 'string'],
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
        $query = LogModel::find();

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
            'category' => $this->category,
            'level' => $this->level,
            //'log_time' => $this->log_time,
        ]);


        if(!empty(trim($this->log_time_start))){
            $searchStartTime = trim($this->log_time_start);
            if(strlen($searchStartTime)==10){
                $searchStartTime = strtotime($searchStartTime.' 00:00:00');
            }else{
                $searchStartTime = strtotime($searchStartTime);
            }
            $query->andFilterWhere(['>=','log_time',$searchStartTime]);
        }

        if(!empty(trim($this->log_time_end))){
            $searchEndTime = trim($this->log_time_end);
            if(strlen($searchEndTime)==10){
                $searchEndTime = strtotime($searchEndTime.' 23:59:59');
            }else{
                $searchEndTime = strtotime($searchEndTime);
            }
            $query->andFilterWhere(['<=','log_time',$searchEndTime]);
        }

        $query->andFilterWhere(['like', 'prefix', $this->prefix])
            ->andFilterWhere(['like', 'message', $this->message]);

        $query->addOrderBy('id desc');
        
        return $dataProvider;
    }
}
