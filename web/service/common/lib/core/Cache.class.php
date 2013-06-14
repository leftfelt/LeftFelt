<?php

require_once dirname(__FILE__)."/Model.class.php";
require_once dirname(__FILE__)."/Method.class.php";
/**
 * キャッシュを管理するModel
 */
interface Cache
{
	/**
	 * キーから値を取得する
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);
	/**
	 * キーに値を割り当てる
	 * @param string $key
	 * @param mixed $value
	 * @param int $expire
	 */
	public function set($key,$value,$expire);
	/**
	 * キーに割り当てられている値を削除
	 * @param string or Method  $key
	 */
	public function delete($key);
	/**
	 * 関数のコールバック結果をキャッシュする
	 * @param Method $method
	 * @param int $expire
	 * @return mixed
	 */
	public function call(Method $method, $expire);
	/**
	 * 関数コールバック結果のキャッシュを削除する
	 * @param Method $method
	 */
	public function call_delete(Method $method);
}

/**
 * 関数結果キャッシュを扱うためのキャッシュクラス 
 */
abstract class CacheModel extends Model implements Cache{
	
	/**
	 * 関数のコールバック結果をキャッシュする
	 * @param Method $method
	 * @param int $expire
	 * @return mixed
	 */
	public function call(Method $method,$expire=0){
		$key = $method->createKey();
		$result = $this->get($key);
		if(!$result){
			//キャッシュされていない
			$result = $method->call();
			$this->set($key,$result,$expire);
		}

		return $result;

	}
	/**
	 * 関数コールバック結果のキャッシュを削除する
	 * @param Method $method
	 */
	public function call_delete(Method $method){
		$key = $method->createKey();
		$this->delete($key);
	}
}

