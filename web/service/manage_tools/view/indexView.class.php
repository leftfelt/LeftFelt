<?php

require_once('../config.inc.php');

class indexView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer("Smarty");
		$renderer->setTemplate('manage.html');
		return $renderer;
	
	}
}
