<?php
require_once("../libs/MySmarty.class.php");
require_once("../libs/videos.class.php");
require_once("../libs/functions.php");

//ログインチェック
session_start();
$name = loginCheck(false);
$videos = new Videos();

$keyword = @$_GET['keyword'];
$page = @$_GET['page'];
$view = @$_GET['view'];
$total = 0;

if(!isset($page)||$page <= 0) $page = 1;
if(!isset($view))$view = 20;


$result = array();

if(isset($keyword) && $keyword != ""){
	//検索
	if(isset($_GET['tag'])){
		//タグ検索
		$result = $videos->searchTag($keyword,$page,(int)$view);
		$total = $videos->getTotal();
	}else{
		//キーワード検索
		$result = $videos->searchVideo($keyword,$page,(int)$view);
		$total = $videos->getTotal();
	}
}

$pages = getPages($page,$view,$total);

$smarty = new MySmarty();
$smarty->assign("name",$name);
$smarty->assign("video_list",$result);
$smarty->assign("total",$total);
$smarty->assign("pages",$pages);
$smarty->display("search.html");
