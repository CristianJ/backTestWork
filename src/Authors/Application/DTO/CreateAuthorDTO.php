<?php 
namespace src\Authors\Application\DTO;

class CreateAuthorDTO
{
    public $name;
    public $birthday;
    public $bookId;

    public function __construct($name, $birthday, $bookId)
    {
        $this->name = $name;
        $this->birthday = $birthday;
        $this->bookId = $bookId;
    }
}