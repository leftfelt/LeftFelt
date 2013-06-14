<?php

require_once dirname(__FILE__)."/../config.inc.php";

class PointSiteManageAction extends Action{
	public function initialize(){
		$this->point_site_model = $this->getModel('PointSite');
		$this->point_site_model->connect($this->config['db-main']);
	}
	public function getPreFilter(){
		return array();
	}
	public function getPostFilter(){
		return array();
	}
	public function execute(){
		$limit = $this->request->getParametor('limit');
		$offset = $this->request->getParametor('offset');
		$this->request->setAttribute(
			'point_site_list',
			$this->point_site_model->getList($limit,$offset)
		);
	}
}
