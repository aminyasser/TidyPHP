<?php 
namespace Tidy\View;

class View {
    public static function make ($view , $params = []) {
            
            $base = self::getBaseContent();
            $viewContent = self::getViewContent($view , $params );
      

            echo (str_replace('{{content}}', $viewContent, $base));
    }
    public static function getBaseContent () {
            ob_start();
            include base_path() . 'views/layouts/app.php';
            return ob_get_clean();
    
    }
    public static function makeError ($error) {
           
        self::getViewContent($error , [] , true);
    
    }
    public static function makeView ($view , $params) {
           
        self::getViewContent($view , $params , true);
    
    }
    public static function getViewContent ($view ,$params = [] , $is_view = false) {
            
            $path =  view_path() ;
           
            if (str_contains($view, '.')) {
                $views = explode('.', $view);
    
                foreach ($views as $view) {
                    // echo $path . $view . "<br>";
                    // dump($path . $view );
                    if (is_dir($path . $view)) {
                        $path = $path .  $view . '/';
                    } 
               
                }
                $view = $path . end($views) . '.php';
            } else {
                $view = $path . $view . '.php';
            }
            
            // to send data['users'] and extract users array in view
            foreach($params as $param => $value) {
                $$param = $value;
            }
            
            //   dump($view);
      
            if($is_view)
            include $view;
            else {
                ob_start();
                include $view;
                return ob_get_clean();
            }
           
    } 

    public function with($params = [])
    {  
        foreach($params as $param => $value) {
            $$param = $value;
        }
            
    }
}