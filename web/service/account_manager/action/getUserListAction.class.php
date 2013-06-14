<?php

require_once dirname(__FILE__)."/../config.inc.php";

class getUserListAction extends Action{
	public function initialize(){
		$this->user_logic = $this->getLogic("User");
	}
	public function execute(){
		$service_id = $this->request->getParametor("service_id","");
		$page = $this->request->getParametor("page",1);
		$view_count = 20;
		try{
			$user_list = $this->user_logic->getUserList($service_id,$page,$view_count);
		}catch(Exception $e){
			//サービスが指定されていなかった場合
			$user_list = new UserList(array());
		}
		$this->request->setAttribute("user_list",$user_list);
	}
}
