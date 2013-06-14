<?php

require_once dirname(__FILE__)."/Controller.class.php";

/**
 * Filterという名ののLogic
 * Actionのexecuteが実行される前後に実行される処理
 * ログイン処理とかメンテナンス処理を想定している
 */
abstract class Filter extends Logic{
	private $controller;
	protected $config;
	protected $request;

	public function __construct(&$controller){
		$this->controller = $controller;
		$this->config = $this->controller->config;
		$this->request =& $this->controller->request;
		$this->initialize();
	}

	//メインの処理
	abstract function execute();

	public function getLogic($class_name){
		return $this->controller->getLogic($class_name);
	}

	public function loadComponent($class_name){
		return $this->controller->loadComponent($class_name);
	}
}
