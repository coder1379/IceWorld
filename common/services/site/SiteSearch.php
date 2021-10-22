<?php

namespace common\services\site;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\ComBase;
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
            [['id', 'add_time', 'status', 'type'], 'integer'],
            [['name', 'introduce', 'seo_title', 'seo_keywords', 'seo_description', 'telphone', 'mobile', 'qq', 'email', 'img_url', 'cover', 'content', 'about_us'], 'safe'],
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
            'add_time' => $this->add_time,
            'status' => $this->status,
            'type' => $this->type,
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

        $query->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE]);//自动加入删除过滤
        
            $query->addOrderBy('id desc');
            
        //导出实际执行,自行打开扩展
        /*if($exportFileFlag===1){
            $outputObj = new OutputExcel();
            $header = ['标题1','标题2'];//导出标题
            $query->select(['id']);//控制导出字段
            $ext = $query->asArray()->all();//导出数据
            $outputObj->run('导出'.date('YmdHis',time()),$header,$ext);
        }*/

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'attributes' => [
                'id' => [
                    'asc' => [
                        'id' => SORT_ASC,
                    ],
                    'desc' => [
                        'id' => SORT_DESC,
                    ],
                    'default' => SORT_ASC,
                ],
                'name' => [
                    'asc' => [
                        'name' => SORT_ASC
                    ],
                    'desc' => [
                        'name' => SORT_DESC,
                    ],
                    'default' => SORT_ASC,
                ]
            ]
        ]); // 默认取消所有排序

        return $dataProvider;
    }
}
