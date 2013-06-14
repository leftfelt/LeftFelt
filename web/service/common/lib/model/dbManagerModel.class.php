<?php

require_once dirname(__FILE__)."/../core/Model.class.php";

class dbManagerModel extends Model{

	public function initialize(){
	}

	public function connect($conf){
		$host 	= $conf['host'];
		$dbname = $conf['dbname'];
		$user 	= $conf['user'];
		$pass 	= $conf['pass'];
		return new dbAccesor(
			"mysql:host=$host;dbname=$dbname",
			$user,
			$pass
		);
	}
}

class dbAccesor extends PDO{
	private $table_name;

	public function setTableName($table_name){
		$this->table_name = $table_name;
	}
	public function truncate(){
		$query = "TRUNCATE $this->table_name";
		$stmt = $this->prepare($query);
		try{
			$stmt->execute();
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return true;
	}
	public function drop(){
		$query = "DROP TABLE $this->table_name";
		$stmt = $this->db->prepare($query);
		try{
			$result = $stmt->execute();
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return $result;

	}
}
