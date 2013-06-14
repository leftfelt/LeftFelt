<?php

require_once "config.inc.php";

$controller = new Controller($conf);

class UserModelTest extends PHPUnit_Framework_TestCase{
	
	public function setUp(){
		global $controller;
		$this->controller = $controller;
		$this->user_model = $this->controller->getModel('User');
		$table_name = 'sample_user_table';
		$this->user_model->setTableName($table_name);
	}
	
	public function test_connect(){
		try{
			$this->user_model->connect($this->controller->config['db-main']);
		}catch(Exception $e){
			$this->fail($e->__toString());
		}
	}

	public function test_サービスごとのユーザテーブルを作成削除の確認(){
		$this->user_model->connect($this->controller->config['db-main']);
		$result = $this->user_model->create();
		$this->assertTrue($result);
		$result = $this->user_model->drop();
		$this->assertTrue($result);
	}

	public function test_ユーザの追加取得削除の確認(){
		$this->user_model->connect($this->controller->config['db-main']);
		$result = $this->user_model->create();
		$user_id = 'hoge';
		$password = 'fuga';
		$permission_id = '1';

		$user = new User(
			$user_id,
			$password,
			$permission_id
		);

		$result = $this->user_model->set($user);
		$this->assertTrue($result);

		$result = $this->user_model->get($user_id);
		$this->assertInstanceOf('User',$result);
		$this->assertEquals($result,$user);

		$result = $this->user_model->delete($user_id);
		$this->assertTrue($result);

		$result = $this->user_model->drop();
	}
}
