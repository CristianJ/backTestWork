<?php
namespace src\Books\Domain\Repository;
use src\Books\Domain\Model\Book;


interface BookRepositoryInterface{
    public function save(Book $author);
    public function findById($id): ?Book;
    public function findAll(): array;
    public function delete(Book $product);
}