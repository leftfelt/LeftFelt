<?php

require_once dirname(__FILE__)."/../config.inc.php";

class addUserAction extends Action{
	public function initialize(){
		$this->user_logic = $this->getLogic('User');
		$this->loadComponent('SecureHash');
		$this->hasher = new SecureHash(
			$this->config['password_hash']['stretching'],
			$this->config['password_hash']['prefix'],
			$this->config['password_hash']['postfix']
		);
	}
	public function execute(){
		$service_id = $this->request->getParametor('service_id',"");
		$user_id = $this->request->getParametor('user_id',"");
		$password = $this->hasher->get($this->request->getParametor('password',""));
		$permission_id = $this->request->getParametor('permission_id',"");
		try{
			$result = $this->user_logic->addUser($service_id,new User(
					$user_id,
					$password,
					$permission_id
			));
		}catch(Exception $e){
			$result = false;
		}
		$this->request->setAttribute('result',$result);
	}
}
