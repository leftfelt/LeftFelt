<?php

require_once('../config.inc.php');

//管理ツールを開いたときに最初に開くページ

class mainController extends Controller{
	public function execute(){
		$renderer = $this->getRenderer('Manage');
		$renderer->setTemplate('main.html');
		return $renderer;
	}
}

$controller = new mainController($conf);
$controller->dispatch();
