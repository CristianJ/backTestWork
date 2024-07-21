<?php 
namespace src\Authors\Application\DTO;


class UpdateAuthorDTO
{
    public $id;
    public $name;
    public $birthday;
    public $book_id;

    public function __construct($id, $name = null, $birthday = null, $book_id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->birthday = $birthday;
        $this->book_id = $book_id;
    }
}