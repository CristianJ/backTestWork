<?php
namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use src\Authors\Application\DTO\CreateAuthorDTO;
use src\Authors\Application\DTO\UpdateAuthorDTO;
use src\Authors\Application\Service\AuthorService;


class AuthorController extends Controller
{
    private $service;

    public function __construct($id, $module, AuthorService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'jwtAuth' => [
                'class' => 'app\components\JwtAuthMiddleware',
            ],
        ];
    }
    public function actionCreate()
    {
       
        $dto = new CreateAuthorDTO(
            Yii::$app->request->post('name'),
            Yii::$app->request->post('birthday'),
            Yii::$app->request->post('book_id')
        );
        $response = $this->service->createAuthor($dto);
        return $this->asJson($response);
    }

    public function actionUpdate($id)
    {
        $dto = new UpdateAuthorDTO(
            $id,
            Yii::$app->request->post('name'),
            Yii::$app->request->post('birthday'),
            Yii::$app->request->post('book_id')
        );
        $response = $this->service->updateAuthor($dto);
        return $this->asJson($response);
    }

    public function actionIndex()
    {
        $response = $this->service->getAllAuthors();
        return $this->asJson($response);
    }

    public function actionView($id)
    {
        $response = $this->service->getAuthorById($id);
        return $this->asJson($response);
    }

    public function actionDelete($id)
    {
        $response = $this->service->deleteAuthor($id);
        return $this->asJson($response);
    }

    public function asJson($response)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
}