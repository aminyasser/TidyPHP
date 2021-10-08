<?php 
namespace Tidy\Validation;

use Tidy\Validation\Rules\AlphaNumRule;
use Tidy\Validation\Rules\Required;
use Tidy\Validation\Rules\MaxRule;
use Tidy\Validation\Rules\BetweenRule;
use Tidy\Validation\Rules\EmailRule;
use Tidy\Validation\Rules\ImageRule;
use Tidy\Validation\Rules\MinRule;
use Tidy\Validation\Rules\MimeRule;
use Tidy\Validation\Rules\NumRule;
use Tidy\Validation\Rules\StringRule;

trait RulesMapper {
    protected static array $map = [
        'required' => Required::class, 
        'alnum' => AlphaNumRule::class, 
        'max' => MaxRule::class, 
        'between' => BetweenRule::class,
        'email' => EmailRule::class,
        'unique' => UniqueRule::class,
        'image' => ImageRule::class , 
        'min'  => MinRule::class , 
        'mimes' => MimeRule::class,
        'number' => NumRule::class,
        'string' => StringRule::class
    ];


    public static function resolve(string $rule, $options) {
      
        return new static::$map[$rule](...$options);
    }
}