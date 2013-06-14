<div align="right" id='bar'>
	<a href="index.php">トップ</a>
	<a href="ranking.php">ランキング</a>
	{{if !isset($name)}}
		<a href="login.php">ログイン</a>
	{{else}}
		<a href="paintchat.php">お絵描きチャット</a>
		<a href="mypage.php">マイページ</a>
		<b id="user_id">{{$name|escape}}</b>
		<a href="logout.php">ログアウト</a>
	{{/if}}
</div>

