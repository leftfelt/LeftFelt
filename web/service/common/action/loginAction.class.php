<?php

require_once dirname(__FILE__)."/../config.inc.php";

class loginAction extends Action{
	public function initialize(){
		$this->loadComponent('SecureHash');
		$this->hasher = new SecureHash(
			$this->config['password_hash']['stretching'],
			$this->config['password_hash']['prefix'],
			$this->config['password_hash']['postfix']
		);
	}

	public function execute(){
		$next_url = urldecode($this->request->getParametor('next_url',""));
		$data = array(
			'service_id'	=> $this->request->getParametor('service_id'),
			'user_id'		=> $this->request->getParametor('user_id'),
			'password'		=> $this->hasher->get($this->request->getParametor('password')),
		);
		//ログインボタンを押されたら認証する
		if($this->request->getParametor('login_button',"") !== ""){
			$response = $this->userAuthRequest($data);
			if($response !== false){
				//認証に成功したらnext_urlへリダイレクト
				$this->request->setCookie('user_session',$response,time() + $this->config['login_expire']);
				header("Location: $next_url");
			}
		}

		$this->request->setAttribute('next_url', urlencode($next_url));
		$this->request->setAttribute('service_id', $data['service_id']);
	}

	public function userAuthRequest($data){
		$url = $this->config['userauth_url'];
		$http_request = $this->getLogic('HttpRequest');
		$result = json_decode($http_request->request($url,"POST", $data));
		return $result->result;
	}
}
