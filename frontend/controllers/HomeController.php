<?php
namespace frontend\controllers;

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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRegister()
    {
        return $this->render('register');
    }

    public function action404()
    {
        return $this->render('404');
    }

}
