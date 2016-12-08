<?php
/**
 * Mysql connection class
 * 
 * @package Connectliving Admin
 * @author  Sboniso Nzimande
 */
class Mysql_Conn {
    public $last_sql;

    // TEST
    protected $host     = "dedi484.jnb2.host-h.net";
    // protected $host  = "bconnectglobal.co.za";
    protected $port     = 3306;
    // protected $user     = "connect_bcon";
    protected $user     = "conne_connect";
    // protected $pass     = "Sandpiper121021";
    protected $pass     = "Sandpiper121021";
    // protected $dbname   = "connect_bcon";
    protected $dbname   = "conne_connect";
    protected $secure   = FALSE;
    protected $link;
}