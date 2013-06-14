<?php

require_once('../config.inc.php');

class ImageManager extends Controller{
	public function execute(){
		$page = $this->request->getParametor('page',1);
		$order = $this->request->getParametor('order','id');
		$sort = $this->request->getParametor('sort','ASC');
		$view = 20;
		
		$image_manager = $this->getModel('imageManager');
		$image_manager->connect($this->config['db-main']);
		$image_list = $image_manager->getPage($page,$view,$order,$sort);
		
		$renderer = $this->getRenderer('Smarty');
		$renderer->setAttribute('page',$page);
		$renderer->setAttribute('order',$order);
		$renderer->setAttribute('sort',$sort);
		$renderer->setAttribute('image_list',$image_list);
		$renderer->setTemplate('imageManager.html');
		return $renderer;
	}
}

$controller = new ImageManager($conf);
$controller->dispatch();
