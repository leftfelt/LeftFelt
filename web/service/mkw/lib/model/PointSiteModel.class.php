<?php

require_once dirname(__FILE__)."/../core/Model.class.php";

class PointSiteModel extends Model{
	private $table_name = 'point_site_table';
	private $db;

	public function initialize(){
	}

	public function connect($conf){
		$dbmanager = $this->getModel('dbManager');
		$this->db = $dbmanager->connect($conf);
		$this->db->setTableName($this->table_name);
	}

	public function get($name){
		$query = "SELECT * FROM $this->table_name WHERE name = ? LIMIT 1";
		$stmt = $this->db->prepare($query);
		$param = array($name);
		try{
			$stmt->execute($param);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		if($result !== false) array_shift($result);
		else $result = array();
		return new PointSite($result);
	}

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

		$point_site_list = array();
		foreach($result as $point_site){
			array_shift($point_site);
			$point_site_list[] = new PointSite($point_site);
		}

		return new PointSiteList($point_site_list);
	}

	public function set(PointSite $point_site){
		$param = array_values($point_site->toArray());
		$colum = array();
		foreach($param as $value){
			$colum[] = '?';
		}
		$query = "INSERT INTO $this->table_name("
				.implode(',',array_keys($point_site->toArray()))
				.") VALUES(".implode(',',$colum).")";
		$stmt = $this->db->prepare($query);
		try{
			$result = $stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return $result;
	}

	public function delete($name){
		$query = "DELETE FROM  $this->table_name WHERE name = ?";
		$stmt = $this->db->prepare($query);
		$param = array($name);
		try{
			$result = $stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
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

class ArgumentsValidator{
	private $map;
	public function __construct(array $map){
		$this->map = $map;
	}
	public function require_key($key){
		if(array_key_exists($key,$this->map)) return true;
		throw new InvalidArgumentException("$key is not exists");
	}
}

class PointSite{
	private $point_site;
	public function __construct(array $point_site){
		$this->point_site = $point_site;
		$key_list = array(
			'name',
			'description',
			'rate',
			'to_webmoney',
			'min_pay',
			'exchange_place',
			'how_to_get_point',
			'register_info',
			'register_link',
			'banner_link',
		);
		$validator = new ArgumentsValidator($point_site);
		foreach($key_list as $key){
			$validator->require_key($key);
		}
	}
	public function getName(){
		return $this->point_site['name'];
	}
	public function getDescription(){
		return $this->point_site['description'];
	}
	public function getRate(){
		return $this->point_site['rate'];
	}
	public function getToWebMoney(){
		return $this->point_site['to_webmoney'];
	}
	public function getMinPay(){
		return $this->point_site['min_pay'];
	}
	public function getExchangePlace(){
		return $this->point_site['exchange_place'];
	}
	public function getHowToGetPoint(){
		return $this->point_site['how_to_get_point'];
	}
	public function getRegisterInfo(){
		return $this->point_site['register_info'];
	}
	public function getRegisterLink(){
		return $this->point_site['register_link'];
	}
	public function getBannerLink(){
		return $this->point_site['banner_link'];
	}
	public function toArray(){
		return array(
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'rate' => $this->getRate(),
			'to_webmoney' => $this->getToWebMoney(),
			'min_pay' => $this->getMinPay(),
			'exchange_place' => $this->getExchangePlace(),
			'how_to_get_point' => $this->getHowToGetPoint(),
			'register_info' => $this->getRegisterInfo(),
			'register_link' => $this->getRegisterLink(),
			'banner_link' => $this->getBannerLink(),
		);
	}
}

class PointSiteList{
	private $point_site_list;
	public function __construct(array $point_site_list){
		$this->point_site_list = $point_site_list;
	}

	public function toArray(){
		$array_point_site = array();
		foreach($this->point_site_list as $point_site){
			$array_point_site[] = $point_site->toArray();
		}
		return $array_point_site;
	}
}
