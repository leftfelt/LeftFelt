#!/usr/bin/php
<?php
$dir = dirname(__FILE__);
require_once($dir.'/../config.inc.php');

$redis = new imageCrawlerRedisModel;
$redis->connect($conf['redis-main']);

$count = $redis->getCount();

$del_count = $count * 5 / 100;

//全体の5%をランダムで削除する
for($i = 0 ; $i < $del_count ; $i++){
	$redis->deleteLink();
}

$redis->close();
