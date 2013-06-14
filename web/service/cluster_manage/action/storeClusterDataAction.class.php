<?php

require_once dirname(__FILE__)."/../config.inc.php";

class storeClusterDataAction extends Action{
	public function initialize(){
	}
	public function getPreFilter(){
		return array();
	}
	public function getPostFilter(){
		return array();
	}
	public function execute(){
		$name = $this->request->getParametor('name');
		$keys = $this->request->getParametor('keys',array());
		$values = $this->request->getParametor('values',array());
		try{
			$redis = $this->getModel('Redis');
			$redis->connect($this->config['redis-main']);

			//keyの更新
			foreach($keys as $key){
				$redis->sadd($this->make_keys_key($name),$key);
			}

			//keyの数を取得
			$keys_num = $redis->scard($this->make_keys_key($name));
			if($keys_num === count($values)){
				$redis->rpush($this->make_values_key($name),$values);
			}
			$result = true;
		}catch(Exception $e){
			$result = false;
		}
		
		$this->request->setAttribute('result',$result);
	}

	private function make_keys_key($name){
		return $name.':keys';
	}

	private function make_values_key($name){
		return $name.':values';
	}
}
