<?php 
namespace src\Authentication\Application\DTO;


class LoginDTO
{
    public $username;
    public $password;
   

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
       
    }
}