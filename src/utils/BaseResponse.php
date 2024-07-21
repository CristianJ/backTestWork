<?php
namespace src\utils;



class BaseResponse
{
    public $success;
    public $message;
    public $data;

    public function __construct($success, $message, $data = null)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }
}

