<?php
namespace Tidy\Database\Managers;

use Exception;
use PDOException;
use Tidy\Database\Grammars\MySQLGrammer;
use Tidy\Database\Managers\Main\DatabaseManager;
use Tidy\Database\Model\Model;

class MySQLManager implements DatabaseManager {

    protected static $instance;
    protected static $query;
    protected static $query_values = [];


    public function connect(): \PDO {
        // Singelton Pattern
        if (!self::$instance) {
            self::$instance = new \PDO(env('DB_DRIVER') . ':host=' . env('DB_HOST') 
            . ';dbname=' . env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
        }

        return self::$instance;
    }

    public function query(string $query, $values = []) {
        $stmt = self::$instance->prepare($query);

        for ($i = 1; $i <= count($values); $i++) {
            $stmt->bindValue($i, $values[$i - 1]);
        }

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function read($columns = '*', $filter = null){

        $query = MySQLGrammer::buildSelectQuery($columns, $filter);

        $stmt = Self::$instance->prepare($query);

        if ($filter) {
            $stmt->bindValue(':' . $filter[0], $filter[2]);
        }

        $stmt->execute();
        if ($stmt->rowCount() == 1)
        return $stmt->fetchAll(\PDO::FETCH_OBJ)[0];
        else 
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
   
    } 
    
    public function create($attributes) {
        $query = MySQLGrammer::buildInsertQuery(array_keys($attributes));
    
        $stmt = self::$instance->prepare($query);

        foreach($attributes as $key => $value) {
            $stmt->bindValue(':'. $key, $value );
        }

        return $stmt->execute();
    }
    
    public function update($id, $attributes) {

        $query = MySQLGrammer::buildUpdateQuery(array_keys($attributes));
        $stmt = self::$instance->prepare($query);
        
        foreach($attributes as $key => $value) {
            $stmt->bindValue(':'. $key, $value );
        }
        $stmt->bindValue(':id', $id );

        return $stmt->execute();
    }

    public function delete($id) {
        $query = MySQLGrammer::buildDeleteQuery();

        $stmt = self::$instance->prepare($query);

        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    //  DB Part! 

    public function readDB(string $table = ''){

        $query = MySQLGrammer::buildSelectQueryDB($table);    
        self::$query = $query;
    }

    public function selectDB($selectors){

        $query = MySQLGrammer::addSelectorsQueryDB($selectors , self::$query); 
        self::$query = $query;
    }
    public function whereDB( $filter = null){

        $query = MySQLGrammer::addWhereQueryDB($filter);
        array_push( self::$query_values , $filter[2]);
        self::$query .=   ' ' . $query;

    }
    public function whereInDB( $col ,  $filter = null){

        $query = MySQLGrammer::addWhereInQueryDB($col , $filter);
        self::$query_values =  array_merge(self::$query_values , $filter);
        self::$query .=   ' ' . $query;

    } 
    public function andWhereInDB( $col ,  $filter = null){

        $query = MySQLGrammer::addAndWhereInQueryDB($col , $filter);
        self::$query_values =  array_merge(self::$query_values , $filter);
        self::$query .=   ' ' . $query;

    }
    public function orWhereInDB( $col ,  $filter = null){

        $query = MySQLGrammer::addOrWhereInQueryDB($col , $filter);
        self::$query_values =  array_merge(self::$query_values , $filter);
        self::$query .=   ' ' . $query;

    }
    public function whereNotInDB( $col ,  $filter = null){

        $query = MySQLGrammer::addWhereNotInQueryDB($col , $filter);
        self::$query_values =  array_merge(self::$query_values , $filter);

        self::$query .=   ' ' . $query;

    }

    public function andWhereNotInDB( $col ,  $filter = null){

        $query = MySQLGrammer::addAndWhereNotInQueryDB($col , $filter);
        self::$query_values =  array_merge(self::$query_values , $filter);

        self::$query .=   ' ' . $query;

    }
    public function orWhereNotInDB( $col ,  $filter = null){

        $query = MySQLGrammer::addOrWhereNotInQueryDB($col , $filter);
        self::$query_values =  array_merge(self::$query_values , $filter);

        self::$query .=   ' ' . $query;

    }

    public function andWhereDB( $filter = null){

        $query = MySQLGrammer::addAndWhereQueryDB($filter);
        array_push( self::$query_values , $filter[2]);
        
        self::$query .=   ' ' . $query;

    }

    public function orWhereDB( $filter = null){

        $query = MySQLGrammer::addOrWhereQueryDB($filter);
        array_push( self::$query_values , $filter[2]);

        self::$query .=   ' ' . $query;

    }

    public function orderByDB( $filter , $type){

        $query = MySQLGrammer::addOrderByQueryDB($filter , $type);
        self::$query .=   ' ' . $query;

    }
    public function takeDB( $num){

        $query = MySQLGrammer::addTakeQueryDB($num);
        self::$query .=   ' ' . $query;

    }
    public function groupByDB( $filter ){

        $query = MySQLGrammer::addGroupByQueryDB($filter);
        self::$query .=   ' ' . $query;

    }
    public function havingDB( $filter ){

        $query = MySQLGrammer::addHavingQueryDB($filter);
        array_push( self::$query_values , $filter[2]);
        self::$query .=   ' ' . $query;

    }
    public function getDB(){

    
      
        $stmt = Self::$instance->prepare(self::$query);
            for($i=1 ; $i <= count(self::$query_values) ; $i++) {
                $stmt->bindValue($i, self::$query_values[$i - 1]);
            } 

           $stmt->execute();
        
        self::$query_values = [];
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    public function countDB(){

     
        $stmt = Self::$instance->prepare(self::$query);
        for($i=1 ; $i <= count(self::$query_values) ; $i++) {
            $stmt->bindValue($i, self::$query_values[$i - 1]);
        } 
        $stmt->execute();
        self::$query_values = [];
        
        return $stmt->rowCount();
    }

    public function firstDB(){

        self::$query = MySQLGrammer::addFirstQueryDB(self::$query);
    
        $stmt = Self::$instance->prepare(self::$query);

        for($i=1 ; $i <= count(self::$query_values) ; $i++) {
            $stmt->bindValue($i, self::$query_values[$i - 1]);
        } 
        $stmt->execute();
        self::$query_values = [];

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    public function existsDB(){

        self::$query = MySQLGrammer::addFirstQueryDB(self::$query);
   
        $stmt = Self::$instance->prepare(self::$query);

        for($i=1 ; $i <= count(self::$query_values) ; $i++) {
            $stmt->bindValue($i, self::$query_values[$i - 1]);
        } 
        $stmt->execute();
        self::$query_values = [];
        
        if ($stmt->rowCount() > 0) {
            return true;
        } else return false;
       
    }

    
}