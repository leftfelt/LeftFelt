<?php

require_once dirname(__FILE__)."/../config.inc.php";

class getServiceListView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer("Json");
		$renderer->setAttribute('service_list',$this->request->getAttribute('service_list'));

		return $renderer;
	}
}
