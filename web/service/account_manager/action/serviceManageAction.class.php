<?php

require_once "../config.inc.php";

class serviceManageAction extends Action{
	public function getPreFilter(){
		return array('loadUser');
	}
	public function initialize(){
		$this->service_logic = $this->getLogic("Service");
	}
	public function execute(){
		$service_list = $this->service_logic->getList();
		$this->request->setAttribute('service_list',$service_list);
	}
}
