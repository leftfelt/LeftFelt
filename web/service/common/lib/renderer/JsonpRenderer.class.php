<?php

require_once dirname(__FILE__).'/SmartyRenderer.class.php';

//JSONデータ用のレンダラー

class JsonpRenderer extends SmartyRenderer
{
	public function __construct(&$controller){
		parent::__construct($controller);
	}

	public function execute(){
		$this->setTemplate('common/jsonp_renderer.html');
		$jsonp_data = $this->getAttributes();
		$result = array();
		foreach ($jsonp_data as $function_name => $args) {
			$result[$function_name] = json_encode($args);
		}
		$this->setAttribute('jsonp_data',$result);
		parent::execute();
	}
}
