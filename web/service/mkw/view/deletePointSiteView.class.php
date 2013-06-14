<?php

require_once dirname(__FILE__)."/../config.inc.php";

class deletePointSiteView extends View{
	public function initialize(){
		$this->renderer = $this->getRenderer('Json');
	}
	public function execute(){
		$this->renderer->setAttribute(
			'result',
			$this->request->getAttribute('resut')
		);
		return $this->renderer;
	}
}
