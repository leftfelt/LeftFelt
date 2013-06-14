<?php

/**
 * 安全なハッシュを生成する(したい)
 */
class SecureHash
{
	private $hash_type = 'sha512';
	private $stretching;
	private $salt_prefix;
	private $salt_postfix;
	
	/**
	 * @param int $stretching		ハッシュ化する回数
	 * @param string $salt_prefix	接頭辞
	 * @param string $salt_postfix	接尾辞
	 */
	public function __construct($stretching=1,$salt_prefix="", $salt_postfix=""){
		$this->stretching = $stretching;
		$this->salt_prefix = $salt_prefix;
		$this->salt_postfix = $salt_postfix;
	}

	/**
	 * ハッシュ値を取得する
	 * @param string $str
	 */
	public function get($str)
	{
		$key = $this->salt_prefix.$str.$this->salt_postfix;
		for ($i = 0; $i < $this->stretching; $i++) {
			$key = hash(
				$this->hash_type,
				$key
			);
		}
		return $key;
	}
}
