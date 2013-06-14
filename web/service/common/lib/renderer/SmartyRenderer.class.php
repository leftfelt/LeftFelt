<?php

require_once dirname(__FILE__).'/../core/FSmarty.class.php';
require_once dirname(__FILE__).'/../core/Renderer.class.php';

//セットされているパラメータをすべてSmartyに渡し、templateに出力

class SmartyRenderer extends Renderer
{
	public function initialize(){
	}	
	public function execute(){
		$smarty = new FSmarty($this->controller->config['base_dir']);
		$parametors = $this->getAttributes();
		foreach($parametors as $key => $parametor){
			$smarty->assign($key,$parametor);
		}
		$smarty->display($this->template);
	}
}
