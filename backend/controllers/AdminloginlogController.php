<?php

namespace backend\controllers;

use Yii;
use common\services\admin\AdminLoginLogModel;
use common\services\admin\AdminLoginLogSearch;
use backend\controllers\AuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\ComBase;
use common\services\administrator\AdministratorModel;

/**
 * AdminloginlogController implements the CRUD actions for AdminLoginLogModel model.
 */
class AdminloginlogController extends AuthController
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
     * Lists all AdminLoginLogModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminLoginLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mainAuthJson'=>$this->adminMainRoleJson,
        ]);
    }

    /**
     * Displays a single AdminLoginLogModel model.
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
     * Creates a new AdminLoginLogModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminLoginLogModel();
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
     * Updates an existing AdminLoginLogModel model.
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
     * Deletes an existing AdminLoginLogModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $obj=$this->findModel($id);
        if(empty($obj)==true){
            return $this->getJsonString(ComBase::getParamsErrorReturnArray());
        }else{

            $deleteFlag = 0;
            if(isset($obj->status)){ //有状态自动自动走软删除设置为-1
                $obj->scenario = 'delete';//删除场景，控制字段安全
                $obj->status = ComBase::STATUS_COMMON_DELETE;
                $deleteFlag = $obj->update();
            }else{
                $deleteFlag = $obj->delete();
            }

            if($deleteFlag){
                return $this->getJsonString([],ComBase::CODE_RUN_SUCCESS,ComBase::MESSAGE_DELETE_SUCCESS);
            }else{
                return $this->getJsonString(ComBase::getServerBusyReturnArray());
            }
        }
    }

    /**
     * Finds the AdminLoginLogModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminLoginLogModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminLoginLogModel::findOne($id)) !== null) {
            return $model;
        } else {
           return null;
        }
    }
}
