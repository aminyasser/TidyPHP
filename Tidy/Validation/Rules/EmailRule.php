<?php 
namespace Tidy\Validation\Rules;

class EmailRule implements Rule
{
    public function apply($field, $value, $data = [])
    {
        return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $value);
    }

    public function __toString()
    {
        return 'your %s is not a valid email';
    }
}