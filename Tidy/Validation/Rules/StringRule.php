<?php 
namespace Tidy\Validation\Rules;
 

class StringRule implements Rule {
    public function apply($field, $value, $data = []) {
        return preg_match('/^[a-zA-Z_ -]+$/', $value);
    }

    public function __toString()
    {
        return '%s must be string only';
    }
}