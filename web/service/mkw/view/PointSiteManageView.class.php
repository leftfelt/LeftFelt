<?php

require_once dirname(__FILE__)."/../config.inc.php";

class PointSiteManageView extends View{
	public function initialize(){
		$this->renderer = $this->getRenderer('Manage');
		$this->renderer->setTemplate('point_site_manage.html');
	}
	public function execute(){
		$this->renderer->setJsList(array(
			'common/js/common.js',
			'js/point_site_manage.js',
		));
		$this->renderer->setCssList(array(
			'common/css/common.css',
		));

		return $this->renderer;
	}
}
