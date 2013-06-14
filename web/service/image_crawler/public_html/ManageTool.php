<?php

require_once('../config.inc.php');

class MkwChangeTool extends Controller{
	public function execute(){
		//リンク管理用model
		$redis = $this->getModel('imageCrawlerRedis');
		$redis->connect($this->config['redis-main']);
		//画像管理用model
		$image_manager = $this->getModel('imageManager');
		$image_manager->connect($this->config['db-main']);
		
		$renderer = $this->getRenderer('Smarty');
		$renderer->setAttribute('link_count',$redis->getCount());
		$renderer->setAttribute('image_count',$image_manager->getCount());
		$renderer->setTemplate('ManageTool.html');
		return $renderer;
	}
}

$controller = new MkwChangeTool($conf);
$controller->dispatch();
