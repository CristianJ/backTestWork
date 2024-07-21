<?php
namespace src\Books\Domain\Model;

class Book
{
    private $id;
    private $title;
    private $authors;
    private $publicationYear;
    private $description;

    public function __construct($title, $authors, $publicationYear, $description, $id = null)
    {
        $this->title = $title;
        $this->authors = $authors;
        $this->publicationYear = $publicationYear;
        $this->description = $description;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getAuthors()
    {
        return $this->authors;
    }

    public function setAuthors($authors)
    {
        $this->authors = $authors;
    }

    public function getPublicationYear()
    {
        return $this->publicationYear;
    }

    public function setPublicationYear($publicationYear)
    {
        $this->publicationYear = $publicationYear;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}