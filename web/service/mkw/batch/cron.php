#!/usr/bin/php
<?php
//毎週木曜の21:00に定期実行される

//無料で簡単にWebMoneyを貯めようを自動更新
execute_batch('../tools/mkw_updater.php');

//バッチを実行する
function execute_batch($filename){
	$dir = dirname(__FILE__);
	exec($dir."/".$filename);
}
