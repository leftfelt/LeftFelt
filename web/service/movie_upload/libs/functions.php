<?php

if(isset($_GET['debug'])){
	print_r($_GET);
	print_r($_POST);
	print_r($_FILES);
}

//ページャー
function getPages($page,$view,$total,$num=10){
        $pages = null;
        for($i = 0 ; $i < $num ; $i++){
                $p = $i - (int)($num/2) + $page;
                if( 0 < $p && $p <= ceil($total/$view)){
                        $pages[] = $p;
                }
        }
        return $pages;
}

//ワイルドカードを含めたエスケープ
function escapeString($string){
	$string = mb_ereg_replace('([_%#])','#\1',$string);
	$string = addslashes("$string");
	return $string;
}

//ログイン状態チェック
//$option : true : チケットを発行する
//$option : false : チェックせずＩＤだけ取得
//$option : null : ログインしてなかったらログインページへ

function loginCheck($option=null){
	
	if(isset($_GET['debug']))print_r($_SESSION);
	$name = @$_SESSION['login'];
	if(!isset($name) && $option !== false) {
	        header("Location:login.php");
		exit;
	}
	if($option == true){
		$_SESSION['ticket'] = sha1(uniqid().mt_rand());
	}
	return $name;
}

function db_connect() {
  $dsn = 'mysql:dbname=my_service;host=localhost';
  $user = 'root';
  $pass = 'uglxc229';
  $pdo = new PDO($dsn,$user,$pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $pdo;
}

/***********************************
 * Authenticator
 * ログイン認証用関数
 *
 * @param string (NAME)
i * @param string (Password)
 * @return bool
 * ログインに成功した場合：true
 * ログインに失敗した場合：false
 *
 ***********************************/
function Authenticator($name , $password) {

    //==========================================================
    //データベースへ接続
    $db = db_connect();

    //---------------------------------------------------------
    //SQL文作成
    $sql = "select * from users where
            name = :name and
            password = :passwd
            ;";

    $param = array(
             ':name' => $name,
             ':passwd' => sha1($password),
             );

    $stmt = $db->prepare($sql);

    //実行
    $stmt->execute($param);

    $result = $stmt->fetch();

    return (!$result)? false : true;
}
/***********************************
 * 
 * 新規登録用関数
 *
 * @param string (NAME)
i * @param string (Password)
 * @return bool
 * 登録に成功した場合：true
 * 登録に失敗した場合：false
 *
 ***********************************/
function AddUser($name,$password){
	$db = db_connect();	
	$sql = "insert into users(name,password) 
		values(:name , :password);";

	$param = array(
		':name' => $name,
		':password' => sha1($password),
		);

	$stmt = $db->prepare($sql);
	$result = $stmt->execute($param);
	return (!$result) ? false : true;
}
