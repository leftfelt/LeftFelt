<?php
require_once("../libs/MySmarty.class.php");
require_once("../libs/videos.class.php");
require_once("../libs/functions.php");
session_start();

$name = @$_POST['name'];
$passwd = @$_POST['passwd'];

$messages = array();
if(isset($_POST['login'])){
	if(Authenticator($name,$passwd)){
		//ログイン成功(検索ページへ)
		$_SESSION['login'] = $name;//ログイン済みにする
		header('Location:mypage.php');
	}else{
		$messages[] = 'idかpasswordが間違っています。';
	}
}else if(isset($_POST['register'])){
	header('location:register.php');
	exit;
	//新規登録
	if(!isset($name)||$name=='') $messages[] = '名前を入力してください';
	if(!isset($passwd)||$passwd=='') $messages[] = 'パスワードを入力してください';
	if(empty($messages)){
		if(AddUser($name,$passwd)) $messages[] = '登録完了';
	}
}

$smarty = new MySmarty();
$smarty->assign("messages",$messages);
$smarty->display('login.html');

