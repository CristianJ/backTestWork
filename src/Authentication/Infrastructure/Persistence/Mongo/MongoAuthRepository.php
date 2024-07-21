<?php
namespace src\Authentication\Infrastructure\Persistence\Mongo;

use MongoDB\Client;
use app\models\User;
use yii\mongodb\Query;
use src\Authentication\Domain\Repository\UserRepositoryInterface;

class MongoAuthRepository implements UserRepositoryInterface
{

    private $collection;

    public function __construct(Client $client)
    {
        $this->collection = $client->selectDatabase('backTest')->selectCollection('user');
    }

    public function generateToken($username, $password)
    {
        $filter = [
            'username' => $username,
            'password' => $password,
        ];

        return $this->collection->findOne($filter);
    }

}