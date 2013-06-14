<?php

require_once('../config.inc.php');

class LinkManager extends Controller{
	public function execute(){
		$redis = $this->getModel('imageCrawlerRedis');
		$redis->connect($this->config['redis-main']);
		
		//URLを取得
		$link_list = $redis->getLinkList();
		
		$renderer = $this->getRenderer('Smarty');
		$renderer->setAttribute('link_list',$link_list);
		$renderer->setTemplate('linkManager.html');
		return $renderer;
	}
}

$controller = new LinkManager($conf);
$controller->dispatch();

