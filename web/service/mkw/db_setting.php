#!/usr/bin/php
<?php

require_once 'config.inc.php';

$host = $conf['db-main']['host'];
$dbname = $conf['db-main']['dbname'];
$user = $conf['db-main']['user'];
$pass = $conf['db-main']['pass'];

$db = new PDO("mysql:dbname=$dbname;host=$host",$user,$pass);
//ポイントサイト管理用のDB作成
$query = "CREATE TABLE IF NOT EXISTS point_site_table(".
		"id int NOT NULL PRIMARY KEY AUTO_INCREMENT,".
		"name varchar(255) UNIQUE NOT NULL COMMENT 'ポイントサイトの名前',".
		"description text COMMENT 'ポイントサイトの紹介文',".
		"rate varchar(100) COMMENT 'ポイントの交換レート',".
		"to_webmoney varchar(100) COMMENT 'WebMoneyへの交換',".
		"min_pay varchar(100) COMMENT '最低支払',".
		"exchange_place text COMMENT 'ポイント交換先',".
		"how_to_get_point text COMMENT 'ポイント獲得方法',".
		"register_info text COMMENT 'ポイント登録情報',".
		"register_link varchar(255) COMMENT '登録リンク',".
		"banner_link varchar(255) COMMENT 'バナー　リンク'".
	")ENGINE=InnoDB DEFAULT CHARACTER SET 'utf8'";
try{
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
	if($result !== true) throw new Exception();
	echo "success CREATE TABLE point_site_table\n";
}catch(Exception $e){
	echo "failure CREATE TABLE point_site_table\n";
	echo $e->getMessage();
}

