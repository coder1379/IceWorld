<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

$includeDelete = 0;

$classM = $generator->modelClass;
$pks = $classM::primaryKey();

$idKey = current($pks);

if(!empty($searchConditions)){
    $fieldList = explode(',',$searchConditions[0]);
    $newList = [];
    if(!empty($fieldList)){
        foreach ($fieldList as $f){
            $names = explode('=>',$f);

            if(trim($names[0])=="'status'"){
                $includeDelete = 1;
                //$newList[] = "\n" . str_repeat(' ', 12)."'is_delete' => 0";
            }

            $newList[] = $f;


        }
        $searchConditions[0] = implode(',', $newList);
    }
}

$timeSearchList = [];
$timeSearchRulesList = [];
$labelList =  $generator->getTableSchema();
$oldLabels=[];
foreach($labelList->columns as $nx){
    $oldLabels[$nx->name]=$nx->comment;
}


foreach ($generator->getColumnNames() as $attribute) {

    $commontStr = $oldLabels[$attribute];

    $lableArr = explode('=+=',$commontStr);
    $jsonV=false;
    if(count($lableArr)>1){
        $jsonV=json_decode($lableArr[1],true);
    }

    if(empty($jsonV)!=true){
        if($jsonV["TimeSearch"]==1){
            $timeSearchList[] = $attribute;
        }
    }
}

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\ComBase;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    <?php
    if(!empty($timeSearchList)){
        foreach ($timeSearchList as $tSearch){
            $timeSearchRulesList[] = "'".$tSearch.'_search_start_val'."'";
            $timeSearchRulesList[] = "'".$tSearch.'_search_end_val'."'";
            ?>

    public $<?php echo $tSearch.'_search_start_val'; ?>; // <?php echo $tSearch; ?>时间过滤开始值

    public $<?php echo $tSearch.'_search_end_val'; ?>; // <?php echo $tSearch; ?>时间过滤结束值

            <?php

        }
    }

    ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
            <?php
            if(!empty($timeSearchRulesList)){
                ?>
                [[<?php echo implode(',',$timeSearchRulesList) ?>], 'string'],
            <?php
            } ?>
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
        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

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
        <?= implode("\n        ", $searchConditions) ?>

        <?php
        if(!empty($timeSearchList)){
            foreach ($timeSearchList as $tSearch){
                ?>

        if(!empty(trim($this-><?php echo $tSearch.'_search_start_val'; ?>))){
                $searchStartTime = trim($this-><?php echo $tSearch.'_search_start_val'; ?>);
                if(strlen($searchStartTime)==10){
                    $searchStartTime = strtotime($searchStartTime.' 00:00:00');
                }else{
                    $searchStartTime = strtotime($searchStartTime);
                }
            $query->andFilterWhere(['>=','<?php echo $tSearch; ?>',$searchStartTime]);
        }

        if(!empty(trim($this-><?php echo $tSearch.'_search_end_val'; ?>))){
                $searchEndTime = trim($this-><?php echo $tSearch.'_search_end_val'; ?>);
                if(strlen($searchEndTime)==10){
                $searchEndTime = strtotime($searchEndTime.' 23:59:59');
                }else{
                $searchEndTime = strtotime($searchEndTime);
                }
            $query->andFilterWhere(['<=','<?php echo $tSearch; ?>',$searchEndTime]);
        }
<?php
            }
        }
        ?>

        <?php
        if($includeDelete==1){
            echo "\$query->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE]);//自动加入删除过滤";
        }
        ?>

        <?php
        //////获取并设置备注里面的ralation 自动生成
        $labelList =  $generator->getTableSchema();
        $withArr=[];
        foreach($labelList->columns as $nx){
            $lableArr = explode('=+=',$nx->comment);
            $jsonV=false;
            if(count($lableArr)>1){
                $jsonV=json_decode($lableArr[1],true);
            }
            if($jsonV["type"]=="db"){
                $withArr[]=$jsonV["name"];
            }
        }

        if(empty($withArr)!=true){
            $withStr="";
            foreach ($withArr as $w){
                $withStr.= "->with('".$w."')";
            }
            echo "\$query".$withStr.";";
        }
        ?>

        <?php
        if(!empty($idKey)) {
            ?>
    $query->addOrderBy('<?php echo $idKey; ?> desc');
            <?php
        }
        ?>

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
