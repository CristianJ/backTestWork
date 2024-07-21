<?php 
namespace src\Books\Infrastructure\Persistence\Mongo;



use MongoDB\Client;
use Ramsey\Uuid\Uuid;
use src\Books\Domain\Model\Book;
use src\Books\Domain\Repository\BookRepositoryInterface;


class MongoBookRepository implements BookRepositoryInterface
{
    private $collection;

    public function __construct(Client $client)
    {
        $this->collection = $client->selectCollection('backTest', 'books');
    }

    public function save(Book $book)
    {
        $bookData = [
            'title' => $book->getTitle(),
            'authors' => $book->getAuthors(),
            'publicationYear' => $book->getPublicationYear(),
            'description' => $book->getDescription(),
        ];

        if ($book->getId() === null) {
            $bookData['id'] = Uuid::uuid4()->toString();
            $this->collection->insertOne($bookData);
        } else {
            $this->collection->updateOne(
                ['id' => $book->getId()],
                ['$set' => $bookData]
            );
        }
    }

    public function findById($id): ?Book
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
            return new Book(
                $document['title'],
                $document['authors'],
                $document['publicationYear'],
                $document['description'],
                $document['id']
            );
        }

       
        return null;
    }

    public function findAll(): array
    {
        $pipeline = $this->getPipeline();
        $books = [];
        $cursor = $this->collection->aggregate($pipeline);

        foreach ($cursor as $document) {
            $books[] = new Book(
                $document['title'],
                $document['authors'],
                $document['publicationYear'],
                $document['description'],
                $document['id']
            );
        }
        return $books;
    }

    public function delete(Book $book)
    {
        $this->collection->deleteOne(['id' => $book->getId()]);
    }

    private function getPipeline(): array
    {
        return [
            [
                '$lookup' => [
                    'from' => 'authors',
                    'localField' => 'authors',
                    'foreignField' => 'id',
                    'as' => 'author_details'
                ]
            ],
            [
                '$unwind' => [
                    'path' => '$author_details',
                    'preserveNullAndEmptyArrays' => true
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'id' => '$id',
                        'title' => '$title',
                        'publicationYear' => '$publicationYear',
                        'description' => '$description'
                    ],
                    'authors' => ['$push' => '$author_details']
                ]
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'id' => '$_id.id',
                    'title' => '$_id.title',
                    'publicationYear' => '$_id.publicationYear',
                    'description' => '$_id.description',
                    'authors' => '$authors'
                ]
            ]
        ];
    }
}