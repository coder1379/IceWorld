<?php

namespace backend\controllers;

use Yii;
use common\services\message\MobileSmsModel;
use common\services\message\MobileSmsSearch;
use backend\controllers\AuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\ComBase;
use common\services\user\UserModel;

/**
 * MobilesmsController implements the CRUD actions for MobileSmsModel model.
 */
class MobilesmsController extends AuthController
{
    /**
     * @inheritdoc
     */
    public $layout = 'main-iframe';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MobileSmsModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MobileSmsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mainAuthJson'=>$this->adminMainRoleJson,
        ]);
    }

    /**
     * Displays a single MobileSmsModel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MobileSmsModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MobileSmsModel();
        $model->scenario = 'create';//创建场景，控制字段安全
        $model->loadDefaultValues();
        if ( $model->load(Yii::$app->request->post())==true) {
            //添加添加时间和添加的管理员代码
            $allAttributeLabels = $model->attributeLabels();
            if(!empty($allAttributeLabels['add_time']) && empty($model->add_time)){
                $model->add_time = time();
            }

            if(!empty($allAttributeLabels['update_time']) && empty($model->update_time)){
                $model->update_time = time();
            }

            if(!empty($allAttributeLabels['add_admin_id']) && empty($model->add_admin_id)){
                $model->add_admin_id = $this->getAdminId();
            }
            if($model->save()==true){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MobileSmsModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';//修改场景，控制字段安全
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post())==true) {
            //维护修改时间如果存在字段
            $allAttributeLabels = $model->attributeLabels();
            if(!empty($allAttributeLabels['update_time'])){
                $model->update_time = time();
            }
            if($model->save()==true){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MobileSmsModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $obj=$this->findModel($id);
        if(empty($obj)==true){
            return ComBase::getReturnJson([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);
        }else{

            $deleteFlag = 0;
            if(isset($obj->status)){ //有状态自动自动走软删除设置为-1
                $obj->scenario = 'delete';//删除场景，控制字段安全
                $obj->status = ComBase::DB_IS_DELETE_VAL;
                $deleteFlag = $obj->update();
            }else{
                $deleteFlag = $obj->delete();
            }

            if($deleteFlag){
                return $this->getJsonString([],ComBase::CODE_RUN_SUCCESS,ComBase::MESSAGE_DELETE_SUCCESS);
            }else{
                return $this->getJsonString([],ComBase::CODE_SERVER_ERROR,ComBase::MESSAGE_SERVER_ERROR);
            }
        }
    }

    /**
     * Finds the MobileSmsModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MobileSmsModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MobileSmsModel::findOne($id)) !== null) {
            return $model;
        } else {
           return null;
        }
    }
}
