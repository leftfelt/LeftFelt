<?php

require_once dirname(__FILE__).'/../core/Logic.class.php';

// サービス管理用のLogic
//　名前空間使う必要あるかも

class ServiceLogic extends Logic
{
	const USER_TABLE_POSTFIX = '_user_table';
	
	public function initialize(){
		$this->service_model = $this->getModel('Service');
		$this->service_model->connect($this->config['db-main']);
		$this->user_model = $this->getModel('User');
		$this->user_model->connect($this->config['db-main']);
	}

	/**
	 * サービスを追加する（ユーザテーブルも作成）
	 * @param string $service_id
	 * @return bool true:成功 false:失敗 
	 */
	public function addService($service_id){
		$user_table_name = $this->getUserTableName($service_id);
		$result = $this->service_model->set(
			$service_id,
			$user_table_name
		);
		$this->user_model->setTableName($user_table_name);
		$this->user_model->create();
		return $result;
	}

	/**
	 * サービスを削除する（ユーザ情報も削除する）
	 * @param string $service_id
	 * @return bool true:成功 false:失敗 
	 */
	public function deleteService($service_id){
		$user_table_name = $this->getUserTableName($service_id);
		$result = $this->service_model->delete($service_id);
		$this->user_model->setTableName($user_table_name);
		$this->user_model->drop();
		return $result;
	}

	/**
	 * サービスリストを取得する
	 * @param int limit
	 * @param int offset
	 * @return array 
	 */
	public function getList($limit=null,$offset=null){
		$service_list = $this->service_model->getList($limit,$offset);
		return $service_list;	
	}

	private function getUserTableName($service_id){
		$user_table_name = $service_id.self::USER_TABLE_POSTFIX;
		return $user_table_name;
	}
}

