<?php

require_once "config.inc.php";

$controller = new Controller($conf);

class PointSiteModelTest extends PHPUnit_Framework_TestCase{

	public function setUp(){
		global $controller;
		$this->controller = $controller;
		$this->point_site_model = $this->controller->getModel('PointSite');
	}

	public function test_connect(){
		try{
			$this->point_site_model->connect($this->controller->config['db-main']);
		}catch(Exception $e){
			$this->fail($e->__toString());
		}
	}

	public function test_ポイントサイトの追加取得削除確認(){
		$this->point_site_model->connect($this->controller->config['db-main']);
		$this->point_site_model->truncate();
		$sample_point_site = new PointSite(array(
			'name' => 'sample_point_site',
			'description' => 'sample_description',
			'rate' => 'sample_rate',
			'to_webmoney' => 'sample_to_webmoney',
			'min_pay' => 'sample_min_pay',
			'exchange_place' => 'sample_exchange_place',
			'how_to_get_point' => 'sample_how_to_get_point',
			'register_info' => 'sample_register_info',
			'register_link' => 'sample_register_link',
			'banner_link' => 'sample_banner_link',
		));
		$result = $this->point_site_model->set($sample_point_site);
		$this->assertTrue($result);

		$result = $this->point_site_model->get($sample_point_site->getName());
		$this->assertEquals($result,$sample_point_site);

		$result = $this->point_site_model->getList();
		$point_site_list = new PointSiteList(array($sample_point_site));
		$this->assertEquals(
			$result->toArray(),
			$point_site_list->toArray()
		);

		$result = $this->point_site_model->delete($sample_point_site->getName());
		$this->assertTrue($result);
	}
}	
