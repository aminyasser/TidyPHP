<?php 
namespace Tidy\Database\Model;

use Tidy\Database\DB;
use Tidy\Support\Str ; 

abstract class Model {
    protected static $instance;
    protected static $table;

    
    public static function create(array $attributes) {
        self::$instance = static::class;

        return app()->db->create($attributes);
    }
    
    public static function all() {
        self::$instance = static::class;

        return app()->db->read();
    }
    
    public static function find($id) {
        self::$instance = static::class;

        return app()->db->read('*' , ['id' , '=' , $id]);
    }
    public static function update($id, array $attributes){
        self::$instance = static::class;

        return app()->db->update($id, $attributes);
    }
    public static function delete($id) {
        self::$instance = static::class;

        return app()->db->delete($id);
    }

   

    public static function where( $filter, $columns = '*')  {
        self::$instance = static::class;
       
        return DB::table(static::getTableName())->select($columns)->where($filter);
      
    }

    public static function whereIn($col , $filter, $columns = '*')  {
        self::$instance = static::class;
       
        return DB::table(static::getTableName())->select($columns)->whereIn($col , $filter);
      
    }

    public static function whereNotIn($col , $filter, $columns = '*')  {
        self::$instance = static::class;
       
        return DB::table(static::getTableName())->select($columns)->whereNotIn($col , $filter);
      
    }

    public static function orderBy($filter, $type = '')  {
        self::$instance = static::class;
       
        return DB::table(static::getTableName())->orderBy($filter,$type);
      
    }
    public static function groupBy($filter , $columns = '*')  {
        self::$instance = static::class;
       
        return DB::table(static::getTableName())->select($columns)->groupBy($filter);
      
    }


    public static function getModel() { 
        self::$instance = static::class;
    
        return self::$instance;
    }

    public static function getTableName()  { 
         if(static::$table == null)
             return Str::lower(Str::plural(class_basename(self::$instance)));
         else   return static::$table;
    }
}
