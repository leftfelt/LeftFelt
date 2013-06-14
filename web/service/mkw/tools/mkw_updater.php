#!/usr/bin/php
<?php

$ftp = array(
	'ftp_server' => 'ftp.homepage.shinobi.jp',
	'ftp_user_name' => 'kantanwmget.tubakurame.com',
	'ftp_user_pass' => 'uglxc229',
);

$dir = dirname(__FILE__).'/../source/';

$conn_id = ftp_connect($ftp['ftp_server']);
$login_result = ftp_login($conn_id,$ftp['ftp_user_name'],$ftp['ftp_user_pass']);
ftp_pasv($conn_id,true);

file_upload($conn_id,$dir, '');

function file_upload($conn_id,$dir,$root){
	$res_dir = opendir($dir);
	while(false !== ($filename = readdir($res_dir))){
		if($filename != '.' && $filename != '..' && $filename != '.svn'){
			if(is_dir($dir.$filename)){
				file_upload($conn_id,$dir.$filename.'/', $root.$filename.'/');
				continue;
			}
			if(!ftp_put($conn_id,$root.$filename,$dir.$filename,FTP_ASCII)){
				echo "failed $dir$filename\n";
				continue;
			}
		}
	}
}
