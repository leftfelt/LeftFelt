<?php

require_once dirname(__FILE__).'/../core/Renderer.class.php';

//表示の必要ない場合に使用する何もしないレンダラ

class DoNothingRenderer extends Renderer
{
	public function execute(){
		//クラス名の通り何もしない
	}
}
