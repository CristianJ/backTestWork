<?php
namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use src\Books\Application\DTO\CreateBookDTO;
use src\Books\Application\DTO\UpdateBookDTO;
use src\Books\Application\Services\BookService;

class BookController extends Controller
{
    private $service;

    public function __construct($id, $module, BookService $service, $config = [])
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
        $dto = new CreateBookDTO(
            Yii::$app->request->post('title'),
            Yii::$app->request->post('authors'),
            Yii::$app->request->post('publication_year'),
            Yii::$app->request->post('description')
        );
        $response = $this->service->createBook($dto);
        return $this->asJson($response);
    }

    public function actionUpdate($id)
    {
        $dto = new UpdateBookDTO(
            $id,
            Yii::$app->request->post('title'),
            Yii::$app->request->post('authors'),
            Yii::$app->request->post('publication_year'),
            Yii::$app->request->post('description')
        );
        $response = $this->service->updateBook($dto);
        return $this->asJson($response);
    }

    public function actionIndex()
    {
        $response = $this->service->getAllBooks();
        return $this->asJson($response);
    }

    public function actionView($id)
    {
        $response = $this->service->getBookById($id);
        return $this->asJson($response);
    }

    public function actionDelete($id)
    {
        $response = $this->service->deleteBook($id);
        return $this->asJson($response);
    }

    public function asJson($response)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
}