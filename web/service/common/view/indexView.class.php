<?php

require_once dirname(__FILE__)."/../config.inc.php";

class indexView extends View{
	public function initialize(){
		//初期化処理
	}
	public function execute(){
		//メイン処理
		$renderer = $this->getRenderer('Manage');
		$renderer->setTemplate("index.html");
				return $renderer;
	}
}
