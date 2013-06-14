/**
 * サイト全体で使う共通のJS
 *
 */

Common = (function(){
	//テンプレート置換用の正規表現
	var TEMPLATE_VARIABLES = /(^|.|\r|\n)(#\[(.*?)\])/;
	// #[]内を置換する
	var TemplateReplace = function(template,object){
		return template.interpolate(object,TEMPLATE_VARIABLES);
	};
	//Ajaxクラスのリクエスト送信を分離したクラス
	var Request = Class.create({
		initialize:function(args){
			this._url = args.url;
			this._method = args.method;
			this._parameter = args.parameter;
			this._type = args.type;
		},
		execute:function(){
			var request = new Ajax.Request(
				this._url,{
				"method":this._method,
				"parameters":this._parameter,
				onSuccess:this._success.bind(this)
			});
		},
		_success:function(response){
			if(this._type == "JSON"){
				response.json = eval("("+response.responseText+")");
			}
			this._after(response);
		},
		after:function(callback){
			this._after = callback;
		}
	});
	return {
		Request:Request,
		TemplateReplace:TemplateReplace}
})();


