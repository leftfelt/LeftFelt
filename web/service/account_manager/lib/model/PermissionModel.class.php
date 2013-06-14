<?php

require_once dirname(__FILE__)."/../core/Model.class.php";

class PermissionModel extends Model{
	private $table_name = "permission_table";
	private $db;

	public function initialize(){
	}

	public function connect($conf){
		$dbmanager = $this->getModel('dbManager');
		$this->db = $dbmanager->connect($conf);
		$this->db->setTableName($this->table_name);
	}

	/**
	 * 登録された権限名を取得する
	 * @param $id ID
	 * @return permission 権限名
	 */
	public function get($id){
		$query = "SELECT permission FROM $this->table_name WHERE id = ?";
		$stmt = $this->db->prepare($query);
		$param = array($id);
		try{
			$stmt->execute($param);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		return $result['permission'];
	}

	/**
	 * 権限を登録する
	 * @param $permission
	 * @return bool true: 成功 false: 登録に失敗
	 */
	public function set($permission){
		$query = "INSERT INTO $this->table_name(permission) VALUES(?)";
		$stmt = $this->db->prepare($query);
		$param = array($permission);
		try{
			$stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return true;
	}
	
	/**
	 * 権限リストを取得する
	 * @return array 権限リスト 
	 */
	public function getPermissionList(){
		$query = "SELECT * FROM $this->table_name";
		$stmt = $this->db->prepare($query);
		try{
			$stmt->execute();
			$result = $stmt->fetchAll();
		}catch(Exception $e){
			echo $e->getMessage();
			return array();
		}
		return $result;
	}

	/**
	 * 権限を削除する
	 * @param $id
	 * @return bool true: 成功 false: 削除に失敗
	 */
	public function delete($id){
		$query = "DELETE FROM $this->table_name WHERE id = ?";
		$stmt = $this->db->prepare($query);
		$param = array($id);
		try{
			$stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	/**
	 * データをすべて削除する
	 * @return bool true: 成功 false: 削除に失敗
	 */
	public function truncate(){
		return $this->db->truncate();
	}

}
