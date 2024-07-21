<?php 
namespace src\Authentication\Application;


use Firebase\JWT\JWT;
use src\utils\Constants;
use src\utils\BaseResponse;
use src\Authentication\Application\AuthForm;
use src\Authentication\Infrastructure\Persistence\Mongo\MongoAuthRepository;


class AuthenticationService
{
    private $userRepository;
    private $jwtSecretKey;

    public function __construct(MongoAuthRepository $userRepository, $jwtSecretKey)
    {
        $this->userRepository = $userRepository;
        $this->jwtSecretKey = $jwtSecretKey;
    }

    public function generateToken($username, $password)
    {
        $authForm = new AuthForm();
        $authForm->loadFromDTO($username,$password);
        if (!$authForm->validate()) {
            return new BaseResponse(false, null, $authForm->errors);
        }

        $user = $this->userRepository->generateToken($username, $password);

        if (!$user) {
            return new BaseResponse(false, null, Constants::USER_AND_PASSWORD_NOT_FOUND);
            
        }

        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;
        $payload = array(
            'id' => $user->id,
            'username' => $user->username,
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        );

        $token =  JWT::encode($payload, $this->jwtSecretKey,'HS256');
        return new BaseResponse(true, $token);
    }
}