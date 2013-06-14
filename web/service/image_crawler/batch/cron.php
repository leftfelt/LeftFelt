#!/usr/bin/php
<?php
//manage/以下にバッチを追加したらここにもファイル名を追加する
//１分毎にすべて実行される

//画像のURLを収集するバッチ
execute_batch('image_crawler');
//収集先URLの数が多くなりすぎないようにするバッチ
//execute_batch('adjustLinkCount.php');

//バッチを実行する
function execute_batch($filename){
	$dir = dirname(__FILE__);
	exec($dir."/".$filename);
}
