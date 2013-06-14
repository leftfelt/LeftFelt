<?php

define("COMMENTS_ROOT",'comments/');

$id = @$_GET['id'];

//コメントファイル作成
createComments($id);

addComments($id,'aa','flet',8,5,3);

//コメントファイル作成
function createComments($id){
	$filename = $id.'.xml';
	$dom = new DomDocument('1.0','UTF-8');

	$dom->formatOutput=true;

	$comments = $dom->appendChild($dom->createElement('comments'));
	$dom->save(COMMENTS_ROOT.$filename);
}

//コメントファイル読込
function loadComments($id){
	$filename = $id.'.xml';
	$comments = simplexml_load_file(COMMENTS_ROOT.$filename);
	return $comments;
}

//コメント追加
function addComments($id,$comment,$user_id,$vpos,$no,$speed){
	$dom = new DomDocument('1.0','UTF-8');
	$dom->formatOutput=true;
	
	$dom->load(COMMENTS_ROOT.$id.'.xml');
	$comments = $dom->childNodes->item(0);
	$cmt = $comments->appendChild($dom->createElement('comment'));
	$cmt->appendChild(createAttribute($dom,'user_id',$user_id));
	$cmt->appendChild(createAttribute($dom,'vpos',$vpos));
	$cmt->appendChild(createAttribute($dom,'speed',$no));
	$cmt->appendChild($dom->createTextNode($comment));
	$dom->save(COMMENTS_ROOT.$id.'.xml');
}

//属性を追加
function createAttribute(&$dom,$name,$value){
	$attribute = $dom->createAttribute($name);
	$attribute->value = $value;
	return $attribute;
}
