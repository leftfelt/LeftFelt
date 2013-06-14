<?php
require_once('../libs/MySmarty.class.php');
require_once('../libs/functions.php');

session_start();
//ログインチェック
$name = loginCheck(true);

$smarty = new MySmarty();
$smarty->assign('name',$name);
$smarty->assign('ticket',$_SESSION['ticket']);
$smarty->display('upload.html');

