<?php

namespace src\Authors\Domain\Model;

class Author
{
    private $id;
    private $fullName;
    private $birthDay;
    private $bookId;

    public function __construct($fullName, $birthDay, $bookId, $id = null)
    {
        $this->fullName = $fullName;
        $this->birthDay = $birthDay;
        $this->bookId = $bookId;
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

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    public function getBirthDay()
    {
        return $this->birthDay;
    }

    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;
    }

    public function getBookId()
    {
        return $this->bookId;
    }

    public function setBookId($bookId)
    {
        $this->bookId = $bookId;
    }

    
}