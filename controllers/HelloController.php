<?php
namespace app\controllers;

use yii\web\Response;
use yii\rest\Controller;

class HelloController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return  ['message' => 'Hello Cristian'];
    }
}