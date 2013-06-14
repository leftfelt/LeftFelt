<?php

require_once dirname(__FILE__)."/../core/Model.class.php";

class ServiceModel extends Model{
	private $table_name = "service_id_table";
	private $db;

	public function initialize(){
	}

	public function connect($conf){
		$dbmanager = $this->getModel('dbManager');
		$this->db = $dbmanager->connect($conf);
		$this->db->setTableName($this->table_name);
	}

	/**
	 * 登録されたサービスのテーブル名を取得する
	 * @param $service_id サービスID
	 * @return table_name テーブル名
	 */
	public function get($service_id){
		$query = "SELECT service_table_name FROM $this->table_name WHERE service_id = ?";
		$stmt = $this->db->prepare($query);
		$param = array($service_id);
		try{
			$stmt->execute($param);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		return $result['service_table_name'];
	}

	/**
	 * サービスのテーブルを登録する
	 * @param $service_id
	 * @param $table_name
	 * @return bool true: 成功 false: 登録に失敗
	 */
	public function set($service_id,$user_table_name){
		$query = "INSERT INTO $this->table_name(service_id,service_table_name) VALUES(?,?)";
		$stmt = $this->db->prepare($query);
		$param = array($service_id,$user_table_name);
		try{
			$result = $stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return $result;
	}

	/**
	 * サービスを削除する
	 * @param $service_id
	 * @return bool true: 成功 false: 削除に失敗
	 */
	public function delete($service_id){
		$query = "DELETE FROM $this->table_name WHERE service_id = ?";
		$stmt = $this->db->prepare($query);
		$param = array($service_id);
		try{
			$result = $stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return $result;
	}
	
	/**
	 * サービスリストを取得する
	 * @param int limit
	 * @param int offset
	 * @return array 
	 */
	public function getList($limit = null,$offset = null){
		$query = array();
		$param = array();
		$query[] = "SELECT * FROM $this->table_name";
		if(isset($limit)){
			$query[] = "LIMIT ?";
			$param[] = $limit;
		}
		if(isset($offset)){
			$query[] = "OFFSET ?";
			$param[] = $offset;
		}
		$query = implode(' ',$query);
		
		$stmt = $this->db->prepare($query);
		try{
			$stmt->execute($param);
			$result = $stmt->fetchAll();
		}catch(Exception $e){
			echo $e->getMessage();
			return array();
		}
		return $result;
	}
	/**
	 * データをすべて削除する
	 * @return bool true: 成功 false: 削除に失敗
	 */
	public function truncate(){
		return $this->db->truncate();
	}
}
