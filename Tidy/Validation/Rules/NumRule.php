<?php 
namespace Tidy\Validation\Rules;
 

class NumRule implements Rule {
    public function apply($field, $value, $data = []) {
        return preg_match('/^[0-9_ -]+$/', $value);
    }

    public function __toString() {
        return '%s must be numeric only';
    }
}