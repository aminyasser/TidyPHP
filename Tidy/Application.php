<?php

namespace Tidy;

use Tidy\Http\Route;
use Tidy\Database\DB;
use Tidy\Database\Managers\MySQLManager;
use Tidy\Http\Request;
use Tidy\Http\Response;
use Tidy\Support\Config;
use Tidy\Support\Session;


class Application {

    protected Route $route;
    protected Request $request;
    protected Response $response;
    protected Config $config;
    protected DB $db;
    protected Session $session;

    public function __construct()   {

        $this->request = new Request;
        $this->response = new Response;
        $this->route = new Route($this->request, $this->response);
        $this->config = new Config($this->loadConfigurations());
        $this->db = new DB($this->getDatabaseDriver());
        $this->session = new Session;
    }

    protected function getDatabaseDriver()   { 
        
        //  can add drivers 
        if (env('DB_DRIVER') == 'mysql') {
            // dump(new MySQLManager);
            return new MySQLManager;
        } else  return new MySQLManager;

    }

    protected function loadConfigurations()   { 
        foreach(scandir(config_path()) as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $filename = explode('.', $file)[0];
            yield $filename => require config_path() . $file;

        }
    }

    public function run()  {
        $this->db->init();
        $this->route->resolve();
  
    }

    public function __get($name)   {
        if(property_exists($this, $name)) {
            return $this->$name;
        }
    }
}