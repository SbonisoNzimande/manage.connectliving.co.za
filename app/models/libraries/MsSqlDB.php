<?php
/**
 * MS SQL connection class
 *
 * @package Propertuity Admin
 * @author  Sboniso Nzimande
 */
class MsSqlDB extends Mssql_Conn implements DB {

	private $new_link 		= true;
	private $client_flags 	= 0;
	
	public function __construct() {
		// To do validate
		$this->connect();
		$this->select_db($this->dbname);

	}
	
	public function __destruct() {
		$this->close();
	}

	public function connect() {
		// $connectionInfo = array("Database" => $this->dbname, "UID" => $this->user, "PWD" => $this->pass);
		// $this->link     = sqlsrv_connect($this->host, $connectionInfo) or die('Cannot connect to msql: '. $this->error);
		$this->link         = mssql_connect($this->host, $this->user, $this->pass) or die('Cannot connect to msql: '. $this->error);

		 
	}

	public function errno() {
		return mssql_get_last_message();
	}

	public function error() {
		return mssql_get_last_message();
	}

	public function escape_string($string) {
		$fix_str    = stripslashes($string); 
		$fix_str    = str_replace("'","''",$string); 
		$fix_str    = str_replace("\0","[NULL]",$fix_str);
		return $fix_str;
	}

	public function query($query) {
		$this->last_sql = $query;
		return mssql_query($query);
	}

	public function fetch_array($result, $array_type = MSSQL_BOTH) {
		return mssql_fetch_array ($result, $array_type);
	}

	public function fetch_row($result) {
		return mssql_fetch_row($result);
	}

	public function fetch_assoc($result) {
		return mssql_fetch_assoc ($result);
	}

	public function fetch_object($result)  {
		return mssql_fetch_object ($result);
	}

	public function num_rows($result) {
		return mssql_num_rows ($result);
	}

	public function mssql_init($sp) {
		return mssql_init($sp, $this->link);
	}

	public function mssql_bind($sp, $varName, $varValue, $varType){
		return mssql_bind ($sp, $varName, $varValue, $varType);
	}

	public function mssql_execute($query){
		return mssql_execute ($query);
	}

	public function close() {
		return mssql_close ($this->link);
	}

	public function select_db($db) {
		return mssql_select_db ($db, $this->link);
	}

	public function insert_id() {
		return $this->link->insert_id;
   	}

	public function prepare($query){}

	public function execute($stmt){}

}