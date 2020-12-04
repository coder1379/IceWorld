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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
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
        <?= implode("\n        ", $searchConditions) ?>


        <?php
        if($includeDelete==1){
            echo "\$query->andWhere(['>','status',ComBase::DB_IS_DELETE_VAL]);//自动加入删除过滤";
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

        return $dataProvider;
    }
}
