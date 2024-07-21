<?php

namespace src\Books\Application\DTO;

class BookDTO
{
    public $id;
    public $title;
    public $authors;
    public $publicationYear;
    public $description;

    public function __construct($id, $title, $authors, $publicationYear, $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->authors = $authors;
        $this->publicationYear = $publicationYear;
        $this->description = $description;
    }
}