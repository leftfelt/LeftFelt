<?php

//画像へのURLを一つDBに登録する

require_once('../../config.inc.php');

class addImageURL extends Controller{
	public function execute(){
		$image_url = $this->request->getParametor('url');
		if(!isset($image_url)){
			exit;
		}
		
		$image_manager = $this->getModel('imageManager');
		$image_manager->connect($this->config['db-main']);
		$image_manager->set($image_url);

		return $this->getRenderer("DoNothing");
	}
}

$controller = new addImageURL($conf);
$controller->dispatch();
