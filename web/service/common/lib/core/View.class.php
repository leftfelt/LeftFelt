<?php

require_once dirname(__FILE__)."/Controller.class.php";

/**
 * View
 * Rendererをrequestによって切り替えたり、Rendererにパラメータを渡す処理を行う。
 * Actionの戻り値で指定されて、実行されることもある。
 * 基本的にはActionと同じ名前のViewがController内で自動的に実行される。(dispatch後)
 */
abstract class View{
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

	//メインの処理
	//@return Renderer
	abstract function execute();

	//レンダラを取得
	public function getRenderer($class_name){
		return $this->controller->getRenderer($class_name);
	}
	
	public function loadComponent($class_name){
		return $this->controller->loadComponent($class_name);
	}

}
