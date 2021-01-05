<?php

namespace common\services\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\ComBase;
use common\services\admin\AdminLoginLogModel;

/**
 * AdminLoginLogSearch represents the model behind the search form about `common\services\admin\AdminLoginLogModel`.
 */
class AdminLoginLogSearch extends AdminLoginLogModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'admin_id', 'type', 'add_time', 'status'], 'integer'],
            [['ip', 'device_desc'], 'safe'],
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
        $query = AdminLoginLogModel::find();

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
            'admin_id' => $this->admin_id,
            'type' => $this->type,
            'add_time' => $this->add_time,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'device_desc', $this->device_desc]);

        $query->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE]);//自动加入删除过滤
        $query->with('adminRecord');
            $query->addOrderBy('id desc');
            
        //导出实际执行,自行打开扩展
        /*if($exportFileFlag===1){
            $outputObj = new OutputExcel();
            $header = ['标题1','标题2'];//导出标题
            $query->select(['id']);//控制导出字段
            $ext = $query->asArray()->all();//导出数据
            $outputObj->run('导出'.date('YmdHis',time()),$header,$ext);
        }*/

        return $dataProvider;
    }
}
