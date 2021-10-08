<?php 
namespace Tidy\Validation\Rules;


interface Rule {
    public function apply ($field , $value , $data);
    public function __toString();
}