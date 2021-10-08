<?php 
namespace Tidy\Http;

use Exception;
use Tidy\View\View;

class Route {
    public Request $request;
    public Response $response;
 

    public function __construct(Request $request, Response $response) { 
      
        $this->request = $request;
        $this->response = $response;
   
    }
    private static  $route = null ;
    public static array $routes = [];
    private static $methods = [
        'get' , 'post',
    ];
    private array  $current = [];
    private array  $urls = [];
        public static function __callStatic($method, $arguments){
                $route = self::getInstance();
                if (in_array( $method,self::$methods)) {
                    return $route->add($method, $arguments[0] , $arguments[1]);
                } 
            }

        public static function getInstance(){
            $route = static::class;
            if (!isset(self::$route)) {
                self::$route= new static(new Request , new Response);
            }
            
            return self::$route;
        }
        
        public function add ( $method, $route ,  $action) {
            // $route = explode('{' , $route);
            // foreach ($route as $r) {
            // dump(explode('}' , $r)[0]) ;
            // }
            
            self::$routes[$method][$route]['action'] = $action;
            $this->current = [
                'method' => $method , 
                'route' => $route ,
            ];
            
            
            return $this;
            
        }
        
    public  function name(string $name) {
        $path = $this->current['route'];
        $method = $this->current['method'];

        self::$routes[$method][$path]['name']  = $name;
        if (isset($this->urls[$name])) {
              
            $e = new Exception('The route name "' . $name . '" is already exist');
            $error = $e->getMessage() . '  on line ' .  $e->getTrace()[0]['line'] . '  at '
             . $e->getTrace()[0]['file'];
                   die ($error);
              
                return self::$routes;
        }
        $this->urls[$name] = $path;
        // array_push($this->urls , ['path' => $path, 'name' => $name]);
        return self::$routes;
    }

    public function getRouteByName(string $name) {          

        if ($this->urls[$name]) {
            return $this->urls[$name];
        } else return false;
    }


    public function resolve() {
        $path = $this->request->path();
        // paramter pathing in get method

        $params = $this->request->params() ;
        if ($params == false)
           $params = [];
           
        $post = $this->request->request();
        // $r = new Request ;
    
        $method = $this->request->getMethod(); 
        
        $data = $this->request->urlMatcher($method , $path);
       
        if(is_array($data)) {
        $action = $data['action'];
        $params = $data['params'];
        } else {
            $action = false;
        }
      
       
    // $action = self::$routes[$method][$path]['action'] ?? false;
        // dd($path);
        // not Found 
        if (!$action) {
            View::makeError('errors.404');
        }

        // callback function 
        if (is_callable($action) && !is_array($action)) {
            call_user_func_array($action , []);
        }
     
        // array 
        if (is_array($action)) {
            // $par = explode('=' , $par)[1];
            if ($method == 'get') 
            call_user_func_array([new $action[0],$action[1]] , $params);
            else if ($method == 'post') {
               $params =  array_merge([$this->request] , $params);
      
            call_user_func_array([new $action[0],$action[1]] , $params);
            }
        }


    } 

  
}