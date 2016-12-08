<?php
/**
 * MS Sql connection class
 * 
 * @package Propertuity Admin
 * @author  Sboniso Nzimande
 */
class Mssql_Conn {
    public $last_sql;

    protected $host 	= "188.121.44.212";
    protected $port 	= 3306;
    protected $user 	= "propertuity";
    protected $pass 	= "S@ndpiper121021";
    protected $dbname  	= "propertuity";
    protected $secure  	= FALSE;
    protected $link;
}