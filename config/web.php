<?php

use yii\mongodb\Connection;
use src\Books\Application\Services\BookService;
use src\Authors\Application\Service\AuthorService;
use src\Books\Domain\Repository\BookRepositoryInterface;
use src\Authentication\Application\AuthenticationService;
use src\Authors\Domain\Repository\AuthorRepositoryInterface;
use src\Authentication\Domain\Repository\UserRepositoryInterface;
use MongoDB\Client as MongoClient;
use src\Books\Infrastructure\Persistence\Mongo\MongoBookRepository;
use src\Authors\Infrastructure\Persistence\Mongo\MongoAuthorRepository;
use src\Authentication\Infrastructure\Persistence\Mongo\MongoAuthRepository;
use yii\di\Container;

//Como lo indicado en el documento cambiar aqui para probar localmente a la ip y puerto en donde se quiere probar
$mongoDsn = 'mongodb://localhost:27017/backTest';

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

// Configurar el contenedor
$container = new Container();

// Configurar MongoDB Client
$container->set(MongoClient::class, function () use ($mongoDsn) {
    return new MongoClient($mongoDsn);
});

// Configurar repositorios y servicios
$container->set(AuthorRepositoryInterface::class, function ($container) {
    $client = $container->get(MongoClient::class);
    return new MongoAuthorRepository($client);
});

$container->set(AuthorService::class, function ($container) {
    return new AuthorService($container->get(AuthorRepositoryInterface::class));
});

$container->set(BookRepositoryInterface::class, function ($container) {
    $client = $container->get(MongoClient::class);
    return new MongoBookRepository($client);
});

$container->set(BookService::class, function ($container) {
    return new BookService($container->get(BookRepositoryInterface::class));
});

$container->set(UserRepositoryInterface::class, function ($container) {
    return new MongoAuthRepository($container->get(MongoClient::class));
});

$container->set(AuthenticationService::class, function ($container) {
    return new AuthenticationService($container->get(UserRepositoryInterface::class), 'secretkeypassword');
});

// Configuración principal de Yii
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'as jwtAuth' => [
            'class' => 'app\components\JwtAuthMiddleware'
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'mongodb' => [
            'class' => MongoClient::class,
            'dsn' => $mongoDsn,
        ],
        'request' => [
            'cookieValidationKey' => 'bKNHjbouLrbkSlEU-JtcfwaExUPDhHxV',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/app.log',
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'hello'],
                'POST author' => 'author/create',
                'PUT author/<id:[\w-]+>' => 'author/update',
                'GET author' => 'author/index',
                'GET author/<id:[\w-]+>' => 'author/view',
                'DELETE author/<id:[0-9a-fA-F-]{36}>' => 'author/delete',
                'POST login' => 'authentication/create',
                'POST book' => 'book/create',
                'PUT book/<id:[\w-]+>' => 'book/update',
                'GET book' => 'book/index',
                'GET book/<id:[\w-]+>' => 'book/view',
                'DELETE book/<id:[0-9a-fA-F-]{36}>' => 'book/delete',
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
    ],
    'params' => $params,
];

// Configuración para entornos de desarrollo
if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

// Asignar el contenedor a Yii
Yii::$container = $container;

return $config;