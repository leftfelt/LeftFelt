<?php

require_once dirname(__FILE__)."/../config.inc.php";

class addServiceView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer("Json");
		$renderer->setAttribute('result',$this->request->getAttribute('result'));

		return $renderer;
	}
}
