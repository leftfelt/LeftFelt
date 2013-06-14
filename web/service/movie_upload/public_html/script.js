var cnt = 0;//表示中のコメント数
var base = 0;//表示位置
var commentdata = null;//コメントデータ
var readingComment;
var count = 0;
var displayflag = true;

//キーワード検索
function searchKeyword(){
	var keyword = document.getElementById('keyword').value;
	location.href="search.php?keyword="+keyword;
}
//タグ検索
function searchTag(){
	var keyword = document.getElementById('keyword').value;
	location.href="search.php?tag&keyword="+keyword;
}
function tagEdit(){
	var tagedit = document.getElementById('tagEdit');
	if(getStyle(tagedit).display == 'none'){
		document.getElementById('edit').innerHTML='【閉じる】';
		tagedit.style.display = 'block';
	}else{
		document.getElementById('edit').innerHTML='【編集】';
		tagedit.style.display = 'none';
	}
}
//タグ追加
function addTag(){
	var tag = document.getElementById('addtag').value;
	var id = getQuery()['id'];
	if(tag == '') return;
	serverSendData('thread.php','type=addtag&id='+id+"&tag="+tag);
	
	//タグリストに追加
	var tags = document.getElementById('tagEdit');
	var func = "deleteTag('"+tag+"');";
	tags.innerHTML += "<div name='tag'>"+tag+"<input type='button' value='削除' onclick=\""+func+"\"></div>";
	//タグ検索リンクを追加
	tags = document.getElementById('tags');
	tags.innerHTML += "<a href='search.php?tag&keyword="+tag+"'>"+tag+"</a> ";
}
//タグ削除
function deleteTag(tag){
	var id = getQuery()['id'];
	var tags = document.getElementsByName('tag');
	var num = 0;
	serverSendData('thread.php','type=deletetag&id='+id+'&tag='+tag);
	
	//タグリストから削除
	for(var i=0 ; i<tags.length ; i++){
		if(tags[i].innerText == tag){
			tags[i].parentNode.removeChild(tags[i]);
			num = i;
		}
	}
	
	//タグ検索リンクを削除	
	tags = document.getElementById('tags');
	tag = tags.getElementsByTagName('a');
	tags.removeChild(tag[num]);
	
}
//コメント投稿
function pushComment(){
	var video = document.getElementById('video');
	var comment = document.getElementById('inputcomment').value;
	var user_id = document.getElementById('user_id').innerText;
	var start = video.currentTime;
	var time = 3;
	var speed = time / parseInt(video.width);
	var end = addComment(comment,user_id,start,speed);
	
	var docomment = document.getElementById('docomment');
	var input = document.getElementById('inputcomment');
	if(comment == '')return;
	
	docomment.disabled = true;//連コメ防止
	input.disabled = true;
	setTimeout("document.getElementById('docomment').disabled = false;document.getElementById('inputcomment').disabled=false;",
		5000
		);


	sendComment(comment,start,end,speed);//コメントデータをサーバに送信
	
	cmt = commentdata.firstChild;
	child = document.createElement('comment');
	child.setAttribute('user_id',user_id);
	child.setAttribute('start',start);
	child.setAttribute('end',end);
	child.setAttribute('no',1);
	child.setAttribute('speed',speed);
	cmt.appendChild(child);
}

//コメント追加
function addComment(comment,user_id,start,speed){
	var comments = document.getElementById('comments');
	comments.innerHTML += "<span name='comment' class='comment' data-start='"+start+"'>"+comment+"</span>";
	cmt = comments.getElementsByTagName('span');
	
	var video = document.getElementById('video');
	var width = comment.length * parseInt(getStyle(cmt[cmt.length-1]).fontSize);
	var end = parseFloat(start) + (parseInt(video.width) + width) * speed;

	initComment(cmt[cmt.length-1]);//初期化
	return end;
}
//コメント送信
function sendComment(comment,start,end,speed){
	var id = getQuery()['id'];
	var data = 'type=comment&id='+id+'&comment='+comment+'&start='+start+'&end='+end+'&speed='+speed;
	serverSendData('thread.php',data);
}
//コメント全体初期設定
function initComments(){
	var comment = document.getElementsByName('comment');

	for(var i = 0 ; i < comment.length ; i++){
		initComment(comment[i]);
	}
}

//コメント初期設定
function initComment(comment){
	var video = getStyle(document.getElementById('video'));
	var style = getStyle(comment);

	comment.style.zIndex = style.zIndes;
	comment.style.left = video.width;
	comment.style.width = (comment.innerText.length * parseInt(style.fontSize)) + 'px';
}

//再生ボタンが押された
function procComments(){
	setInterval('moveComments();',1);
	var id = getQuery()['id'];
	var ticket = document.getElementById('ticket').value;
	serverSendData("thread.php",'type=video&id='+id+'&ticket='+ticket);
}

