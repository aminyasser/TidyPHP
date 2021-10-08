<?php

namespace Tidy\Database\Grammars;

use Tidy\Database\Model\Model;


class MySQLGrammer { 

    public static function buildInsertQuery($keys) { 
        $query = 'INSERT INTO ' . Model::getTableName() ;
        $values = '';
        foreach ($keys as $key => $value) {
            $values .= " :{$value} , ";
        }
      $colms =  ' (`' . implode('`, `', $keys) . '`)' ;
     $query .=  $colms .' VALUES(' . rtrim($values, ', ')  . ') ';

        return $query;
    }

    public static function buildUpdateQuery($keys){
        $query = 'UPDATE ' . Model::getTableName() . ' SET ';

        foreach ($keys as $key) {
            $query .= "{$key} = :{$key} , ";
        }

        $query = rtrim($query, ', ') ;
        $query .= ' WHERE id = :id';

        return $query;
    }

    public static function buildSelectQuery($columns = '*', $filter = null)
    {
        if (is_array($columns)) {
            $columns = implode(', ', $columns);
        }

        $query = "SELECT {$columns} FROM " . Model::getTableName();

        if ($filter) {
            $query .= " WHERE {$filter[0]} {$filter[1]} :{$filter[0]}";
        }

        return $query;
    } 
    public static function buildSelectQueryDB($table = '')  {
      
        $query = "SELECT * FROM " . $table;

        return $query;
    }
    public static function buildDeleteQuery() {
        return 'DELETE FROM ' . Model::getTableName() . ' WHERE ID = :id';
    }

    public static function addSelectorsQueryDB($selectors , $query)  {
       if (is_array($selectors)) {

           $selectors = implode(',' , $selectors);
           $selectors = rtrim($selectors, ', ');
       }
    
        $query = str_replace('*' , $selectors , $query);
        
        return $query;
    }

    public static function addWhereQueryDB($filter = null)  {
      
        if ($filter) {
            $query = " WHERE {$filter[0]} {$filter[1]} ?";
        }

        return $query;
    }
    public static function addWhereInQueryDB($col , $filter = null)  {
        $q = '';
        foreach($filter as $f) {
            $q .= '?,'; 
        }
        $filter = rtrim($q, ', ');
        if ($filter) {
            $query = " WHERE {$col} IN  ({$filter}) ";
        }

        return $query;
    }
    public static function addAndWhereInQueryDB($col , $filter = null)  {
        $q = '';
        foreach($filter as $f) {
            $q .= '?,'; 
        }
        $filter = rtrim($q, ', ');
        if ($filter) {
            $query = " AND {$col} IN  ({$filter}) ";
        }

        return $query;
    }

    public static function addOrWhereInQueryDB($col , $filter = null)  {
        $q = '';
        foreach($filter as $f) {
            $q .= '?,'; 
        }
        $filter = rtrim($q, ', ');
        if ($filter) {
            $query = " OR {$col} IN  ({$filter}) ";
        }

        return $query;
    }
    public static function addWhereNotInQueryDB($col , $filter = null)  {
        $q = '';
        foreach($filter as $f) {
            $q .= '?,'; 
        }
        $filter = rtrim($q, ', ');
        if ($filter) {
            $query = " WHERE {$col} NOT IN  ({$filter}) ";
        }

        return $query;
    }
    public static function addAndWhereNotInQueryDB($col , $filter = null)  {
        $q = '';
        foreach($filter as $f) {
            $q .= '?,'; 
        }
        $filter = rtrim($q, ', ');
        if ($filter) {
            $query = " AND {$col} NOT IN  ({$filter}) ";
        }

        return $query;
    }

    public static function addOrWhereNotInQueryDB($col , $filter = null)  {
        $q = '';
        foreach($filter as $f) {
            $q .= '?,'; 
        }
        $filter = rtrim($q, ', ');
        if ($filter) {
            $query = " OR {$col} NOT IN  ({$filter}) ";
        }

        return $query;
    }
    public static function addAndWhereQueryDB($filter = null)  {
      
        if ($filter) {
            $query = " AND {$filter[0]} {$filter[1]} ?";
        }

        return $query;
    }

    public static function addOrWhereQueryDB($filter = null)  {
      
        if ($filter) {
            $query = "OR {$filter[0]} {$filter[1]} ?";
        }

        return $query;
    }
    public static function addFirstQueryDB($query)  {
      
         $query .= ' LIMIT 1';

        return $query;
    }

    public static function addTakeQueryDB($num)  {
      
        $query = " LIMIT {$num}";

       return $query;
   }

    public static function addOrderByQueryDB($filter , $type = '')  {
      
        $query = " ORDER BY {$filter} {$type}";

       return $query;
   }
   public static function addGroupByQueryDB($filter)  {
    if(is_array($filter)) {

        $filter = implode(',' , $filter);
        $filter = rtrim($filter, ', ');
    }
    $query = " GROUP BY {$filter} ";
     return $query;
    }
    public static function addHavingQueryDB($filter)  {
        
        $query = " HAVING  {$filter[0]} {$filter[1]} ? ";
         return $query;
    }
  
}