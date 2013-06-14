<?php
require_once('../libs/functions.php');
require_once('../libs/MySmarty.class.php');
session_start();


$name = @$_POST['name'];
$passwd1 = @$_POST['passwd1'];
$passwd2 = @$_POST['passwd2'];

$messages = array();

$smarty = new MySmarty();
if(isset($_POST['register'])){
	if(!isset($name)||$name=='') $messages[] = '名前を入力してください';
	if(!isset($passwd1)||$passwd1==''||!isset($passwd2)||$passwd2=='') $messages[] = 'パスワードを入力してください';
	if($passwd1 != $passwd2) $messages[] = 'パスワードが間違っています。';
	if(empty($messages)){
		$_SESSION['ticket'] = sha1(uniqid().mt_rand());
		$smarty->assign('name',$name);
		$smarty->assign('password',$passwd1);
		$smarty->assign('ticket',$_SESSION['ticket']);
		$smarty->display('confirm.html');
		exit;
	}
}

$smarty->assign('messages',$messages);
$smarty->display('register.html');
