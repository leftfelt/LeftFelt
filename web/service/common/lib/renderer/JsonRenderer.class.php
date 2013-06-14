<?php

require_once dirname(__FILE__).'/SmartyRenderer.class.php';

//JSONデータ用のレンダラー

class JsonRenderer extends SmartyRenderer
{
	public function __construct(&$controller){
		parent::__construct($controller);
	}

	public function execute(){
		$this->setTemplate('common/json_renderer.html');
		$this->setAttribute(
			'json_data',
			json_encode($this->getAttributes())
		);
		parent::execute();
	}
}
