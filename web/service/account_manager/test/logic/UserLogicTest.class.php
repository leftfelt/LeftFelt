<?php

require_once "config.inc.php";

$controller = new Controller($conf);

class UserLogicTest extends PHPUnit_Framework_TestCase{
	public function setUp(){
		global $controller;
		$this->controller = $controller;
		$this->user_logic = $this->controller->getLogic('User');
		$this->service_id = 'sample_service';
		//テスト用のサービスを追加
		$this->service_logic = $this->controller->getLogic('Service');
		$this->service_logic->deleteService($this->service_id);
		$this->service_logic->addService($this->service_id);
	}
	
	public function test_指定サービスからユーザの追加取得削除(){
		$user = new User(
			'hoge',
			'fuga',
			'1'
		);
		$result = $this->user_logic->addUser($this->service_id,$user);
		$this->assertTrue($result);

		$result = $this->user_logic->getUser($this->service_id,$user->getId());
		$this->assertInstanceOf('User',$result);
		$this->assertEquals($result,$user);

		$result = $this->user_logic->isUserExists($this->service_id,$user->getId());
		$this->assertTrue($result);
		
		$result = $this->user_logic->deleteUser($this->service_id,$user->getId());
		$this->assertTrue($result);

		$result = $this->user_logic->isUserExists($this->service_id,$user->getId());
		$this->assertFalse($result);
		

	}
}
