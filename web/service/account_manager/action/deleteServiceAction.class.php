<?php

require_once dirname(__FILE__)."/../config.inc.php";

class deleteServiceAction extends Action{
	public function initialize(){
		$this->service_logic = $this->getLogic("Service");
	}
	public function execute(){
		$service_name = $this->request->getParametor('id',"");
		if($service_name !== ""){
			$result = $this->service_logic->deleteService($service_name);
		}else{
			$result = false;
		}
		$this->request->setAttribute('result',$result);
	}
}
