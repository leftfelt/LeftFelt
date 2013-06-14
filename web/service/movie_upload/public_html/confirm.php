<?php
require_once('../libs/functions.php');
require_once('../libs/MySmarty.class.php');
session_start();

$name = @$_POST['name'];
$passwd = @$_POST['password'];
$smarty = new MySmarty();
$messages = array();

if(isset($_POST['ticket'],$_SESSION['ticket']) && $_POST['ticket'] === $_SESSION['ticket'] && $_SESSION['ticket'] != ''){
	$_SESSION['ticket'] = '';
}else{
	//期限切れ
	$messages[] = 'ページの有効期限が切れています。';
	$messages[] = '登録に失敗しました。';
	$smarty->assign('messages',$messages);
	$smarty->display('login.html');
	exit;
}

if(AddUser($name,$passwd)) $messages[] = '登録完了！';
else $messages[] = '登録に失敗しました。';
$smarty->assign('messages',$messages);
$smarty->display('login.html');
