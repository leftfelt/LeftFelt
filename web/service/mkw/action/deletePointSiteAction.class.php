<?php

require_once dirname(__FILE__)."/../config.inc.php";

class deletePointSiteAction extends Action{
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
		$result = $this->point_site_model->delete(
				$this->request->getParametor('name')
			);
		$this->request->setAttribute('result',$result);
	}
}
