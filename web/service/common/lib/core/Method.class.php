<?php

/**
 * 実行可能な形式の関数を表すクラス 
 */
class Method
{
	private $method;	//関数
	private $args;		//引数

	/**
	 * call_user_func_arrayで実行可能な形式
	 * @param mixed $method
	 * @param mixed $args...
	 * @throw InvalidArgumentException 
	 */
	public function __construct(){
		$args = func_get_args();
		$method = array_shift($args);
		if(!is_array($method) && !is_string($method)) 
			throw new InvalidArgumentException('$method must be array or string');
		if(!is_array($args))
			throw new InvalidArgumentException('$args must be array');
		$this->method = $method;
		$this->args = $args;
	}

	/**
	 * 関数を取得する
	 * @return mixed 
	 */
	public function getMethod(){
		return $this->method;
	}

	/**
	 * 引数を取得する
	 * @return mixed 
	 */
	public function getArgs(){
		return $this->args;
	}

	/**
	 * キーを生成する
	 * @return string  
	 */
	public function createKey(){
		if(is_array($this->method)){
			$method_key = get_class($this->method[0]).':'.$this->method[1];
		}else{
			$method_key = $this->method;
		}

		$args_key = serialize($this->args);

		return md5($method_key.'=>'.$args_key); 
	}

	/**
	 * 関数の実行結果を取得する
	 * @return mixed
	 */
	public function call(){
		return call_user_func_array($this->method,$this->args);
	}
}

