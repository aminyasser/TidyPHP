<?php 
namespace Tidy\Validation;

class Message
{
    public static function generate($rule, $field)
    { 
        //   echo $rule;
        return str_replace('%s', $field, $rule);
    }
}