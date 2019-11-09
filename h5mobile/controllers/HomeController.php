<?php
namespace h5mobile\controllers;

use Yii;
use yii\web\Controller;
/**
 * Site controller
 */
class HomeController extends Controller
{
    /**
     * @inheritdoc
     */
	public $layout = 'main';
	public $enableCsrfValidation = false;
	
	
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
        ];
    }



    public function actionIndex()
    {
		return $this->render('index');
    }
    
    public function action404(){
        return $this->render('404');
    }

}
