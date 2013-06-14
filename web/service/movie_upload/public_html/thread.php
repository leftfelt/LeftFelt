<?php
require_once('../libs/comments.class.php');
require_once('../libs/videos.class.php');
require_once('../libs/ffmpeg.class.php');
require_once('../libs/functions.php');

define('THUMB_WIDTH',160);
define('THUMB_HEIGHT',120);

session_start();

$name = loginCheck();
$type = @$_POST['type'];

switch($type){
	case "comment":
		//ファイルにコメント追加
		$id = @$_POST["id"];
		$comment = @$_POST['comment'];
		$start = @$_POST['start'];
		$end = @$_POST['end'];
		$speed = @$_POST['speed'];
		
		if(!isset($id)||!isset($comment)||!isset($start)||!isset($end)||!isset($speed)||$comment=='')break;
		
		$comments = new Comments();
		$comments->load($id);
		$comments->add($comment,$name,$start,$end,0,$speed);
		//コメント数+1
		$videos = new Videos();
		$videos->incrementComment_count($id);
		break;
	case "video":
		if(isset($_POST['ticket'],$_SESSION['ticket']) && $_POST['ticket'] === $_SESSION['ticket'] && $_SESSION['ticket']!=''){
			$_SESSION['ticket'] = '';
		}else{
			//期限切れ
			echo 'expire ticket';
			break;
		}
		//再生数+1
		$id = @$_POST['id'];
		$videos = new Videos();
		$videos->incrementView($id);
		break;
	case "addtag":
		$id = @$_POST['id'];
		$tag = @$_POST['tag'];
		if(isset($tag) && $tag != ""){
			$videos = new Videos();
			$videos->addVideoTag($id,$tag);
		}
		break;
	case 'deletetag':
		$id = @$_POST['id'];
		$tag = @$_POST['tag'];
		$videos = new Videos();
		$videos->deleteVideoTag($id,$tag);
		break;
	case 'upload':
		if(isset($_POST['ticket'],$_SESSION['ticket']) && $_POST['ticket'] === $_SESSION['ticket'] && $_SESSION['ticket']!=''){
			$_SESSION['ticket'] = '';
		}else{
			//期限切れ
			echo 'expire ticket';
			break;
		}
		$name = @$_POST['name'];
		$title = @$_POST['title'];
		$desc = @$_POST['description'];
		$time = 10;

		//アップロードファイルの確認
		if(!is_uploaded_file($_FILES['video']['tmp_name'])) break;
		
		//DBにデータ追加&ID取得
		$videos = new Videos();
        $filename = $videos->addVideo($name,$title,$desc);

		if(!move_uploaded_file($_FILES['video']['tmp_name'],UPLOADS_ROOT.$filename)) break;
		//変換開始
		$ffmpeg = new ffmpeg();
		if(!$ffmpeg->convert(UPLOADS_ROOT.$filename,VIDEOS_ROOT.$filename.TYPE))break;

		//サムネイル作成
		$videos->makeThumbnail($filename,THUMB_WIDTH,THUMB_HEIGHT,$time);
		
		//コメントファイル作成
		$comments = new Comments();
                $comments->create($filename);

		//動画を有効にする
		$videos->enable($filename);
		//変換完了通知
		echo 'encoded';
               	break;
	default:
		break;
}

function debug($data){
	$fp = fopen('/home/httpd/public_html/debug/result','w');
	fprintf($fp,$data);
	fclose($fp);
}

if(isset($_GET['debug'])){
	print "<plaintext>".$comments->getXML()->saveXML();

	$fp = fopen(COMMENTS_ROOT.'ttttt','w');
	fprintf($fp,"aa$id,$comment,$user_id,$time,$speed");
	fclose($fp);
}
