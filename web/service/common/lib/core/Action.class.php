<?php

require_once dirname(__FILE__)."/Controller.class.php";

/**
 * Action
 * ページごとのメイン処理にあたる
 * Logic,Modelを組み合わせて実装を行う
 * 戻り値にViewの名前を指定するとそのViewがController内で自動的に実行される
 */
abstract class Action {
	private $controller;
	protected $config;
	protected $request;

	public function __construct(&$controller){
		$this->controller = $controller;
		$this->config = $this->controller->config;
		$this->request =& $this->controller->request;
		$this->initialize();
	}

	//初期処理
	abstract function initialize();

	//execute実行前の処理
	//ログイン処理とか
	public function getPreFilter(){
		return array();
	}

	//execute実行後の処理
	public function getPostFilter(){
		return array();
	}

	//メインの処理
	//@return string Viewの名前
	abstract function execute();

	public function getModel($class_name){
		return $this->controller->getModel($class_name);
	}

	public function getLogic($class_name){
		return $this->controller->getLogic($class_name);
	}

	public function loadComponent($class_name){
		return $this->controller->loadComponent($class_name);
	}

}
