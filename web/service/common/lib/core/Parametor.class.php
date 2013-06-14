<?php
//複数のクラスにパラメータを渡すときに使ったりする
class Parametor{
	private $attributes = array();

	public function setAttribute($name, $data){
		$this->attributes[$name] = $data;
	}

	public function setAttributes($data_list){
		$this->attributes = array_merge(
			$this->attributes,
			$data_list
		);
	}

	public function getAttribute($name, $default=null){
		if(array_key_exists($name, $this->attributes) && isset($this->attributes[$name])){
			return $this->attributes[$name];
		}
		return $default;
	}

	public function getAttributes(){
		return $this->attributes;
	}
}


