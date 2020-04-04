<?php

namespace backend\controllers;

use Yii;
use common\services\adminauth\AdminAuthModel;
use common\services\adminauth\AdminAuthSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * AdminauthController implements the CRUD actions for AdminAuthModel model.
 */
class AdminauthController extends AuthController
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
     * Lists all AdminAuthModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminAuthSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mainAuthJson'=>$this->adminMainRoleJson,
        ]);
    }

    /**
     * Displays a single AdminAuthModel model.
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
     * Creates a new AdminAuthModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminAuthModel();
        $model->scenario = 'create';//创建场景，控制字段安全
        $model->loadDefaultValues();
        if ( $model->load(Yii::$app->request->post())==true) {
            //添加添加时间和添加的管理员代码
            $allAttributeLabels = $model->attributeLabels();
            if(!empty($allAttributeLabels['add_time']) && (empty($model->add_time) || $model->add_time == '0000-00-00 00:00:00' )){
                $model->add_time = date('Y-m-d H:i:s',time());
            }

            if(!empty($allAttributeLabels['add_admin_id']) && empty($model->add_admin_id)){
                $model->add_admin_id = $this->getAdminId();
            }
            if($model->save()==true){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('create', [
                'model' => $model,
                ]);
            }
        } else {
             return $this->render('create', [
             'model' => $model,
             ]);
        }
    }

    /**
     * Updates an existing AdminAuthModel model.
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
            if($model->save()==true){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('update', [
                'model' => $model,
                ]);
            }

        } else {
            return $this->render('update', [
            'model' => $model,
            ]);
        }

    }

    /**
     * Deletes an existing AdminAuthModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $obj=$this->findModel($id);
        if(empty($obj)==true){
            return $this->getJsonString([],10001,'参数错误!');
        }else{
            $obj->scenario = 'delete';//删除场景，控制字段安全
            $obj->is_delete=1;
            if($obj->update()==true){
                return $this->getJsonString([],200,'删除成功!');
            }else{
                return $this->getJsonString([],10001,'删除失败!');
            }
        }
    }

    /**
     * Finds the AdminAuthModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminAuthModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminAuthModel::findOne($id)) !== null) {
            return $model;
        } else {
           return null;
        }
    }
}
