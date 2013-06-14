<?php

require_once dirname(__FILE__)."/../config.inc.php";

class getPointSiteListView extends View{
	public function initialize(){
		$this->renderer = $this->getRenderer('Json');
	}
	public function execute(){
		$this->renderer->setAttribute(
			'point_site_list',
			$this->request->getAttribute('point_site_list')
		);
		return $this->renderer;
	}
}
