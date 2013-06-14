<?php

require_once dirname(__FILE__)."/../core/Logic.class.php";

class HttpRequestLogic extends Logic{
	public function initialize(){
	}
	public function request($url, $method, $data){
		$headers = array(
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: '.strlen(http_build_query($data)),
		);
		$options = array( 'http' => array(
			'method' => $method,
			'content' => http_build_query($data),
			'header' => implode("\r\n",$headers),
		));
		$result = file_get_contents($url,false,stream_context_create($options));
		return $result; 
	}
}
