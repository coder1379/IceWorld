<?php
use backend\giitemplate\apiCrud\Generator;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

echo $form->field($generator, 'modelClass')->textInput(['value'=>empty($generator->modelClass)?'common\services\xxx\XxxApiModel':$generator->modelClass]);
echo $form->field($generator, 'searchModelClass')->textInput(['value'=>empty($generator->searchModelClass)?'common\services\xxx\XxxApiSearch':$generator->searchModelClass]);
echo $form->field($generator, 'logic')->textInput(['value'=>empty($generator->logic)?'common\services\xxx\XxxLogic':$generator->logic]);
echo $form->field($generator, 'controllerClass')->textInput(['value'=>empty($generator->controllerClass)?'api\controllers\XxxController':$generator->controllerClass]);
//echo $form->field($generator, 'viewPath')->textInput(['value'=>empty($generator->viewPath)?'@api\views\xxx':$generator->viewPath]);
echo $form->field($generator, 'baseControllerClass')->textInput(['value'=>empty($generator->baseControllerClass)?'common\controllers\ApiCommonAuthContoller':$generator->baseControllerClass]);
/*echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);*/
//echo $form->field($generator, 'enableI18N')->checkbox();
//echo $form->field($generator, 'enablePjax')->checkbox();
//echo $form->field($generator, 'messageCategory');
