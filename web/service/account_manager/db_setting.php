#!/usr/bin/php
<?php

require_once 'config.inc.php';

$host = $conf['db-main']['host'];
$dbname = $conf['db-main']['dbname'];
$user = $conf['db-main']['user'];
$pass = $conf['db-main']['pass'];

$db = new PDO("mysql:dbname=$dbname;host=$host",$user,$pass);

//サービス名のDB作成
$query = "CREATE TABLE IF NOT EXISTS service_id_table(".
		"id int NOT NULL PRIMARY KEY AUTO_INCREMENT,".
		"service_id varchar(255) NOT NULL UNIQUE,".
		"service_table_name varchar(255) NOT NULL".
	")ENGINE=InnoDB DEFAULT CHARACTER SET 'utf8'";
try{
	$stmt = $db->prepare($query);
	$stmt->execute();
	echo "success CREATE TABLE service_id_table\n";
}catch(Exception $e){
	echo "failure CREATE TABLE service_id_table\n";
	echo $e->getMessage();
}

//権限管理のDB作成
$query = "CREATE TABLE IF NOT EXISTS permission_table(".
		"id int NOT NULL PRIMARY KEY AUTO_INCREMENT,".
		"permission varchar(255) NOT NULL UNIQUE".
	")ENGINE=InnoDB DEFAULT CHARACTER SET 'utf8'";
try{
	$stmt = $db->prepare($query);
	$stmt->execute();
	echo "success CREATE TABLE permission_table\n";
}catch(Exception $e){
	echo "failure CREATE TABLE permission_table\n";
	echo $e->getMessage();
}
