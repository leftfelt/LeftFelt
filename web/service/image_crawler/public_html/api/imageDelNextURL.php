<?php

require_once('../../config.inc.php');
/*
 *画像収集用クロール先URLを削除する
 */
class imageSetNextURL extends Controller{
	private $key = 'image_crawler';
	public function execute(){

		$redis = new Redis;
		$redis->connect('127.0.0.1',6379);
		$status = 'failure';

		//URLを削除
		$redis->del($this->key);
		$status = 'success';

		$redis->close();
		
		$renderer = $this->getRenderer('Smarty');
		$renderer->setAttribute('status',$status);
		$renderer->setTemplate('api/imageGetNextURL.html');
		return $renderer;
	}
}

$controller = new imageSetNextURL($conf);
$controller->dispatch();

	
