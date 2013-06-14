//require common/common.js

AccountManager = {};
AccountManager.ServiceManage = Class.create({
	initialize:function(args){
		this._area = args.area;
		this._template = args.template;
		this._result = args.result;
		this._service_id = args.service_id;
	},
	addService:function(){
		var service_id = this._service_id.value;
		var request = new Common.Request({
			url:'api/addService.php',
			method:"get",
			parameter:"id="+service_id,
			type:"JSON",
			});
		request.after(this._success.bind(this));
		request.execute();
	},
	deleteService:function(){
		var service_id = this._service_id.value;
		var request = new Common.Request({
			url:'api/deleteService.php',
			method:"get",
			parameter:"id="+service_id,
			type:"JSON",
			});
		request.after(this._success.bind(this));
		request.execute();
	},
	updateServiceList:function(){
		var service_id = this._service_id.value;
		var request = new Common.Request({
			url:'api/getServiceList.php',
			method:"get",
			type:"JSON",
			});
		request.after(this._update.bind(this));
		request.execute();
	},
	_success:function(request){
		var response = request.json;
		if(response.result){
			this._result.update("完了");
		}else{
			this._result.update("失敗");
		}
		this.updateServiceList().bind(this);
	},
	_update:function(request){
		var response = request.json;
		if(response.service_list){
			var service_list = response.service_list;
			
			var html_list = service_list.map(this._replace.bind(this));
			this._area.update(html_list.join("\n"));
		}
	},
	_replace:function(object,index){
		return Common.TemplateReplace(this._template,object);
	}
});

//もっときれいにかけるはず
AccountManager.ServiceManagePage = (function(){
	var init = function(){
		var service_manage = new AccountManager.ServiceManage({
			service_id:$("service_id"),
			area:$("service_list_area"),
			template:$F("service_list_js_template"),
			result:$("result")
		});
		$("add_button").observe(
			'click',
			service_manage.addService.bind(service_manage)
		);
		$("delete_button").observe(
			'click',
			service_manage.deleteService.bind(service_manage)
		);
		service_manage.updateServiceList();
	}
	return {
		init:init
	};
})();

//ページ読み込み時に実行される
Event.observe(
	window,
	"load",
	AccountManager.ServiceManagePage.init
);
