<?php
class db{
	private $db_connection;
	private $settings = [];
	private $tables = [];
	
	function __construct($host, $user, $pass, $table){
		$this->connect($host, $user, $pass, $table);
		$this->query("SET NAMES 'utf8'");
		$this->settings['db'] = $table;
		$res = $this->query("SHOW TABLES");
		while($row = mysqli_fetch_array($res)){
			$this->tables[] = $row[0];
		}
	}
	
	function select_db($db, $conn){
		mysqli_select_db($conn, $db);
	}
	
	function connect($host, $user, $pass, $table){
		$this->db_connection = mysqli_connect($host, $user, $pass, $table);
		mysqli_set_charset($this->db_connection, "utf8");
	}
	
	function query($query){
		$sql = mysqli_query($this->db_connection, $query);
		return $sql;
	}
	
	function multi_query($query){
		$sql = mysqli_multi_query($this->db_connection, $query);
		return $sql;
	}
	
	public  function dbformat(&$val){
		$val = '`'.$val.'`';
	}
	
	function insert_array($table, $array, $secure = true){
		$fields = array();
		$values = array();
		foreach($array as $field => $value){
			$fields[] = $field;
			$values[] = ($secure ? $this->esc($value) : "'" . $value . "'");
		}
		
		array_walk($fields, array($this, 'dbformat'));
		$query = "INSERT INTO `$table` (". implode(", ", $fields) .") VALUES (".implode(", ", $values).")";
		
		if($this->query($query))
			return true;
		else
			return false;
	}
	
	function rows($query){
		return @mysqli_num_rows($query);
	}
	
	function fetch($query){
		return @mysqli_fetch_array($query, MYSQLI_ASSOC);
	}
	
	function esc($value, $hsc = true){
		if($hsc)
			$value = htmlspecialchars($value);
		if (get_magic_quotes_gpc())
			$value = stripslashes($value);
		if(!is_numeric($value))
			$value = "'" . $this->db_connection->real_escape_string($value) . "'";
		return $value;
	}
	
	function __destruct(){
		mysqli_close($this->db_connection);
	}
}