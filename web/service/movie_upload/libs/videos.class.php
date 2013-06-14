<?php
require_once('functions.php');

$root = dirname(__FILE__);

define('VIDEOS_ROOT',$root.'/../public_html/videos/');
define('UPLOADS_ROOT',$root.'/../uploads/');
define('THUMBNAIL_ROOT',$root.'/../public_html/thumbnail/');
define('VIDEOS_COLUMM','id,user_id,title,description,view,comment_count');
define('TYPE','.mp4');


//動画のエンコード・検索・ランキング・サムネイル作成・タグの編集・詳細取得を行う
class Videos{
	
	var $db;

	public function __construct(){
		$this->db = db_connect();
	}
	
	/*************************************
	サムネイル作成
	*************************************/
	public function makeThumbnail($filename,$size_w,$size_h,$time){
		//ファイルが存在するか確認
		if(!file_exists(VIDEOS_ROOT.$filename.TYPE)) return false;
	        $movie = new ffmpeg_movie(VIDEOS_ROOT.$filename.TYPE);
                $frame_rate = $movie->getFrameRate();
                $target = $frame_rate * $time;
                $frame = $movie->getFrame($target);

                $image = $frame->toGDImage();
                $width = ImageSX($image);
                $height = ImageSY($image);

                $width_rate = $size_w / $width;
                $height_rate = $size_h / $height;

                if($height_rate < $width_rate) $rate = $height_rate;
                else $rate = $width_rate;

                $new_width = $width * $rate;
                $new_height = $height * $rate;

                // リサイズした画像オブジェクトを生成
		$new_image = @ImageCreateTrueColor($new_width, $new_height);
                ImageCopyResized($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                //サムネイル保存
                imagejpeg($new_image,THUMBNAIL_ROOT.$filename.'.jpg');
		return true;
	}
	/*************************************
	すべての動画を削除(初期化)
	*************************************/	
	public function deleteVideoAll(){
		$sql = "delete from videos;alter table videos auto_increment = 0;delete from tags;";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute();
		exec("rm videos/*");
		exec("rm thumbnail/*");
		exec("rm comments/*");
		return (!$result) ? false : true;
	}

	/*************************************
	動画を追加
	*************************************/
	public function addVideo($user_id,$title,$description){
		$sql = "insert into videos(user_id,title,description)
			values(:user_id,:title,:description);";
		$param = array(
			":user_id" => $user_id,
			":title" => escapeString($title),
			":description" => escapeString($description),
			);
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute($param);
		
		//idを取得
		$sql = "select last_insert_id() as id;";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();
		return $result['id'];

	}
	
	/*************************************
	再生数+1
	*************************************/
	public function incrementView($id){
		$sql = "update videos set view = view + 1 where id = :id;";
		$stmt = $this->db->prepare($sql);
		$param = array(
			":id" => $id,
			);
		$result = $stmt->execute($param);
		return (!$result) ? false : true;
	}
	/*************************************
	コメント数+1	
	*************************************/
	public function incrementComment_count($id){
		$sql = "update videos set comment_count = comment_count + 1 where id = :id;";
		$stmt = $this->db->prepare($sql);
		$param = array(
			":id" => $id,
			);
		$result = $stmt->execute($param);
		return (!$result) ? false : true;

	}
	
	/*************************************
	指定動画にタグを追加	
	*************************************/
	public function addVideoTag($id,$tag){
		$sql = "insert into tags(video_id,tag)
			values(:id,:tag);";
		$stmt = $this->db->prepare($sql);
		$param = array(
			":id" => $id,
			":tag" => escapeString($tag),
			);
		try{
			$stmt->execute($param);
			$result = true;
		}catch(Exception $e){
			$result = false;
		}
		return (!$result) ? false : true;	
	}

	/*************************************
	指定動画のタグ一覧を取得
	*************************************/
	public function getVideoTags($id){
		$sql = "select group_concat(tag) as tags from tags where video_id = :id group by video_id;";
		$param = array(
			":id" => $id,
			);
		$stmt = $this->db->prepare($sql);
		$stmt->execute($param);
		$result = $stmt->fetch();
		return explode(',',$result['tags']);
	}
	
	/*************************************
	指定動画のタグを削除
	*************************************/
	public function deleteVideoTag($id,$tag){
		$sql = "delete from tags where video_id = :id and tag = :tag;";
		$tag = escapeString($tag);
		$param = array(
			":id" => $id,
			":tag" => $tag,
			);
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute($param);
		return (!$result) ? false : true;
	}

	/*************************************
	ランキング取得	
	*************************************/
	public function getRanking($page,$view){
		$sql = "select sql_calc_found_rows ".VIDEOS_COLUMM.",group_concat(tag) as tags from videos left join tags on videos.id = tags.video_id where deleted is NULL group by id order by (view+comment_count) desc limit :limit offset :offset;";
		
		$stmt = $this->db->prepare($sql);
		
		$stmt->bindValue(":limit" ,$view,PDO::PARAM_INT);
		$stmt->bindValue(":offset" ,($page-1)*$view,PDO::PARAM_INT);

		$stmt->execute();
		$result = $stmt->fetchAll();
		
		return $this->parseTags($result);
	}

	/*************************************
	指定した動画の詳細を取得	
	*************************************/
	public function getVideoDesc($id){
		$sql = "select ".VIDEOS_COLUMM.",group_concat(tag) as tags from videos left join tags on videos.id = tags.video_id where id = :id and deleted is NULL group by id";
		$stmt = $this->db->prepare($sql);
		$param = array(
			":id" => $id,
			);
		$stmt->execute($param);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if($result&&isset($result['tags'])){
			$result['tags'] = explode(',',$result['tags']);
		}
		return $result;
	}

	/*************************************
	タグを持つ動画を検索
	*************************************/
	public function searchTag($tag,$page,$view){
		$sql = "select sql_calc_found_rows ".VIDEOS_COLUMM.",group_concat(tag) as tags  from videos join (select video_id,group_concat(tag) as tags from tags where tag like :tag group by video_id) as A on A.video_id = videos.id left join tags on A.video_id = tags.video_id where deleted is NULL group by id order by created desc limit :limit offset :offset;";
		$tag = escapeString($tag);
		$stmt = $this->db->prepare($sql);
		$tag = str_replace(' ','%',$tag);	
		$stmt->bindValue(":tag" ,"%$tag%");
		$stmt->bindValue(":limit" ,$view,PDO::PARAM_INT);
		$stmt->bindValue(":offset" ,($page-1)*$view,PDO::PARAM_INT);
		
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		return $this->parseTags($result);
	}

	/*************************************
	動画を検索
	*************************************/
	public function searchVideo($keyword,$page,$view){
		$sql = "select sql_calc_found_rows ".VIDEOS_COLUMM.",group_concat(tag) as tags from videos left join tags on videos.id = tags.video_id where deleted is NULL and (title like :keyword or description like :keyword) group by id order by created desc limit :limit offset :offset;";

		$stmt = $this->db->prepare($sql);
		$keyword=escapeString($keyword);
		$keyword=str_replace(' ','%',$keyword);
		$stmt->bindValue(":keyword" ,"%$keyword%");
		$stmt->bindValue(":limit" ,$view,PDO::PARAM_INT);
		$stmt->bindValue(":offset" ,($page-1)*$view,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();

		return $this->parseTags($result);
	}
	/*************************************
	指定ユーザの投稿した動画を検索
	*************************************/
	public function userVideo($name,$page,$view){
		$sql = "select sql_calc_found_rows ".VIDEOS_COLUMM.",group_concat(tag) as tags from videos left join tags on videos.id = tags.video_id where deleted is NULL and user_id = :user_id group by id  order by created desc limit :limit offset :offset;";

		$stmt = $this->db->prepare($sql);
		$keyword=escapeString($name);
		$stmt->bindValue(":user_id" ,$name);
		$stmt->bindValue(":limit" ,$view,PDO::PARAM_INT);
		$stmt->bindValue(":offset" ,($page-1)*$view,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();

		return $this->parseTags($result);
	}
	/*************************************
	タグをパース
	*************************************/
	private function parseTags($videos){
		foreach($videos as &$video){
			$video['tags'] = explode(',',$video['tags']);
		}
		return $videos;
	}
	
	/*************************************
	動画を有効にする
	*************************************/

	public function enable($id){
		$sql = "update videos set deleted = NULL";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
	}
	
	/*************************************
	動画を無効にする
	*************************************/

	public function disable($id){
		$sql = "update videos set deleted = 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();

	}
	
	/*************************************
	結果の総数を取得
	*************************************/
	public function getTotal(){
		$sql = "select found_rows() as rows;";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();
		return $result['rows'];
	}
	
}
