<?php 
namespace src\Authors\Application\DTO;

class AuthorDTO implements \JsonSerializable
{
    private $id;
    private $fullName;
    private $birthDay;
    private $bookId;

    public function __construct($id, $fullName, $birthDay, $bookId)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->birthDay = $birthDay;
        $this->bookId = $bookId;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->fullName,
            'birth_day' => $this->birthDay,
            'book_id' => $this->bookId,
        ];
    }
}