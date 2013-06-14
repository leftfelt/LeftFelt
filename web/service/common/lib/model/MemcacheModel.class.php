<?php

require_once dirname(__FILE__)."/../core/Model.class.php";
require_once dirname(__FILE__)."/../core/Cache.class.php";

/**
 * memcacheを扱うモデル
 */
class MemcacheModel extends CacheModel{
	protected $memcache;
	private $flag;

	public function initialize()
	{
		$this->flag = 0;
	}

	/**
	 * 接続する
	 * @param array $conf
	 */
	public function connect($conf){
		$this->memcache = new Memcache;
		$this->memcache->connect(
				$conf['host'],
				$conf['port']
			);
	}

	/**
	 * 値を取得する
	 * @param  $key string or array
	 * @return mixed
	 */
	public function get($key){
		return $this->memcache->get($key);
	}

	/**
	 * 値を設定する
	 * @param string $key
	 * @param mixed $value
	 * @param int $expire
	 */
	public function set($key, $value, $expire=0){
		$this->memcache->set($key, $value, $this->flag, $expire);
	}

	/**
	 * 値を削除する
	 * @param string $key
	 */
	public function delete($key){
		$this->memcache->delete($key);
	}
}

