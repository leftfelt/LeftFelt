<?php

require_once "../config.inc.php";

class serviceManageView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer("Manage");
		$renderer->setTemplate('serviceManage.html');

		$renderer->setAttribute('service_list',$this->request->getAttribute('service_list'));

		$renderer->setJsList(array(
			'common/js/common.js',
			'js/serviceManage.js',	
		));

		return $renderer;
	}
}
