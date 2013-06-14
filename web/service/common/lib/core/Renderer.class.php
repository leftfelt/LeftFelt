<?php
require_once dirname(__FILE__)."/FSmarty.class.php";
require_once dirname(__FILE__).'/Parametor.class.php';

//パラメータをSmartyに渡す
abstract class Renderer extends Parametor{
	protected $controller;
	protected $template;

	public function __construct(&$controller){
		$this->controller = $controller;
		$this->initialize();
	}

	//初期処理
	abstract function initialize();

	//パラメータを渡すテンプレートを設定する
	public function setTemplate($template){
		$this->template = $template;
	}

	//実装は継承先に任せる
	abstract public function execute();

	public function loadComponent($class_name){
		return $this->controller->loadComponent($class_name);
	}
}
