<?php

define("COMMENTS_ROOT",'comments/');
define("COMMENTS_MAX",1000);

class Comments{
	var $filename;
	var $dom;
	public function __construct(){
		$this->dom = null;
	}

	//コメントファイル作成
	public function create($id){
	        $this->filename = $id.'.xml';
		if(file_exists(COMMENTS_ROOT.$this->filename)){
			$this->load($id);
			return;
		}
	        $this->dom = new DomDocument('1.0','UTF-8');
	        $this->dom->formatOutput=true;
	
        	$comments = $this->dom->appendChild($this->dom->createElement('comments'));
        	$this->dom->save(COMMENTS_ROOT.$this->filename);
	}
	//コメントファイル読込
	public function load($id){
		$this->filename = $id.".xml";
		if(!isset($this->dom)){
			$this->dom = new DomDocument('1.0','UTF-8');
		}
		$this->dom->preserveWhiteSpace = false;	
	        $this->dom->formatOutput=true;
		$this->dom->load(COMMENTS_ROOT.$this->filename);
	}

	//コメント追加
	public function add($comment,$user_id,$start,$end,$no,$speed){
		$comments = $this->dom->childNodes->item(0);
		//COMMENT_MAX件以上コメントが存在したら
		echo $comments->childNodes->length;
		while($comments->childNodes->length >= COMMENTS_MAX){
			$comments->removeChild($comments->childNodes->item(0));
		}
	        $cmt = $comments->appendChild($this->dom->createElement('comment'));
        	$cmt->appendChild($this->createAttribute('user_id',$user_id));
        	$cmt->appendChild($this->createAttribute('start',$start));
        	$cmt->appendChild($this->createAttribute('end',$end));
        	$cmt->appendChild($this->createAttribute('speed',$speed));
        	$cmt->appendChild($this->dom->createTextNode($comment));
        	$this->dom->save(COMMENTS_ROOT.$this->filename);
	}
	
	//属性を追加
	private function createAttribute($name,$value){
	        $attribute = $this->dom->createAttribute($name);
	        $attribute->value = $value;
	        return $attribute;
	}
	public function getXML(){
		return $this->dom;
	}	
}
