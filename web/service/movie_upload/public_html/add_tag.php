<?php
require_once('../libs/videos.class.php');

$id = @$_GET['id'];
$tag = @$_GET['tag'];

$videos = new Videos();

$videos->addVideoTag($id,$tag);

header("Location:edit_tag.php?id=$id");
