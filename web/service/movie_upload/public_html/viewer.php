<?php
require_once('../libs/MySmarty.class.php');
require_once('../libs/videos.class.php');
require_once('../libs/functions.php');

session_start();
$name = loginCheck(true);
$videos = new Videos();

$id = @$_GET['id'];
if(!isset($id))header("Location:search.php");

$video = $videos->getVideoDesc($id);

//存在しない動画にアクセスしようとした。
if(!$video)header("Location:Err/errorNoVideo.php");

$smarty = new MySmarty();
$smarty->assign("name",$name);
$smarty->assign("video",$video);
$smarty->assign("ticket",$_SESSION['ticket']);
$smarty->display('viewer.html');

