<?php
namespace src\Authors\Application\Service;
use yii\base\Model;
use src\Authors\Application\DTO\CreateAuthorDTO;

class AuthorForm extends Model
{
    public $name;
    public $birthday;
    public $book_id;

    public function rules()
    {
        return [
            [['name', 'birthday', 'book_id'], 'required'],
            ['name', 'string', 'max' => 255],
            ['birthday', 'date', 'format' => 'php:Y-m-d'],
            ['book_id', 'string', 'max' => 255],
        ];
    }

    public function loadFromDTO(CreateAuthorDTO $dto)
    {
        $this->name = $dto->name;
        $this->birthday = $dto->birthday;
        $this->book_id = $dto->bookId;
    }
}