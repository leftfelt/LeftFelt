<?php

require_once "config.inc.php";

$controller = new Controller($conf);

class ServiceModelTest extends PHPUnit_Framework_TestCase{
	public function setUp(){
		global $controller;
		$this->controller = $controller;
		$this->service_model = $this->controller->getModel('Service');
	}

	public function test_connect(){
		try{
			$this->service_model->connect($this->controller->config['db-main']);
		}catch(Exception $e){
			$this->fail($e->__toString());
		}
	}

	public function test_サービスの追加取得削除野確認(){
		$this->service_model->connect($this->controller->config['db-main']);
		$this->service_model->truncate();
		$service_id = 'sample_service';
		$user_table_name = 'sample_user_table_name';
		
		$count = count($this->service_model->getList());

		$result = $this->service_model->set($service_id,$user_table_name);
		$this->assertTrue($result);

		$this->assertSame($count+1,count($this->service_model->getList()));
		
		$result = $this->service_model->get($service_id);
		$this->assertSame($result,$user_table_name);

		$result = $this->service_model->delete($service_id);
		$this->assertTrue($result);
	}
}

