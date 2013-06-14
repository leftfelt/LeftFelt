<?php

require_once dirname(__FILE__).'/Controller.class.php';

/**
 * Model
 * db,memcache,redis等への入出力を行うクラス
 * 基本的にgetter,setter,delete,updateなどの単機能を実装する
 * これらを組み合わせた処理はLogicクラスで実装する
 */
abstract class Model{
	private $controller;

	public function __construct(&$controller){
		$this->controller = $controller;
		$this->initialize();
	}

	//初期処理
	abstract function initialize();

	public function getModel($class_name){
		return $this->controller->getModel($class_name);
	}
	
	public function loadComponent($class_name){
		return $this->controller->loadComponent($class_name);
	}
}

