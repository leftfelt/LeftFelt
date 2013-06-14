<?php

require_once dirname(__FILE__)."/../core/Model.class.php";

class imageManagerModel extends Model{

	private $db;

	public function initialize(){
	}

	public function connect($conf){
		$dbmanager = $this->getModel("dbManager");
		$this->db = $dbmanager->connect($conf);
	}
	
	public function get($id){
		$query = "SELECT id,url FROM image_url WHERE id = ? LIMIT 1";
		$stmt = $this->db->prepare($query);
		$param = array($id);
		try{
			$stmt->execute($param);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		return $result;
	}

	public function getPage($page,$view,$order='id',$sort='ASC'){
		$query = "SELECT id,url FROM image_url ORDER BY $order $sort LIMIT :limit OFFSET :offset";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':limit',$view,PDO::PARAM_INT);
		$stmt->bindValue(':offset',($page-1)*$view,PDO::PARAM_INT);
		try{
			$stmt->execute();
			$result = $stmt->fetchAll();
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		return $result;
	}

	//最大数を返す
	public function getCount(){
		$query = "SELECT COUNT(*) AS count FROM image_url";
		$stmt = $this->db->prepare($query);
		try{
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		return $result['count'];
	}

	public function set($url){
		$query = "INSERT INTO image_url(url) VALUES(?)";
		$stmt = $this->db->prepare($query);
		$param = array($url);
		try{
			$stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function delete($id){
		$query = "DELETE FROM image_url WHERE id = ?";
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
}
