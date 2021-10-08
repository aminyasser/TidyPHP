<?php 
namespace Tidy\Validation\Rules;

class Required implements Rule {
    public function apply ($field , $value , $data) {
            return !empty($value);
    }
    public function __toString () {
        return '%s is required';
    }
}
