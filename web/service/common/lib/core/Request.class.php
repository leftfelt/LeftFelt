<?php

require_once dirname(__FILE__).'/Parametor.class.php';

//GETとPOSTで受け取ったパラメータをすべて受け取る
class Request extends Parametor{
	private $request;

	public function __construct(){
		$this->request = $_GET + $_POST;
		$this->cookie = $_COOKIE;
		if(array_key_exists('REQUEST_URI',$_SERVER)){
			session_start();
			$this->session = $_SESSION;
		}
	}

	//キーを指定してパラメータを取得する
	public function getParametor($name,$default=null){
		return $this->get($this->request, $name, $default);
	}
	//受け取ったパラメータをすべて取得する
	public function getParametors(){
		return $this->request;
	}

	//クッキーの値を取得する
	public function getCookie($name,$default=null){
		return $this->get($this->cookie, $name, $default);
	}

	//クッキーの値を設定する
	public function setCookie($name, $value, $expire, $domain, $path='/', $secute=false, $httponly=false){
		setcookie($name, $value, $expire, $path, $domain, $secute, $httponly);
		$this->cookie = $_COOKIE;
	}

	//セッションの値を取得する
	public function getSession($name, $default=null){
		return $this->get($this->session, $name, $default);
	}

	//セッションの値を設定する
	public function setSession($name, $value){
		$_SESSION[$name] = $value;
		$this->session = $_SESSION;
	}

	private function get($data, $name, $default=null){
		if(array_key_exists($name, $data) && isset($data[$name])){
			return $data[$name];
		}
		return $default;
	}
}
