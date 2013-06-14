<?php

require_once dirname(__FILE__)."/../config.inc.php";

class getPointSiteAction extends Action{
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
		try{
			$name = $this->request->getParametor('name');
			$point_site = $this->memcache->call(
				new Method(
					array($this->point_site_model,'get'),
					$name
				),
				$this->config['cache_call_short']
			);
			$result = $point_site->toArray();
		}catch(Exception $e){
			$result = array();
		}
		$this->request->setAttribute(
			'point_site',
			$result
		);
	}
}
