<?php 
namespace Tidy\Http;

use Tidy\Validation\Validator;
use Tidy\Support\Arr;

class Request {
        public $post = [];
        public function set (array $post) {
                $this->$post = $post;
        }
        public function getMethod() {
            return strtolower($_SERVER['REQUEST_METHOD']);
        }

        public function path() {
            //  dump($this);
             $path1 = $_SERVER['REQUEST_URI'] ?? '/';
     
            //  dump($_SERVER['SCRIPT_NAME']);
            if( explode('/index.php' , $_SERVER['PHP_SELF'] )[0] == '') {
                $path = $path1;
            } else $path = explode( '/public' , $_SERVER['REQUEST_URI'])[1] ?? $path1;
        
            //  dump(str_contains($path , '?') ? explode('?' , $path)[0] : $path );
             return str_contains($path , '?') ? explode('?' , $path)[0] : $path ;
        }

        public function params() {
            //  dump($this);
             $path = $_SERVER['QUERY_STRING'] ?? false;
              $params = [];
                if (str_contains($path , '&')) { 
                    foreach( explode('&' , $path) as $query) {
                        $params += [explode('=' , $query)[0] => explode('=' , $query)[1] ] ;
                    } 
                    return $params;

                } else if (str_contains($path , '=')) {
                    $params += [explode('=' , $path)[0] => explode('=' , $path)[1] ] ;
                    return $params;
                } else return $path;
          
        }
        public function request () {  
                return $_REQUEST;
        }

        public function validate (array $rules) {
            $val =new Validator;
            $val->setRules($rules);
            $val->make($this->request());
    
            if ($val->errorsExist()) {

                app()->session->setFlash('errors' , $val->errors());
                app()->session->setFlash('old' , $this->request());
    
              return back();
    
            } 
          
                return $this->request();
     
        }
        public function only($keys) {
            return Arr::only($this->request(), $keys);
        }
    
        public function get($key){
            return Arr::get($this->request(), $key);
        }
        public function urlMatcher ($method , $path) {
                
                $params = [];
                $check = Route::$routes[$method][$path]['action'] ?? false;
                if ($check != false) {
                    $data['action'] = $check;
                    $data['params'] = $params;
                    return $data;
                }
                
               
                $arr = explode('/' , trim($path , '/'));
                $path_component = $arr[0] != "" ? $arr : ['/']; 
                 
                $routes = Route::$routes[$method] ;
           
                foreach (array_keys($routes) as $route) {
                    $r = explode('/' , trim($route , '/'));
                    $r = $r[0] != "" ? $r : ['/']; 
                 
                    if (count($path_component) == count($r)) {
                            for ($i = 0 ; $i < count($r) ; $i++) {
                                if ($r[$i] != $path_component[$i] &&
                                  !(str_contains($r[$i] , '{') && str_contains($r[$i] , '}') )) {
                                    return false;
                                }   
                                if(str_contains($r[$i] , '{')) {
                                    array_push($params , $path_component[$i]);
                                }  
                            } 
                        $data['action'] = Route::$routes[$method][$route]['action'];
                        $data['params'] = $params;
                    
                         return $data ;

                    } 
                }
                return false;
        }
    


}