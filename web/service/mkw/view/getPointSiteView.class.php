<?php

require_once dirname(__FILE__)."/../config.inc.php";

class getPointSiteView extends View{
	public function initialize(){
		$this->renderer = $this->getRenderer('Jsonp');
	}
	public function execute(){
		$this->renderer->setAttribute(
			'GetPointSiteResult',
			$this->request->getAttribute('point_site')
		);
		return $this->renderer;
	}
}
