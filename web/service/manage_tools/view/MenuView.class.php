<?php

require_once('../config.inc.php');

class MenuView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer("Manage");
		$renderer->setTemplate('menu.html');
		$renderer->setAttribute('config',$this->config);
		$renderer->setJsList(array(
			'js/menu.js',
		));
		return $renderer;
	}
}
