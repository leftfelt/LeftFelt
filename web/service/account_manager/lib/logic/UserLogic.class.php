<?php

require_once dirname(__FILE__).'/../core/Logic.class.php';

// ユーザ管理用のLogic
//　名前空間使う必要あるかも

class UserLogic extends Logic
{
	public function initialize(){
		$this->service_model = $this->getModel('Service');
		$this->service_model->connect($this->config['db-main']);
		$this->user_model = $this->getModel('User');
		$this->user_model->connect($this->config['db-main']);
	}

	/**
	 * 指定サービスのユーザを取得する
	 * @param string $service_id サービスID
	 * @param string $user_id ユーザID
	 * @return User ユーザ情報 データクラス化する
	 **/
	public function getUser($service_id,$user_id){
		$this->setUserTableName($service_id);
		$user = $this->user_model->get($user_id);
		return $user;
	}

	/*
	* 指定サービスのユーザリストを取得する
	 * @param string $service_id サービスID
	 * @param string $page ページ数
	 * @param string $view_count 表示する数
	 * @param bool $ignore_deleted 削除済フラグを無視する
	 * @return User ユーザ情報 データクラス化する
	 **/
	public function getUserList($service_id,$page,$view_count,$ignore_deleted=false){
		$this->setUserTableName($service_id);
		$limit = $view_count;
		$offset = ($page-1) * $view_count;
		$user_list = $this->user_model->getList($limit,$offset,$ignore_deleted);
		return $user_list;
	}

	/**
	 * 指定サービスのユーザを追加する
	 * @param string $service_id サービスID
	 * @param User $user ユーザ情報
	 * @return bool true:成功 false:失敗
	 **/
	public function addUser($service_id,$user){
		$this->setUserTableName($service_id);
		return $this->user_model->set($user);
	}

	/**
	 * 指定サービスのユーザを削除する
	 * @param string $service_id サービスID
	 * @param string $user_id ユーザID
	 * @return bool true:成功 false:失敗
	 **/
	public function deleteUser($service_id,$user_id){
		$this->setUserTableName($service_id);
		return $this->user_model->delete($user_id);
	}

	/**
	 * 指定サービスに有効なユーザが存在するか
	 * @param string $service_id サービスID
	 * @param string $user_id ユーザID
	 * @return bool true:いる false:いない
	 **/
	public function isUserExists($service_id,$user_id){
		$user = $this->getUser($service_id,$user_id);
		return ($user != null && !$user->isDeleted());
	}

	/**
	 * ユーザ認証を行う
	 * @param string $service_id サービスID
	 * @param string $user_id ユーザID
	 * @param string $password 暗号化済のパスワード
	 * @return string ユーザ識別用文字列 認証に失敗するとnull
	 **/
	public function userAuth($service_id, $user_id, $password){
		if($service_id === "" || $user_id === "" || $password === "") return null;

		$user = $this->getUser($service_id, $user_id);
		if(is_null($user)) return null;
		if($user_id === $user->getId() && $password === $user->getPassword()){
			$result = array(
				$service_id,
				$user->getId(),
				$user->getPassword(),
				$user->getPermissionId(),
			);
			return sha1(implode("_",$result)); 	
		}

		return null;
	}

	private function setUserTableName($service_id){
		$table_name = $this->service_model->get($service_id);
		$this->user_model->setTableName($table_name);
	}
}