//コメントを移動させる
function moveComments(){
	var comment = document.getElementsByName('comment');
	var video = document.getElementById('video');
	var comments = commentdata.getElementsByTagName('comment');
	
var test=0;
	cnt=0;
	base=0;	

	for(var i = 0 ; i < comment.length ; i++){
		var video = document.getElementById('video');
		if(typeof(comment[i])!='object') continue;
		var start = parseFloat(comments[i].getAttribute('start'));
		var end = parseFloat(comments[i].getAttribute('end'));
		var style = comment[i].style;
		if(start < video.currentTime && video.currentTime < end && displayflag == true){
			var speed = parseFloat(comments[i].getAttribute('speed'));
			

			//移動中
			var pos = video.width - (video.currentTime-start) / speed;
			if(isNaN(parseInt(style.top))){
				style.top = cnt*parseInt(getStyle(comment[i]).fontSize)+base+'px';
			}
			
			style.left = pos + 'px';
			cnt = cnt + 1;
			//改行
			if(parseInt(getStyle(video).height)/parseInt(getStyle(comment[i]).fontSize)-3 < cnt){
				cnt = 0;
				test++;
				
				if(base == 0){
					base = parseInt(getStyle(comment[i]).fontSize)/2;
				}else{
					base = 0;
				}
			}
		}else{
			style.left = video.width;
		}
	}
}

function dispComment(){
	var check = document.getElementById('dispcomment');
	
	displayflag = check.checked;
}

//コメントの読込
function readComments(){
	var ajax = new XMLHttpRequest();
	var query = getQuery();
	ajax.open('GET','comments/'+query['id']+'.xml',true);
	ajax.onreadystatechange = function(){
		if(ajax.readyState === 4 && ajax.status === 200){
			commentdata = ajax.responseXML;
			comments = commentdata.getElementsByTagName('comment');
			setInterval('setComment();',0.1);	
			console.log('comments loading finished');
		}
	}
	console.log('comments loading');
	ajax.send(null);
}


function setComment(){	
	var data = commentdata.getElementsByTagName('comment');	
	if(count > data.length-1){
		 clearInterval(readingComment);
		return;
	}
	addComment(
		comment = data[count].textContent,
		data[count].getAttribute('user_id'),
		data[count].getAttribute('start'),
		data[count].getAttribute('speed')
		);
	data[count].getAttribute('no');
	count++;
}

//CSSの値を取得
function getStyle(element){
	return (element.currentStyle || document.defaultView.getComputedStyle(element,''));
}

function test(){
	alert('test');
}

//データ送信
function serverSendData(url,data){
	var ajax = new XMLHttpRequest();
	ajax.open('POST',url,true);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.onreadystatechange = function(){
		if(ajax.readyState === 4 && ajax.status === 200){
			//document.write(ajax.responseText);
		}
	}
	
	ajax.send(data);
}



//クエリ変数を連想配列で受け取る
function getQuery(){
	var query = location.search.split("?")[1];
	var result = {};
	if(query != ""){
		var tmp = query.split("&")
		for(i in tmp){
			result[tmp[i].split("=")[0]] = tmp[i].split("=")[1];
		}
	}
	return result;
}

//ファイルアップロード
function sendUploadFile(){
	var form = document.getElementById("updata");
	var data = new FormData(form);
	var ajax = new XMLHttpRequest();
	
	//アップロードボタン無効
	document.getElementById('upload').disabled=true;
	document.getElementById('video').disabled=true;
	document.getElementById('title').disabled=true;
	document.getElementById('description').disabled=true;
	ajax.onreadystatechange = function(){
		var result = document.getElementById('upstatus');
		if(ajax.readyState === 4){
			if('encoded' == ajax.responseText){
				result.innerHTML = '変換完了';
			}else if('expire ticket' == ajax.responseText){
				result.innerHTML = '有効期限が切れています'
			}else{
				result.innerHTML = '変換失敗<br>サイズが大きすぎるか対応していない形式です';
			}
			document.getElementById('upload').disabled=false;
			document.getElementById('video').disabled=false;
			document.getElementById('title').disabled=false;
			document.getElementById('description').disabled=false;
		}
	}


	ajax.upload.onprogress = function(e){
		var result = document.getElementById('upstatus');
		result.innerHTML = 'アップロード中:'+e.total*1000+'KB中'+e.loaded*1000+'KB完了';

		console.log('uploading:'+e.loaded);
	}
	ajax.upload.onloadstart = function(e){
		var result = document.getElementById('upstatus');
		result.innerHTML = 'アップロード開始';

		console.log('upstart');
	}
	ajax.upload.onloadend = function(e){
		var result = document.getElementById('upstatus');
		result.innerHTML = 'アップロード完了<br>変換中です。しばらくお待ちください。';

		console.log('upend');
	}
	ajax.upload.onloadtimeout = function(e){
		console.log('uptimeout');
	}
	ajax.upload.onabort = function(e){
		console.log('upabort');
	}
	ajax.upload.onerror = function(e){
		console.log('uperror');
	}
	ajax.upload.onload = function(e){
		console.log('upsuccess');
	}
	ajax.open(form.method, form.action,true);
	ajax.send(data);
}
