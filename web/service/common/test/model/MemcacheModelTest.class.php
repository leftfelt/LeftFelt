<?php

require_once "config.inc.php";

$controller = new Controller($conf);

class MemcacheModelTest extends PHPUnit_Framework_TestCase{
	public function setUp(){
		global $controller;
		$this->controller = $controller;
		$this->memcache_model = $this->controller->getModel('Memcache');
	}

	public function test_connect(){
		$this->memcache_model->connect($this->controller->config['memcache-main']);
	}

	public function test_取得設定削除(){
		try{
			$this->memcache_model->connect($this->controller->config['memcache-main']);
			$sample_key = 'sample_key';
			$sample_data = 'sample_data';
			$this->memcache_model->set($sample_key,$sample_data);

			$result = $this->memcache_model->get($sample_key);
			$this->assertSame($sample_data,$result);

			$this->memcache_model->delete($sample_key);
			$result = $this->memcache_model->get($sample_key);
			$this->assertFalse($result);
		}catch(Exception $e){
			$this->fail($e->__toString());
		}
	}

	public function test_関数結果キャッシュの確認(){
		$this->memcache_model->connect($this->controller->config['memcache-main']);
		$method = new Method(array($this,"sample_method"),'test');
		$this->memcache_model->call_delete($method);

		//キャッシュされているかの確認
		$this->sample_result = 'sample_text';
		$ans = $this->sample_result.'test';
		$result = $this->memcache_model->call($method);
		$this->assertSame($result, $this->sample_result.'test');
		
		$this->sample_result = 'sample';
		$result2 = $this->memcache_model->call($method);
		$this->assertSame($result2,$ans);

		//キャッシュ削除の確認
		$this->memcache_model->call_delete($method);
		$result2 = $this->memcache_model->call($method);
		$this->assertSame($result2,$this->sample_result.'test');
	}

	public function sample_method($text){
		return $this->sample_result.$text;
	}
}



