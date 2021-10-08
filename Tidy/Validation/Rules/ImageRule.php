<?php 
namespace Tidy\Validation\Rules;

class ImageRule implements Rule {
    protected $img;
    protected  $types = ['jpg' ,'jpeg' ,'png' , 'gif' , 'svg' , 'bmp' ];

    public function apply($field, $value, $data = []) {
        if (!in_array( end(explode('.' , $value)) , $this->types)) {
            return false;
        }

        return true;
    }

    public function __toString() {
        return "%s must be an image";
    }
}