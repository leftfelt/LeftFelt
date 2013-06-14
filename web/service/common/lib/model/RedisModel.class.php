<?php

require_once dirname(__FILE__)."/../core/Model.class.php";
require_once dirname(__FILE__)."/../core/Cache.class.php";

/**
 * redisを扱うモデル
 */
class RedisModel extends CacheModel{
	protected $redis;

	public function initialize(){
	}

	/**
	 * 既存のRedisクラスのメソッドを使う
	 */
	public function __call($method, $args){
		return call_user_func_array(array($this->redis,$method),$args);
	}

	/**
	 * 接続する
	 * @param array $conf
	 */
	public function connect($conf){
		$this->redis = new Redis;
		$this->redis->connect(
				$conf['host'],
				$conf['port']
			);
	}

	/**
	 * 値を取得する
	 * @param  $key string or array
	 * @return string or array
	 */
	public function get($key){
		return $this->redis->get($key);
	}

	/**
	 * 値を設定する
	 * @param string $key
	 * @param mixed $value
	 * @param int $expire
	 */
	public function set($key, $value, $expire=0){
		$this->redis->set($key, $value);
		if($expire !== 0){
			$this->redis->expire($key, $expire);
		}
	}

	/**
	 * 値を削除する
	 * @param string $key
	 */
	public function delete($key){
		$this->redis->delete($key);
	}
}


