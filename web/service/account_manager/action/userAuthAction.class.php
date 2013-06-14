<?php

require_once dirname(__FILE__)."/../config.inc.php";

class userAuthAction extends Action{
	public function initialize(){
		$this->user_logic = $this->getLogic("User");
	}
	public function execute(){
		$service_id = $this->request->getParametor('service_id',"");
		$user_id = $this->request->getParametor('user_id',"");
		$password = $this->request->getParametor('password',"");

		$result = $this->user_logic->userAuth($service_id, $user_id, $password);
		$this->request->setAttribute('result',$result);
	}
}
