<?php

require_once dirname(__FILE__)."/../core/Model.class.php";

class imageCrawlerRedisModel extends Model{
	private $redis;

	private $key = 'image_crawler';

	public function initialize(){
		$this->redis = new Redis();
	}

	//$confのhost,portに接続する	
	public function connect($conf){
		$this->redis->connect(
			$conf['host'],
			$conf['port']
		);	
	}
	
	//巡回先URLの総数を返す
	public function getCount(){
		return count($this->getLinkList());
	}

	//巡回先URLを追加する	
	public function setLink($url){
		$this->redis->sadd($this->key,$url);
	}

	//巡回先URLをランダムで取得し、リストから削除する
	public function getLink(){
		return $this->redis->spop($this->key);
	}

	//巡回先URLをランダムで削除する
	public function deleteLink(){
		$this->getLink();
	}
	
	//巡回先のURLリストを取得する
	public function getLinkList(){
		return $this->redis->smembers($this->key);
	}
	
	public function close(){
		$this->redis->close();
	}
}
