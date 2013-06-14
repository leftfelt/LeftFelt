<?php

require_once('../../config.inc.php');

/*
 *画像収集用次回クロール先URLを登録する
 */

class imageSetNextURL extends Controller{
	private $key = 'image_crawler';
	
	public function execute(){
		
		$redis = new Redis;
		$redis->connect('127.0.0.1',6379);
		
		$status = 'failure';
		
		$url = $this->request->getParametor('url');
		
		if(isset($url)){
			//URLをセット
			$redis->sadd($this->key,$url);
			$status = 'success';
		}
		
		$redis->close();
		
		$renderer = $this->getRenderer('Smarty');
		$renderer->setAttribute('status',$status);
		$renderer->setTemplate('api/imageSetNextURL.html');
		return $renderer;
	}
}

$controller = new imageSetNextURL($conf);
$controller->dispatch();

