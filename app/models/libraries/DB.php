<?php

interface DB {
    public function connect();
    public function error();
    public function errno();
    public function escape_string($string);
    public function query($query);
    public function fetch_array($result);
    public function fetch_row($result);
    public function fetch_assoc($result);
    public function fetch_object($result);
    public function num_rows($result);
    public function close();
    public function insert_id();

    public function prepare($query);
    // public function bind_param(mysqli_stmt $stmt, array $args);
    public function execute($stmt);


    /* MS SP*/
    public function mssql_init($sp);
    public function mssql_bind($sp, $varName, $varValue, $varType);
    public function mssql_execute($query);
    

}