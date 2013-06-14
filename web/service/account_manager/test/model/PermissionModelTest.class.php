<?php

require_once "config.inc.php";

$controller = new Controller($conf);

class PermissionModelTest extends PHPUnit_Framework_TestCase{

	public function setUp(){
		global $controller;
		$this->controller = $controller;
		$this->permission_model = $this->controller->getModel('Permission');
	}

	public function test_connect(){
		try{
			$this->permission_model->connect($this->controller->config['db-main']);
		}catch(Exception $e){
			$this->fail($e->__toString());
		}

	}
	
	public function test_権限の追加取得削除野確認(){
		$this->permission_model->connect($this->controller->config['db-main']);
		$this->permission_model->truncate();
		$permission = 'sample_permission';

		$count = count($this->permission_model->getPermissionList());
		//追加
		$result = $this->permission_model->set($permission);
		$this->assertTrue($result);
		$list = $this->permission_model->getPermissionList();
		$this->assertSame($count+1,count($list));
		//取得
		$result = $this->permission_model->get($count+1);
		$this->assertSame($result,$permission);
		//削除
		$result = $this->permission_model->delete($count+1);
		$this->assertTrue($result);
		$list = $this->permission_model->getPermissionList();
		$this->assertSame($count,count($list));
		
	}
}
