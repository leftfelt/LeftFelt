<?php

require_once dirname(__FILE__)."/../config.inc.php";

class addPointSiteAction extends Action{
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
		$point_site = new PointSite(array(
			'name' => $this->request->getParametor('name'),
			'description' => $this->request->getParametor('description'),
			'rate' => $this->request->getParametor('rate'),
			'to_webmoney' => $this->request->getParametor('to_webmoney'),
			'min_pay' => $this->request->getParametor('min_pay'),
			'exchange_place' => $this->request->getParametor('exchange_place'),
			'how_to_get_point' => $this->request->getParametor('how_to_get_point'),
			'register_info' => $this->request->getParametor('register_info'),
			'register_link' => $this->request->getParametor('register_link'),
			'banner_link' => $this->request->getParametor('banner_link')
	));
		$result = $this->point_site_model->set($point_site);
		$this->request->setAttribute('result',$result);
	}
}
