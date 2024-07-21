<?php

use yii\mongodb\Connection;

use src\Authors\Domain\Repository\AuthorRepositoryInterface;
use src\Authors\Infrastructure\Persistence\Mongo\MongoAuthorRepository;



return [
    'definitions' => [
        Connection::class => function () {
            return new Connection([
                'dsn' => getenv('MONGODB_DSN'),
            ]);
        },
        AuthorRepositoryInterface::class => [
            'class' => MongoAuthorRepository::class,
           
        ],
    ],
];
