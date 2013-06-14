<?php
require_once("../libs/videos.class.php");

$id = @$_GET['id'];
$keyword= @$_GET['keyword'];
$tag = @$_GET['tag'];
$dtag = @$_GET['dtag'];
$videos = new Videos();

$videos->enable($id);


//タグの一覧を取得
$result = $videos->getVideoTags($id);
print_r($result);
echo '<br><br>';
//動画の詳細を取得
$result = $videos->getVideoDesc($id);
print_r($result);
//タグを追加
if(isset($tag))$videos->addVideoTag($id,$tag);
if(isset($dtag))$videos->deleteVideoTag($id,$dtag);
//再生数
if(isset($_GET['view']))$videos->incrementView($id);
//コメント数
if(isset($_GET['comment']))$videos->incrementComment_count($id);
echo '<br><br>';
//動画の詳細を取得
$result = $videos->getVideoDesc($id);
print_r($result);

