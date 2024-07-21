<?php
namespace src\Books\Application\Services;

use yii\base\Model;
use src\Books\Application\DTO\CreateBookDTO;

class BookForm extends Model
{
    public $title;
    public $authors;
    public $publicationYear;
    public $description;

    public function rules()
    {
        return [
            [['title', 'authors', 'publicationYear'], 'required'],
            ['title', 'string', 'max' => 255],
            ['authors', 'each', 'rule' => ['string']],
            ['publicationYear', 'integer'],
            ['description', 'string'],
        ];
    }

    public function loadFromDTO(CreateBookDTO $dto)
    {
        $this->title = $dto->title;
        $this->authors = $dto->authors;
        $this->publicationYear = $dto->publicationYear;
        $this->description = $dto->description;
    }
}