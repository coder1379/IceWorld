<?php

namespace common\services\sourcechannel;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\ComBase;
use common\services\sourcechannel\SourceChannelModel;

/**
 * SourceChannelSearch represents the model behind the search form about `common\services\sourcechannel\SourceChannelModel`.
 */
class SourceChannelSearch extends SourceChannelModel
{
    
    public $add_time_search_start_val; // add_time时间过滤开始值

    public $add_time_search_end_val; // add_time时间过滤结束值

            
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'sponsor_id', 'status', 'type', 'province_id', 'city_id', 'area_id', 'add_time', 'update_time'], 'integer'],
            [['name', 'channel_code', 'img_url', 'address', 'liaison', 'phone', 'mobile', 'email', 'qq', 'weixin', 'weibo', 'remark', 'keywords', 'description', 'details'], 'safe'],
                            [['add_time_search_start_val','add_time_search_end_val'], 'string'],
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
        $query = SourceChannelModel::find();

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
            'app_id' => $this->app_id,
            'sponsor_id' => $this->sponsor_id,
            'status' => $this->status,
            'type' => $this->type,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'add_time' => $this->add_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'channel_code', $this->channel_code])
            ->andFilterWhere(['like', 'img_url', $this->img_url])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'liaison', $this->liaison])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'qq', $this->qq])
            ->andFilterWhere(['like', 'weixin', $this->weixin])
            ->andFilterWhere(['like', 'weibo', $this->weibo])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'details', $this->details]);

        
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
        $query->with('appRecord')->with('sponsorRecord')->with('provinceRecord')->with('cityRecord')->with('areaRecord');
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
