<?php

namespace app\components;

use Yii;
use app\models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\base\ActionFilter;
use src\utils\BaseResponse;

class JwtAuthMiddleware extends ActionFilter
{
    public function beforeAction($action)
    {
        $authHeader = Yii::$app->request->headers->get('Authorization');

        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);

            try {
               
                $decoded = JWT::decode($token, new Key(Yii::$app->params['jwtSecretKey'], 'HS256'));
              

                
                $user = User::findIdentity($decoded->id);

                if ($user) {
                   
                    Yii::$app->user->identity = $user;
                    Yii::$app->user->login($user); 
                    return parent::beforeAction($action);
                } else {
                    $this->handleUnauthorizedResponse('Acceso no autorizado. Usuario no encontrado.');
                    return false; 
                }

            } catch (\Exception $e) {
                $this->handleUnauthorizedResponse('Acceso no autorizado.', $e->getMessage());
                return false; 
            }
        } else {
            $this->handleUnauthorizedResponse('Acceso no autorizado. El token JWT no fue proporcionado.');
            return false;
        }
    }

   
    private function handleUnauthorizedResponse($message, $details = null)
    {
        Yii::$app->response->statusCode = 401; 
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = new BaseResponse(false, $message ." ".$details);
        Yii::$app->end();
    }
}