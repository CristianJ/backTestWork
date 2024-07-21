<?php 
namespace src\Authentication\Application;

use src\Authentication\Application\DTO\LoginDTO;
use yii\base\Model;


class AuthForm extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'string', 'max' => 255],
           
        ];
    }

    public function loadFromDTO($username,$password)
    {
        $this->username = $username;
        $this->password = $password;
       
    }
}