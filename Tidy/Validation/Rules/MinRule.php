<?php 
namespace Tidy\Validation\Rules;

class MinRule implements Rule {
    protected $min;

    public function __construct($min)  {
        $this->min = $min;
    }

    public function apply($field, $value, $data = [])
    {
        if (strlen($value) < $this->min) {
            return false;
        }

        return true;
    }

    public function __toString()
    {
        return "%s must be more than {$this->min} characters";
    }
}