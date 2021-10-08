<?php 
namespace Tidy\Http;
class Response {
    public function back(){
    //  dd('Location:' .  $_SERVER['HTTP_REFERER']);
        header('Location:' . $_SERVER['HTTP_REFERER']);
        die();
        // return $this;
    }
    public function setStatusCode(int $code) {
        http_response_code($code);
    }

}