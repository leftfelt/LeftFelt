<?php

require_once dirname(__FILE__)."/../config.inc.php";

class addPointSiteView extends View{
	public function initialize(){
		$this->renderer = $this->getRenderer('Json');
	}
	public function execute(){
		$this->renderer->setAttribute(
			'result',
			$this->request->getAttribute('result',false)
		);
		return $this->renderer;
	}
}
