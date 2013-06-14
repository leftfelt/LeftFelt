(function (){
	var save_confirm = function(){
		if(window.confirm('保存します。')){
			var ajax = new Ajax.Request( "mkw_change_tool.php",{
				"method":"post",
				"parameters":"dir="+$('directory').innerHTML+'&'+"contents_data="+encodeURIComponent($F('contents')),
				onComplete:function(request){
					var json = request.responseText.evalJSON();
					if(json.status == true){
						alert('保存しました');
					}else{
						alert(json.message);
					}
				}
			});
		}
	};
	var contents_save = $('contents_save');
	if(contents_save){
		contents_save.observe('click',save_confirm);
	}
})();
