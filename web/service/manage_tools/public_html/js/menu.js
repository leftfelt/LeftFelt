Menu = {};
Menu.Mkw = Class.create({
	initialize:function(){
	},
	update_confirm:function(){
		if(window.confirm('本番環境を更新します。')){
			var ajax = new Ajax.Request( "mkw_tools/mkw_updater.php",{
				"method":"get"
			});
		}
	},
});

Menu.Page = (function(){
	var init = function(){
		var mkw = new Menu.Mkw();
		$('update_main').observe('click',mkw.update_confirm.bind(mkw));
	};
	return {
		init:init,
	};
})();

//ページ読み込み時に実行される
Event.observe(
	window,
	"load",
	Menu.Page.init
);
