<?php

use backend\giitemplate\apiModel\Generator;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator backend\giitemplate\apiModel\Generator */

echo $form->field($generator, 'tableName')->textInput(['table_prefix' => $generator->getTablePrefix()]);
echo $form->field($generator, 'modelClass')->textInput(['value'=>empty($generator->modelClass)?'XxxApiModel':$generator->modelClass]);
//echo $form->field($generator, 'standardizeCapitals')->checkbox();
//echo $form->field($generator, 'singularize')->checkbox();
echo $form->field($generator, 'ns')->textInput(['value'=>empty($generator->ns)?'common\services\xxx':$generator->ns]);
echo $form->field($generator, 'baseClass')->textInput(['value'=>empty($generator->baseClass)?'common\services\xxx\XxxModel':$generator->baseClass]);
echo $form->field($generator, 'db');
echo $form->field($generator, 'useTablePrefix')->checkbox();
echo $form->field($generator, 'generateRelations')->dropDownList([
    Generator::RELATIONS_NONE => 'No relations',
    Generator::RELATIONS_ALL => 'All relations',
    Generator::RELATIONS_ALL_INVERSE => 'All relations with inverse',
]);
echo $form->field($generator, 'generateRelationsFromCurrentSchema')->checkbox();
echo $form->field($generator, 'generateLabelsFromComments')->checkbox();
//echo $form->field($generator, 'generateQuery')->checkbox();
//echo $form->field($generator, 'queryNs');
//echo $form->field($generator, 'queryClass');
//echo $form->field($generator, 'queryBaseClass');
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');
echo $form->field($generator, 'useSchemaName')->checkbox();
