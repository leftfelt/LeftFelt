<?php

require_once dirname(__FILE__)."/../core/Filter.class.php";

/**
 * ユーザ情報を読み込むモデル
 */
class loadUserFilter extends Filter{
	public function initialize(){
	}
	public function execute(){
		$user_session = $this->request->getCookie('user_session',"");
		$service_id = $this->config['service_name'];

		$next_url = urlencode($this->config['service_url']);
		if($user_session !== ""){
			//ログイン済ならユーザ情報を読み込む
		}else{
			//未ログイン野場合はログインページへ
			header("Location: ".$this->config['login_url']."?service_id=$service_id&next_url=".$next_url);
		}
	}
}
