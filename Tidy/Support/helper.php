<?php

use Tidy\Application;
use Tidy\Http\Request;
use Tidy\Http\Response;
use Tidy\Http\Route;
use Tidy\Support\Arr;
use Tidy\View\View;
use Tidy\Support\Session;

if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        return dirname(__DIR__) . '/../';
    }
}

if (!function_exists('view_path')) {
    function view_path()
    {
        return  base_path() . 'views/';
    }
}
if (!function_exists('public_path')) {
    function public_path()
    {
        return  base_path() . 'public/';
    }
}
if (!function_exists('asset')) {
    function asset($url)
    {
        return  '/assets/' . $url;
    }
}
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        return $default;
    }
}


if (!function_exists('view')) {
    function view($view , $params = []){
        View::make($view ,$params);
        
    }
}

if (!function_exists('app')) {
    function app()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new Application();
        }

        return $instance;
    }
}

if (!function_exists('value')) {
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('dd')) {
    function dd($value)
    {
       dump($value);
       die();
    }
}

if (! function_exists('route')) {
    function route(string $name , $params = '') {
        $route = Route::getInstance()->getRouteByName($name);
        if ($route == false) {
           return false;
        } else {
          
         if(str_contains($route , '{')) {
                if (is_array($params)) {
                $solve = explode('/' , implode('',explode('{' , $route)));
                    $i=0;
                    
                foreach($solve as &$s) {
                    
                    if (str_contains($s , '}')) {
                    $s = $params[$i];
                    $i++;
                    }
                    
                } 
                $route = implode('/' , $solve); 
                } else {
                    $solve = explode('/' , implode('',explode('{' , $route)));
                 
                    foreach($solve as &$s) {
                        
                        if (str_contains($s , '}')) {
                        $s = $params;
                        }     
                    } 
                    $route = implode('/' , $solve); 
                } 
         }  else {

             if (is_array($params)) {
                 $q = '/';
                 $params =$q . implode('/' , $params);
             } else if ($params != '') {
                  $params = '/' . $params;
             } 

             $route = $route . $params;
         }
         
        //  rtrim( $_SERVER['HTTP_REFERER'] , '/') ??   ; 
            $serve = explode('/index.php' , $_SERVER['PHP_SELF'])[0];
        $go_to = env('URL') ? env('URL') . $route : 'http://' . $_SERVER['HTTP_HOST']. $serve . $route;
        
        return $go_to ;
        } 
            
       
    }
}

// Gets the absolute url by relative url
if (! function_exists('url')) {
    function url(string $url) {
        echo base_path() . $url;
    }
} 


if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app()->config;
        }

        if (is_array($key)) {
            return app()->config->set($key);
        }

        return app()->config->get($key, $default);
    }
}

if (!function_exists('config_path')) {
    function config_path()
    {
        return base_path() . 'config/';
    }
}

if (!function_exists('class_basename')) {
    function class_basename($class) {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('back')) {
    function back() {
        return app()->response->back();

    }
} 

if (!function_exists('request')) {
    function request($key = null){
        $instance = new Request();

        if (!$instance) {
            return new Request();
        }

        if ($key) {
            if (is_string($key)) {
                return $instance->get($key);
            }

            if (is_array($key)) {
                return $instance->only($key);
            }
        }

        return $instance;
    }
} 
if (!function_exists('old')) {
    function old($key) {
        if (app()->session->hasFlash('old')) {
            return app()->session->getFlash('old')[$key];
        }
    }
}

if (!function_exists('hasErrors')) {
    function hasErrors() {
  
        return app()->session->hasFlash('errors');
    }
}
if (!function_exists('errors')) {
    function errors() {
       $arr =Arr::flatten( app()->session->getFlash('errors'));
      return $arr;
    }
}
if (!function_exists('error')) {
    function error($name) {

        return app()->session->getFlash('errors')[$name][0];
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {

        header('Location:' . $url);
    }
}
