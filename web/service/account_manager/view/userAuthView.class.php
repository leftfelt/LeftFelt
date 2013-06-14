<?php

require_once dirname(__FILE__)."/../config.inc.php";

class userAuthView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer('Json');
		$result = $this->request->getAttribute('result');
		$renderer->setAttribute('result',$result);
		return $renderer;
	}
}
