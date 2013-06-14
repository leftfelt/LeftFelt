<?php

require_once "config.inc.php";

$controller = new Controller($conf);

class ServiceLogicTest extends PHPUnit_Framework_TestCase{
	public function setUp(){
		global $controller;
		$this->controller = $controller;
		//テスト用のサービスを追加
		$this->service_logic = $this->controller->getLogic('Service');
	}

	public function test_サービスが追加削除できることを確認(){
		$service_name = 'sample_service';

		$result = $this->service_logic->addService($service_name);
		$this->assertTrue($result);
		
		$result = $this->service_logic->deleteService($service_name);
		$this->assertTrue($result);
	}
}
