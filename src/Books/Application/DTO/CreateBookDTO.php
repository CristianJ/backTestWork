<?php

namespace src\Books\Application\DTO;

class CreateBookDTO
{
    public $title;
    public $authors;
    public $publicationYear;
    public $description;

    public function __construct($title, $authors, $publicationYear, $description)
    {
        $this->title = $title;
        $this->authors = $authors;
        $this->publicationYear = $publicationYear;
        $this->description = $description;
    }
}