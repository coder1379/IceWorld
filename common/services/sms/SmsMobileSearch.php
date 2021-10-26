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
    
    public $send_time_search_start_val; // send_time时间过滤开始值

    public $send_time_search_end_val; // send_time时间过滤结束值

            
    public $add_time_search_start_val; // add_time时间过滤开始值

    public $add_time_search_end_val; // add_time时间过滤结束值

            
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'user_id', 'area_code', 'send_time', 'send_num', 'type', 'send_type', 'sms_type', 'add_time', 'status'], 'integer'],
            [['name', 'mobile', 'other_mobiles', 'content', 'params_json', 'template', 'feedback', 'remark'], 'safe'],
                            [['send_time_search_start_val','send_time_search_end_val','add_time_search_start_val','add_time_search_end_val'], 'string'],
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

        //判断是否为导出
        $dpArr = ['query' => $query,];
        $exportFileFlag = $params['export_file_flag']??0;
        $exportFileFlag = intval($exportFileFlag);
        if($exportFileFlag === 1){
            $query->limit(10000);
            $dpArr['pagination'] = false;
        }

        $dataProvider = new ActiveDataProvider($dpArr);

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
            'area_code' => $this->area_code,
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

        
        if(!empty(trim($this->send_time_search_start_val))){
                $searchStartTime = trim($this->send_time_search_start_val);
                if(strlen($searchStartTime)==10){
                    $searchStartTime = strtotime($searchStartTime.' 00:00:00');
                }else{
                    $searchStartTime = strtotime($searchStartTime);
                }
            $query->andFilterWhere(['>=','send_time',$searchStartTime]);
        }

        if(!empty(trim($this->send_time_search_end_val))){
                $searchEndTime = trim($this->send_time_search_end_val);
                if(strlen($searchEndTime)==10){
                $searchEndTime = strtotime($searchEndTime.' 23:59:59');
                }else{
                $searchEndTime = strtotime($searchEndTime);
                }
            $query->andFilterWhere(['<=','send_time',$searchEndTime]);
        }

        if(!empty(trim($this->add_time_search_start_val))){
                $searchStartTime = trim($this->add_time_search_start_val);
                if(strlen($searchStartTime)==10){
                    $searchStartTime = strtotime($searchStartTime.' 00:00:00');
                }else{
                    $searchStartTime = strtotime($searchStartTime);
                }
            $query->andFilterWhere(['>=','add_time',$searchStartTime]);
        }

        if(!empty(trim($this->add_time_search_end_val))){
                $searchEndTime = trim($this->add_time_search_end_val);
                if(strlen($searchEndTime)==10){
                $searchEndTime = strtotime($searchEndTime.' 23:59:59');
                }else{
                $searchEndTime = strtotime($searchEndTime);
                }
            $query->andFilterWhere(['<=','add_time',$searchEndTime]);
        }

        $query->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE]);//自动加入删除过滤
        $query->with('userRecord');
            $query->addOrderBy('id desc');
            
        //导出实际执行,自行打开扩展
        /*if($exportFileFlag===1){
            $outputObj = new OutputExcel();
            $header = ['标题1','标题2'];//导出标题
            $query->select(['id']);//控制导出字段
            $ext = $query->asArray()->all();//导出数据
            $outputObj->run('导出'.date('YmdHis',time()),$header,$ext);
        }*/

        $dataProvider->setSort(false); // 默认取消所有排序

        return $dataProvider;
    }
}
