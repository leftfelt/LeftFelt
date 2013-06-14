<?php

require_once dirname(__FILE__)."/../config.inc.php";

class indexAction extends Action{
	public function getPreFilter(){
		//executeの前処理
		return array();
	}
	public function getPostFilter(){
		//executeの後処理
		return array();
	}
	public function initialize(){
		//初期化処理
	}
	public function execute(){
		//メイン処理
		return; //View名を返すとそちらへ飛ぶ
	}
}
