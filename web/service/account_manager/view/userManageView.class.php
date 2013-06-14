<?php

require_once dirname(__FILE__)."/../config.inc.php";

class userManageView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer("Manage");
		$renderer->setTemplate("userManage.html");

		$renderer->setJsList(array(
			'common/js/common.js',
			'js/userManage.js',
		));

		$renderer->setCssList(array(
			'css/userManage.css',	
		));

		return $renderer;
	}
}
