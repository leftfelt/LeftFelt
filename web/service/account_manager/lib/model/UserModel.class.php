<?php

require_once dirname(__FILE__)."/../core/Model.class.php";

class UserModel extends Model{
	private $table_name = null;
	private $permission_table = "permission_table";
	private $db;

	public function initialize(){
	}

	public function connect($conf){
		$dbmanager = $this->getModel('dbManager');
		$this->db = $dbmanager->connect($conf);
	}

	//サービスごとにテーブルが違うので使用テーブルは外部に任せる
	public function setTableName($table_name){
		$this->table_name = $table_name;
	}

	public function create(){
		if(is_null($this->table_name)) throw new Exception('テーブル名が設定されていません');
		$query = "CREATE TABLE IF NOT EXISTS $this->table_name(".
			"id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,".
			"user_id VARCHAR(255) NOT NULL UNIQUE,".
			"password VARCHAR(255) NOT NULL,".
			"permission_id VARCHAR(255) NOT NULL,".
			"deleted BOOLEAN DEFAULT false".
		")ENGINE=InnoDB DEFAULT CHARACTER SET 'utf8'";
		$stmt = $this->db->prepare($query);
		try{
			$result = $stmt->execute();
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return $result;
	}

	public function drop(){
		if(is_null($this->table_name)) throw new Exception('テーブル名が設定されていません');
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

	/**
	 * 登録されたユーザを取得する
	 * @param string $user ユーザID
	 * @param bool $ignore_deleted 削除済フラグを無視する
	 * @return User ユーザ情報
	 */
	public function get($user_id,$ignore_deleted = false){
		if(is_null($this->table_name)) throw new Exception('テーブル名が設定されていません');
		$query = "SELECT user_id,password,permission_id,deleted FROM $this->table_name WHERE user_id = ?";
		
		if(!$ignore_deleted){
			//削除済フラグを無視しない
			$query .= " AND deleted = 0";
		}
		
		$stmt = $this->db->prepare($query);
		$param = array($user_id);
		try{
			$stmt->execute($param);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		//レコードが存在しないならnull
		if($result===false) return null;
		
		$user = new User(
			$result['user_id'],
			$result['password'],
			$result['permission_id'],
			$result['deleted']
		);
		
		if(!$user->isDeleted() || $ignore_deleted){
			return $user;
		}
		return null;
	}

	/**
	 * 登録されたユーザリストを取得する
	 * @param $limit
	 * @param $offset
	 * @param bool $ignore_deleted 削除済フラグを無視する
	 * @return UserList ユーザ情報リスト
	 */
	public function getList($limit,$offset,$ignore_deleted = false){
		if(is_null($this->table_name)) throw new Exception('テーブル名が設定されていません');
		$query = "SELECT user_id,password,permission_id,deleted FROM $this->table_name";
		
		if(!$ignore_deleted){
			//削除済フラグを無視しない
			$query .= " WHERE deleted = 0";
		}
		$query .= " LIMIT $limit OFFSET $offset";
		
		$stmt = $this->db->prepare($query);
		try{
			$stmt->execute();
			$result = $stmt->fetchAll();
		}catch(Exception $e){
			echo $e->getMessage();
			return array();
		}
		//レコードが存在しないならnull
		if($result===false) return array();
		$user_list = array();
		foreach ($result as $user_record) {
			$user = new User(
				$user_record['user_id'],
				$user_record['password'],
				$user_record['permission_id'],
				$user_record['deleted']
			);
			if(!$user->isDeleted() || $ignore_deleted){
				$user_list[] = $user;
			}
		}

		return new UserList($user_list);
	}
	/**
	 * ユーザを登録する
	 * @param User $user ユーザ情報
	 * @return bool true: 成功 false: 登録に失敗
	 */
	public function set($user){
		if(is_null($this->table_name)) throw new Exception('テーブル名が設定されていません');
		$query = "INSERT INTO $this->table_name(user_id,password,permission_id) VALUES(?,?,?)";
		$stmt = $this->db->prepare($query);
		$param = array(
				$user->getId(),
				$user->getPassword(),
				$user->getPermissionId(),
			);
		try{
			$result = $stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return $result;
	}

	/**
	 * ユーザーを削除する
	 * @param string $id
	 * @return bool true: 成功 false: 削除に失敗
	 */
	public function delete($user_id){
		if(is_null($this->table_name)) throw new Exception('テーブル名が設定されていません');
		$query = "DELETE FROM $this->table_name WHERE user_id = ?";
		$stmt = $this->db->prepare($query);
		$param = array($user_id);
		try{
			$result = $stmt->execute($param);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
		return $result;
	}
}

class User{
	private $user_id;
	private $password;
	private $permission_id;
	private $deleted;

	public function __construct($user_id,$password,$permission_id,$deleted = 0){
		$this->user_id = $user_id;
		$this->password = $password;
		$this->permission_id = $permission_id;
		$this->deleted = $deleted;
	}
	
	/**
	 * IDを取得する
	 * @return string 
	 **/
	public function getId(){
		return (string)$this->user_id;
	}
	
	/**
	 * パスワードを取得する
	 * @return string SHA1で暗号化済のパスワード
	 **/
	public function getPassword(){
		return (string)$this->password;
	}

	/**
	 * 権限IDを取得する
	 * @return ID 
	 **/
	public function getPermissionId(){
		return (int)$this->permission_id;
	}

	/**
	 * 削除済か
	 * @return bool true:削除済 false:削除前
	 **/
	public function isDeleted(){
		return ( ((int)$this->deleted) === 1 );
	}

	/**
	 * 配列として取得する
	 * @return array 
	 **/
	public function toArray(){
		return array(
				'id'			=> $this->getId(),
				'password'		=> $this->getPassword(),
				'permission_id' => $this->getPermissionId(),
				'deleted'		=> $this->isDeleted(),
			);
	}
}

class UserList{
	private $user_list;

	public function __construct(array $user_list){
		$this->user_list = $user_list;
	}

	/**
	 * 配列として取得する
	 * @return array
	 **/
	public function toArray(){
		$array_user_list = array();
		foreach ($this->user_list as $user) {
			$array_user_list[] = $user->toArray();
		}
		return $array_user_list;
	}
}
