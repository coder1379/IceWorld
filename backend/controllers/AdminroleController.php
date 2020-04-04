<?php

namespace backend\controllers;

use Yii;
use common\services\adminrole\AdminRoleModel;
use common\services\adminrole\AdminRoleSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminroleController implements the CRUD actions for AdminRoleModel model.
 */
class AdminroleController extends AuthController
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
     * Lists all AdminRoleModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mainAuthJson'=>$this->adminMainRoleJson,
        ]);
    }

    /**
     * Displays a single AdminRoleModel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $menulist=$this->getAdminMenuList();
        $authData = $this->findModel($id);
        $authNameList = $this->getAuthNameList($menulist,$authData['auth_list']);

        return $this->render('view', [
            'model' =>$authData,
            'authNameList'=>$authNameList,
        ]);
    }

    private function getAdminMenuList(){
        $params = [':is_delete' => 0, ':status' => 1];
        $adminMenuRecord = Yii::$app->db->createCommand("select * from {{%admin_menu}} where is_delete=:is_delete and status=:status order by m_level asc,show_sort asc")->bindValues($params)->queryAll();
        $newList = [];
        if(!empty($adminMenuRecord)){
            foreach ($adminMenuRecord as $a){
                if($a['m_level']==1){
                    $a['children_list']=[];
                    $a['children_name']=[];
                    $newList[$a['id']]=$a;
                }else if($a['m_level']==2){
                    if(!empty($newList[$a['parent_id']])){
                        $newList[$a['parent_id']]['children_name'][]=$a['controller'];
                        $newList[$a['parent_id']]['children_list'][$a['controller']]=$a;
                    }
                }
            }
        }
        return $newList;
    }

    private function getAllGourpByParentAuthId(){
        $params = [':is_delete' => 0, ':status' => 1];
        $autolist=Yii::$app->db->createCommand("select * from {{%admin_auth}} where is_delete=:is_delete and status=:status order by parent_id asc,show_sort asc,id asc")->bindValues($params)->queryAll();

        $newlist=array();
        $parentlist=array();
        if(empty($autolist)!=true){
            foreach ($autolist as $l){
                if($l['type']==1){ //controller
                    $newlist['c_'.$l['id']]=array('own'=>$l,'action'=>array());
                }else if($l['type']==2){ //action
                    $parentlist['p_'.$l['id']]=$l['parent_id'];
                    if(empty($newlist['c_'.$l['parent_id']])!=true){
                        $newlist['c_'.$l['parent_id']]['action']['a_'.$l['id']]=array('own'=>$l,'node'=>array());
                    }
                }else if($l['type']==3){ //node
                    if(empty($parentlist['p_'.$l['parent_id']])!=true && empty($newlist['c_'.$parentlist['p_'.$l['parent_id']]]['action']['a_'.$l['parent_id']])!=true){
                        $newlist['c_'.$parentlist['p_'.$l['parent_id']]]['action']['a_'.$l['parent_id']]['node']=array('own'=>$l);
                    }
                }
            }
        }
        return $newlist;
    }

    /**
     * Creates a new AdminRoleModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminRoleModel();
        $authlist=$this->getAllGourpByParentAuthId();
        $menulist=$this->getAdminMenuList();
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
            $model->auth_list=$this->getAuthArray($authlist);
            $model->other_auth_list=$this->getOtherAuthString($model->auth_list);
            if($model->save()==true){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('create', [
                    'model' => $model,
                    'authlist'=>$authlist,
                    'menulist'=>$menulist,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'authlist'=>$authlist,
                'menulist'=>$menulist,
            ]);
        }
    }

    /**
     * Updates an existing AdminRoleModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $authlist=$this->getAllGourpByParentAuthId();
        $menulist=$this->getAdminMenuList();
        $model->scenario = 'update';//修改场景，控制字段安全
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post())==true) {
            //
            $model->auth_list=$this->getAuthArray($authlist);
            $model->other_auth_list=$this->getOtherAuthString($model->auth_list);
            if($model->save()==true){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                    'authlist'=>$authlist,
                    'menulist'=>$menulist,
                ]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
                'authlist'=>$authlist,
                'menulist'=>$menulist,
            ]);
        }

    }

    /**
     * 获取权限对于名称
     */
    public function getAuthNameList($menuList,$authData){
        $returnAuthNameList = [];
        $authLevel = Yii::$app->params['authLevel'];
        $params = [':is_delete' => 0, ':status' => 1];
        $autolist=Yii::$app->db->createCommand("select * from {{%admin_auth}} where is_delete=:is_delete and status=:status order by parent_id asc,show_sort asc,id asc")->bindValues($params)->queryAll();
        $autoNameList = [];
        $controlerAuthNameList = [];
        if(!empty($autolist)){
            foreach ($autolist as $a){
                if($a['type']==1){
                    $controlerAuthNameList[$a['id']]=$a['auth_flag'];
                    $autoNameList[$a['auth_flag']]=$a['name'];
                }else if($a['type']==2){
                    if(!empty($controlerAuthNameList[$a['parent_id']])){
                        $controlerName = $controlerAuthNameList[$a['parent_id']];
                        $autoNameList[$controlerName.'_'.$a['auth_flag'].'_']=$a['name'];
                    }
                }
            }
        }
        if(empty($menuList)!=true && empty($authData)!=true){
            $authJson  = json_decode($authData);
            foreach($menuList as $m){
                if(!empty($m['children_name'])){
                    $authArray = [];
                    $authArray['name']=$m['name'];
                    $authArray['controllers']=[];
                    foreach($authJson as $key=>$a){
                        if(in_array($key,$m['children_name'])){
                            $controllerArray=[];
                            $controllerArray['name'] =empty($m['children_list'][$key]['name'])!=true?$m['children_list'][$key]['name']:$autoNameList[$key];
                            $controllerArray['actions'] = [];
                            if($authLevel==1 ){
                                if((is_int($a)==true && $a==1) || empty($a)!=true){
                                    $authArray['controllers'][]=$controllerArray;
                                }
                            }else if($authLevel==2){
                                if(!empty($a) && is_int($a)!=true){
                                    foreach ($a as $akey=>$action){
                                        if(!empty($autoNameList[$key.'_'.$akey.'_'])){
                                            $controllerArray['actions'][] = $autoNameList[$key.'_'.$akey.'_'];
                                        }
                                    }
                                    $authArray['controllers'][]=$controllerArray;
                                }

                            }
                        }
                    }
                    $returnAuthNameList[]=$authArray;
                }
            }
        }
        return $returnAuthNameList;
    }

    /**
     * 获取其他权限列表
     * @param $mainAuthStr
     * @return string
     */
    public function getOtherAuthString($mainAuthStr){
        $otherAuthStr = '';
        $otherAuthArray = [];
        if(empty($mainAuthStr)!=true){
            $mainArray = json_decode($mainAuthStr);
            if(empty($mainArray)!=true){
                foreach ($mainArray as $controller=>$actions) {
                    $controllerName = $controller;
                    $actionArray=[];
                    if(empty($actions)!=true && is_numeric($actions)!=true){
                        foreach ($actions as $action=>$a){
                            $actionArray[] = $action;
                        }
                    }
                    $params = [':auth_flag' => $controllerName];
                    $controllerRecord = Yii::$app->db->createCommand("select id,other_auth_url from {{%admin_auth}} where auth_flag=:auth_flag and is_delete=0 and status=1 and type=1")->bindValues($params)->queryOne();
                    if(empty($controllerRecord)!=true){
                        if(empty($controllerRecord['other_auth_url'])!=true){
                            $otherAuthArray = array_merge($otherAuthArray,explode(',',$controllerRecord['other_auth_url']));
                        }

                        $actionRecord = Yii::$app->db->createCommand("select id,other_auth_url from {{%admin_auth}} where parent_id=:parent_id and is_delete=0 and status=1 and type=2")->bindValues([':parent_id' => $controllerRecord['id']])->queryAll();
                        if(empty($actionRecord)!=true){
                            foreach ($actionRecord as $act){
                                if(empty($act['other_auth_url'])!=true){
                                    $otherAuthArray = array_merge($otherAuthArray,explode(',',$act['other_auth_url']));
                                }

                            }
                        }
                    }
                }
            }
        }
        if(empty($otherAuthArray)!=true){
            $otherAuthStr = implode(',',array_unique($otherAuthArray));
        }

        return $otherAuthStr;
    }

    public function getAuthArray($authlist){

        $newrolelist='';
        if(empty($authlist)!=true){
            foreach ($authlist as $au){
                $controllername=$au['own']['auth_flag'];
                if(Yii::$app->params['authLevel']==2){
                    if(empty(Yii::$app->request->post('ctl_'.$controllername))!=true){
                        $newactionliststr='';
                        foreach (Yii::$app->request->post('ctl_'.$controllername) as $actnm){
                            if(empty(trim($actnm))!=true){
                                if($newactionliststr==''){
                                    $newactionliststr.='"'.$actnm.'":1';
                                }else{
                                    $newactionliststr.=',"'.$actnm.'":1';
                                }
                            }
                        }
                        if($newactionliststr!=''){
                            if($newrolelist==''){
                                $newrolelist.='"'.$controllername.'":{'.$newactionliststr.'}';
                            }else{
                                $newrolelist.=',"'.$controllername.'":{'.$newactionliststr.'}';
                            }
                        }

                    }

                }else if(Yii::$app->params['authLevel']==1){
                    if(empty(Yii::$app->request->post('ctl_'.$controllername.'_c'))!=true && Yii::$app->request->post('ctl_'.$controllername.'_c')==1){
                        if($newrolelist==''){
                            $newrolelist.='"'.$controllername.'":1';
                        }else{
                            $newrolelist.=',"'.$controllername.'":1';
                        }
                    }
                }

            }

            if($newrolelist!='' && json_decode('{'.$newrolelist.'}')!=false){
                $newrolelist='{'.$newrolelist.'}';
            }else{
                $newrolelist='';
            }
        }
        return $newrolelist;
    }

    /**
     * Deletes an existing AdminRoleModel model.
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
     * Finds the AdminRoleModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminRoleModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminRoleModel::findOne($id)) !== null) {
            return $model;
        } else {
            return null;
        }
    }
}
