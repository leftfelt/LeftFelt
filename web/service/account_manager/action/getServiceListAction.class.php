<?php

require_once dirname(__FILE__)."/../config.inc.php";

class getServiceListAction extends Action{
	public function initialize(){
		$this->service_logic = $this->getLogic("Service");
	}
	public function execute(){
		$limit = $this->request->getParametor('limit');
		$offset = $this->request->getParametor('offset');
		$service_list = $this->service_logic->getList($limit,$offset);
		$this->request->setAttribute('service_list',$service_list);
	}
}
