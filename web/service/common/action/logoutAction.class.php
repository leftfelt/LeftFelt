<?php

require_once dirname(__FILE__)."/../config.inc.php";

class logoutAction extends Action{
	public function initialize(){
	}
	public function execute(){
		$next_url = urldecode($this->request->getParametor('next_url',""));
		$service_id = $this->request->getParametor("service_id","");

		//ログアウトする
		setcookie('user_session',"",time() - $this->config['login_expire']);

		$this->request->setAttribute('next_url', urlencode($next_url));
		$this->request->setAttribute('service_id', $service_id);

		return "login";
	}
}
