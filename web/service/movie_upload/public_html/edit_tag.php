<?php
require_once('../libs/MySmarty.class.php');
require_once('../libs/videos.class.php');
require_once('../libs/functions.php');

session_start();
$name = loginCheck();
$videos = new Videos();

$id = @$_GET['id'];
if(!isset($id))header("Location:search.php");

$tags = $videos->getVideoTags($id);

$smarty = new MySmarty();
$smarty->assign("name",$name);
$smarty->assign("tags",$tags);
$smarty->display('edit_tag.html');

