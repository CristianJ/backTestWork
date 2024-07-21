<?php 
namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use src\Authentication\Application\AuthenticationService;


class AuthenticationController extends Controller
{
    private $authService;

    public function __construct($id, $module, AuthenticationService $authService, $config = [])
    {
        $this->authService = $authService;
        parent::__construct($id, $module, $config);
        
    }

    public function actionCreate()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $response = $this->authService->generateToken($username, $password);
        return $this->asJson($response);
    }

    public function asJson($response)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
}