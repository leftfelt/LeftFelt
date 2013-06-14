<?php

require_once dirname(__FILE__)."/../config.inc.php";

class getPointSiteListAction extends Action{
	public function initialize(){
		$this->point_site_model = $this->getModel('PointSite');
		$this->point_site_model->connect($this->config['db-main']);
		$this->memcache = $this->getModel('Memcache');
		$this->memcache->connect($this->config['memcache-main']);
	}
	public function getPreFilter(){
		return array();
	}
	public function getPostFilter(){
		return array();
	}
	public function execute(){
		$point_site_list = $this->memcache->call(
				new Method(
					array($this->point_site_model,'getList'),
					$this->request->getParametor('limit',null),
					$this->request->getParametor('offset',null)
				),
				$this->config['cache_call_short']
			);
		$this->request->setAttribute(
			'point_site_list',
			$point_site_list->toArray()
		);
	}
}
