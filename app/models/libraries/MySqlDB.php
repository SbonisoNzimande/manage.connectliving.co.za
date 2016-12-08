<?php
/**
 * MSQLI connection class
 * 
 * @todo Validate the functions
 * @package Qmart Stock Allocation App
 * @author  Sboniso Nzimande
 */
class MySqlDB extends Mysql_Conn implements DB {

    private $new_link = true;
    private $client_flags = 0;
    
    public function __construct() {
        $this->connect();
        $this->select_db($this->dbname);
    }
    
    public function __destruct() {
        $this->close();
    }
    
    public function connect() {

        $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        
        if ($this->link->connect_errno) {
            /* Error connecting */
            return 'Error connecting: '.$this->link->connect_errno;
        }
    }

    public function errno() {
        return $this->link->errno;
    }

    public function error() {
        return $this->link->error;
    }

    public function escape_string($string) {
        return $this->link->real_escape_string($string);
    }

    public function query($query) {
        $this->last_sql = $query;
        return $this->link->query($query);
    }
    
    public function fetch_array($result, $array_type = MYSQL_BOTH) {
        return $result->fetch_array($array_type);
    }

    public function fetch_row($result) {
        return $result->fetch_row();
    }
    
    public function fetch_assoc($result) {
        return $this->link->fetch_assoc($result);
    }
    
    public function fetch_object($result)  {
        return $this->link->fetch_object($result);
    }
    
    public function num_rows($result) {
        return $result->num_rows;
    }

    public function insert_id() {
        return $this->link->insert_id;
    }
    
    public function close() {
        return $this->link->close();
    }
    
    public function select_db($db) {
        return $this->link->select_db($db);
    }

    public function prepare($query){
        return $this->link->prepare($query);
    }

    // public function bind_param(mysqli_stmt $stmt, array $args){
    //     $refargs = array("");
    //     foreach ($args as $key => $value) {
    //             $refargs[] =& $args[$key];
    //         }
    //     return call_user_func_array(array($stmt, "bind_param"), $refargs);
    // }

    public function execute($stmt){
        return $stmt->execute();
    }

    public function mssql_init($sp){}
    public function mssql_bind($sp, $varName, $varValue, $varType){}
    public function mssql_execute($query){}

}