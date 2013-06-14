#!/usr/bin/php
<?php

require_once('config.inc.php');

$host = $conf['db-main']['host'];
$dbname = $conf['db-main']['dbname'];
$user = $conf['db-main']['user'];
$pass = $conf['db-main']['pass'];

$db = new PDO("mysql:dbname=$dbname;host=$host",$user,$pass);

//画像URL収集用のDB作成
$query = "CREATE TABLE IF NOT EXISTS image_url(".
		"id int NOT NULL PRIMARY KEY AUTO_INCREMENT,".
		"url varchar(255) NOT NULL UNIQUE".
	")ENGINE=InnoDB DEFAULT CHARACTER SET 'utf8'";
try{
	$stmt = $db->prepare($query);
	$stmt->execute();
	echo "success CREATE TABLE image_url\n";
}catch(Exception $e){
	echo "failure CREATE TABLE image_url\n";
	echo $e->getMessage();
}
