var DATA_SIZE = 6;
var socket;
var pt1 = new Object();
var pt2 = new Object();
var flag = 0;
var selectedCommand = 'line';

var CommandList = new Array(
		'line',
		'rect',
		'circle'
	);
var StatusList = new Array(
		'start',
		'move',
		'end'
	);
var commState = new Array(
		'enter',
		'connected',
		'leave'
);
var Commands = new Uint8Array();

//読込時の処理
window.onload = function(){
	socketOpen();
	var canvas = document.getElementById('canvas');
	canvas.addEventListener('mousedown',startPoint,false);
	canvas.addEventListener('mouseup',endPoint,false);
	canvas.addEventListener('mousemove',moveMouse,false);
	setInterval('sendCommands();',500);//定期的にコマンドを送信
}

//マウス関係
function startPoint(e){
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');
	
	ctx.beginPath();
	flag = 1;
	pt1.x = getMousePosition(e).x;
	pt1.y = getMousePosition(e).y;
	ctx.moveTo(pt1.x,pt1.y);
	var data = createCommand(selectedCommand,'start',pt1.x,pt1.y);
	writeCommand(data);//コマンド追加
}

function endPoint(e){
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');
	
	flag = 0;
	pt2.x = getMousePosition(e).x;
	pt2.y = getMousePosition(e).y;
	ctx.lineTo(pt2.x,pt2.y);
	ctx.stroke();
	var data = createCommand(selectedCommand,'end',pt2.x,pt2.y);
	writeCommand(data);//コマンド追加
}

function getMousePosition(e){
	var obj = new Object();
	
	var rect = e.target.getBoundingClientRect();
	obj.x = e.clientX - rect.left;
	obj.y = e.clientY - rect.top;
	
	return obj;
}

function moveMouse(e){
	if(flag == 1){
		var canvas = document.getElementById('canvas');
		var ctx = canvas.getContext('2d');
	

		pt2.x = getMousePosition(e).x;
		pt2.y = getMousePosition(e).y;
		ctx.lineTo(pt2.x,pt2.y);
		ctx.stroke();
		var data = createCommand(selectedCommand,'move',pt2.x,pt2.y);
		writeCommand(data);
		//socket.send(data.buffer);//座標を送信
	}
}
//ソケット開く
function socketOpen(){
	//proxyを通していると動かない
	socket = new WebSocket('ws://'+location.host+':8001');
	socket.binaryType = 'arraybuffer';//バイナリデータを送信
	
	socket.onmessage = function(event){//データ受信
		var data = new Uint8Array(event.data);
		var num = data.length / DATA_SIZE;
		var pos = 0;
		/*****************************************
		 * 1バイト目 通信用フラグ
		 * 2バイト目 コマンド＆ステータス
		 * 3,4バイト目 X座標
		 * 5.6バイト目 Y座標
		 *****************************************/
		 for(var i = 0; i < num ; i++){
			pos = i*DATA_SIZE;
			var com = data[pos+0];
			switch(commState[com]){
				case 'enter':
					//入室通知
					alert('aa');
					break;
				case 'connected':
					var command = (data[pos+1] & 0x00f0) >> 4;
					var state = data[pos+1] & 0x000f;
					var x = (data[pos+2] << 8) + data[pos+3];
					var y = (data[pos+4] << 8) + data[pos+5];
					DrawReceiveData(command,state,x,y);
					break;
				case 'leave':
					//退出通知
					break;
				default:
					break;
			}
		}
	}
	socket.onerror = function(){
		console.log('error',arguments);
	}
	socket.onclose = function(){
		console.log('close');
	}
	socket.onopen = function(){
		console.log('open');
	}
}

//テスト
function testButton(){
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');

	//var data = ctx.getImageData(0,0,100,100).data;
	var x = 512;
	var y = 128;
	var byteArray = createCommand(selectedCommand,'end',x,y);//何バイトのデータを送るか
	socket.send(byteArray.buffer);
}

//送信用のコマンド
function createCommand(command,state,x,y){
	var byteArray = new Uint8Array(DATA_SIZE);//何バイトのデータを送るか
	command = getIndex(CommandList,command);//コマンド番号
	state = getIndex(StatusList,state);//ステータス番号
	byteArray[0] = 1;//通信フラグ
	byteArray[1] = (command << 4) + state;
	byteArray[2] = (x & 0xff00) >> 8;	//上位 X座標
	byteArray[3] = (x & 0x00ff);		//下位 X座標
	byteArray[4] = (y & 0xff00) >> 8;	//上位 Y座標
	byteArray[5] = (y & 0x00ff);		//下位 Y座標
	return byteArray;
}

//インデックスを取得
function getIndex(list,value){
	for(var i = 0 ; i < list.length ; i++){
		if(list[i] == value) return i;
	}
	return -1;
}
//コマンド送信
function sendCommands(){
	document.getElementById('msg').value = Commands.length;
	if(Commands.length == 0)return;
	socket.send(Commands.buffer);
	Commands = new Array();//空にする。
}

//送信用コマンドに追加
function writeCommand(command){
	var prev = Commands
	Commands = new Uint8Array(prev.length+command.length);
	Commands.set(prev);
	Commands.set(command,prev.length);
}

//受信データから描画
function DrawReceiveData(command,state,x,y){
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');

	//コマンド
	switch(CommandList[command]){
		case "line":
			switch(StatusList[state]){
				case "start":
					ctx.beginPath();
					ctx.moveTo(x,y);
					console.log('start');
					break;
				case "move":
					console.log('move');
					ctx.lineTo(x,y);
					ctx.stroke();
					break;
				case "end":
					console.log('end');
					ctx.lineTo(x,y);
					ctx.stroke();
					break;
				default:
					break;
			}
			break;
		case "rect":
			switch(StatusList[state]){
				case "start":
					ctx.beginPath();
					ctx.moveTo(x,y);
					console.log('start');
					break;
				case "end":
					console.log('end');
					ctx.lineTo(x,y);
					ctx.stroke();
					break;
				default:
					break;
			}
			break;
		default:
			break;
	}
}
