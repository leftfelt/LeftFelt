<?php

require_once dirname(__FILE__)."/../config.inc.php";

class deleteUserAction extends Action{
	public function initialize(){
		$this->user_logic = $this->getLogic("User");
	}
	public function execute(){
		$service_id = $this->request->getParametor('service_id',"");
		$user_id = $this->request->getParametor('user_id',"");
		try{
			$result = $this->user_logic->deleteUser($service_id,$user_id);
		}catch(Exception $e){
			$result = false;
		}
		$this->request->setAttribute('result',$result);
	}
}
