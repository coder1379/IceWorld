<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;

/*由于接口业务对应前端传递搜索字段的场景太少所有目前不在生成前端动态搜索功能，使用时自行继承实现*/
/* @var $this yii\web\View */
/* @var $generator backend\giitemplate\apiCrud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use common\ComBase;
use yii\data\ActiveDataProvider;
use common\widgets\Pagination;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form of `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return [
            'search' =>[<?php foreach ($searchAttributes as $key => $value){ echo "'".$value."',"; } ?>]
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @param string $formName 表单名称
     *
     * @return ActiveDataProvider
     */
    public function search($query, $paginationParams, $formName)
    {
        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

        // add conditions that should always apply here


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>$paginationParams,
        ]);

        // 参数验证失败可以打开此代码进行严格控制将不返回任何数据
        /*$this->setScenario($scenario);
        $this->load($params, $formName);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }*/

        $searchScenarios = $this->scenarios()[$scenario];
        if(empty($searchScenarios)){
            throw new \Exception('Unknown scenario:'.$scenario);
        }

        $query->andWhere(['>','status',-1]);//必须字段直接在此处添加,注意不要在search场景内覆盖了
        $searchParams = ComBase::getReserveArray($params,$searchScenarios);
        $query->andFilterWhere($searchParams);

        return $dataProvider;
    }
}
