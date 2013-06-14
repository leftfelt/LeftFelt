<?php

require_once dirname(__FILE__)."/Controller.class.php";

/*
 * Logic
 * イメージとしてはActionで使われる共通の処理をLogicに切り出す
 * Modelを組み合わせて処理を行う
 * 機能としてはActionとほぼ同じ
 */
abstract class Logic {
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
