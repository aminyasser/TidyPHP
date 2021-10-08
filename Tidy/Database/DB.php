<?php 
namespace Tidy\Database;

use Tidy\Database\Managers\Main\DatabaseManager;
use Tidy\Database\Concerns\Connect;
class DB {
    protected DatabaseManager $manager;
    private static $db = null;
    public function __construct(DatabaseManager $manager) {
        $this->manager = $manager;
    }

    public function init() { 
        Connect::connect($this->manager);
    }

    
    public static function __callStatic($method, $arguments) {
        $route = self::getInstance();
        if ($method == 'table') {
            return $route->start($arguments[0]);
        } 
    }

    public static function getInstance()  {
        $db = static::class;
        if (!isset(self::$db)) {
            self::$db= app()->db;
        }
        
        return self::$db;
    }

    public function start (string $table) {
 
        $this->manager->readDB($table);
        return $this;
    }

    public function select ($selectors) {

        $this->manager->selectDB($selectors);
        return $this;
    }
    public function where ($filter) {
       $this->manager->whereDB($filter);
       return $this;
    }

    public function andWhere ($filter) {
        $this->manager->andWhereDB($filter);
        return $this;
     }
     public function orWhere ($filter) {
        $this->manager->orWhereDB($filter);
        return $this;
     }
     public function whereIn ( $col , $filter) {
        $this->manager->whereInDB($col , $filter);
        return $this;
     }
     public function andWhereIn ( $col , $filter) {
        $this->manager->andWhereInDB($col , $filter);
        return $this;
     } 
     public function orWhereIn ( $col , $filter) {
        $this->manager->orWhereInDB($col , $filter);
        return $this;
     }
     public function whereNotIn ($col , $filter) {
        $this->manager->whereNotInDB($col , $filter);
        return $this;
     }
     public function andWhereNotIn ( $col , $filter) {
        $this->manager->andWhereNotInDB($col , $filter);
        return $this;
     } 
     public function orWhereNotIn ( $col , $filter) {
        $this->manager->orWhereNotInDB($col , $filter);
        return $this;
     }
     public function orderBy ($filter , $type='') {
        $this->manager->orderByDB($filter , $type);
        return $this;
     }
     public function groupBy ($filter ) {
        $this->manager->groupByDB($filter);
        return $this;
     }
     public function having ($filter ) {
        $this->manager->havingDB($filter);
        return $this;
     }
     public function take ($num ) {
        $this->manager->takeDB($num);
        return $this;
     }


    public function get () {
        return $this->manager->getDB();
    }
    public function count () {
        return $this->manager->countDB();
    }
    public function first () {
        return $this->manager->firstDB();
    }

    public function exists () {
        return $this->manager->existsDB();
    }
    public function latest ($colm = 'id') {
        return $this->orderBy($colm , 'desc');
    }
    
    protected function raw(string $query, $value = [])  {
        return $this->manager->query($query, $value);
    }
    
    protected function create(array $data)  {
        return $this->manager->create($data);
    }
    
    protected function read($columns = '*', $filter = null) {
        return $this->manager->read($columns, $filter);
    }

    protected function update($id, array $attributes)  {
        return $this->manager->update($id, $attributes);
    }
    protected function delete($id){
        return $this->manager->delete($id);
    }

    public function __call($name, $arguments)  {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }
    }
}