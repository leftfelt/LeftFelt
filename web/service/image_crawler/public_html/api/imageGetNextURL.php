<?php

require_once('../../config.inc.php');
/*
 *画像収集用クロール先URLを取得する
 */

class imageGetNextURL extends Controller{
	private $key = 'image_crawler';
	public function execute(){

		$redis = new Redis;
		$redis->connect('127.0.0.1',6379);
		$status = 'failure';
		
		//URLを取得
		$url = $redis->spop($this->key);
		if($url){
			//取得成功ならRedisから消す
			$redis->srem($this->key,$url);
			$status = 'success';
		}else{
			$url = '';
		}
		
		$redis->close();
		
		$renderer = $this->getRenderer('Smarty');
		$renderer->setAttribute('url',$url);
		$renderer->setAttribute('status',$status);
		$renderer->setTemplate('api/imageGetNextURL.html');
		return $renderer;
	}
}

$controller = new imageGetNextURL($conf);
$controller->dispatch();

