<?php 
namespace Tidy\Validation\Rules;

class MimeRule implements Rule {

    protected  $types = [];

    public function __construct(...$params) {
     
        $this->types = $params;  
    }

    public function apply($field, $value, $data = []) {
    
        if (!in_array( end(explode('.' , $value)) , $this->types)) {
            return false;
        }

        return true;
    }

    public function __toString() {
      
        $values = implode(',' , $this->types);
 
        return "%s must be mimes " . $values . " only";
    }
}