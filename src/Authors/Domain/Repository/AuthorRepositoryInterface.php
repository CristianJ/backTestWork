<?php
namespace src\Authors\Domain\Repository;


use src\Authors\Domain\Model\Author;



interface AuthorRepositoryInterface{
    public function save(Author $author);
    public function findById($id): ?Author;
    public function findAll(): array;
    public function delete(Author $product);
}