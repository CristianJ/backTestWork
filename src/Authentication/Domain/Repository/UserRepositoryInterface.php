<?php
namespace src\Authentication\Domain\Repository;


interface UserRepositoryInterface
{
    public function generateToken($username, $password);
}