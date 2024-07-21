<?php

namespace src\Authors\Infrastructure\Persistence\Mongo;

use Ramsey\Uuid\Uuid;
use src\Authors\Domain\Model\Author;
use src\Authors\Domain\Repository\AuthorRepositoryInterface;
use MongoDB\Client as MongoClient;
use MongoDB\Collection;

class MongoAuthorRepository implements AuthorRepositoryInterface
{
    private Collection $collection;

    public function __construct(MongoClient $client)
    {
        $this->collection = $client->selectCollection('backTest', 'authors');
    }

    public function save(Author $author)
    {
        $authorData = [
            'fullName' => $author->getFullName(),
            'birthDay' => $author->getBirthDay(),
            'bookId' => $author->getBookId(),
        ];

        if ($author->getId() === null) {
            $authorData['id'] = Uuid::uuid4()->toString();
            $this->collection->insertOne($authorData);
        } else {
            $this->collection->updateOne(
                ['id' => $author->getId()],
                ['$set' => $authorData]
            );
        }
    }

    public function findById($id): ?Author
    {
        $pipeline = array_merge(
            $this->getPipeline(),
            [
                [
                    '$match' => ['id' => $id]
                ]
            ]
        );

        $cursor = $this->collection->aggregate($pipeline);

        foreach ($cursor as $document) {
            return new Author(
                $document['fullName'],
                $document['birthDay'],
                $document['books'],
                $document['id']
            );
        }

        return null;
    }

    public function findAll(): array
    {
        $pipeline = $this->getPipeline();
        $authors = [];

        $cursor = $this->collection->aggregate($pipeline);

        foreach ($cursor as $document) {
            $authors[] = new Author(
                $document['fullName'],
                $document['birthDay'],
                $document['books'],
                $document['id']
            );
        }

        return $authors;
    }

    public function delete(Author $author)
    {
        $this->collection->deleteOne(['id' => $author->getId()]);
    }

    private function getPipeline(): array
    {
        return [
            [
                '$lookup' => [
                    'from' => 'books',
                    'localField' => 'bookId',
                    'foreignField' => 'id',
                    'as' => 'book_details'
                ]
            ],
            [
                '$unwind' => [
                    'path' => '$book_details',
                    'preserveNullAndEmptyArrays' => true
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'id' => '$id',
                        'fullName' => '$fullName',
                        'birthDay' => '$birthDay',
                    ],
                    'books' => [
                        '$push' => [
                            'title' => '$book_details.title',
                            'publicationYear' => '$book_details.publicationYear',
                            'description' => '$book_details.description',
                            'id' => '$book_details.id',
                        ]
                    ]
                ]
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'id' => '$_id.id',
                    'fullName' => '$_id.fullName',
                    'birthDay' => '$_id.birthDay',
                    'books' => '$books'
                ]
            ]
        ];
    }
}