<?php

require_once dirname(__FILE__)."/../config.inc.php";

class loginView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer('Smarty');
		$renderer->setTemplate('login_form.html');
		$renderer->setAttribute('config',$this->config);
		$renderer->setAttribute('next_url',$this->request->getAttribute('next_url'));
		$renderer->setAttribute('service_id',$this->request->getAttribute('service_id'));
		return $renderer;
	}
}
