AccountManager = {};
AccountManager.UserManage = Class.create({
	initialize:function(args){
		this._area = args.area;
		this._template = args.template;
		this._result = args.result;
		this._service_id = args.service_id;
		this._user_id = args.user_id;
		this._password = args.password;
		this._permission_id = args.permission_id;
	},
	editUser:function(){
		alert('編集ボタン');
	},
	deleteUser:function(){
		var service_id = this._service_id.value;
		var user_id = this._user_id.value;
	
		var request = new Common.Request({
			url:'api/deleteUser.php',
			method:"get",
			parameter:"service_id="+service_id+"&user_id="+user_id,
			type:"JSON",
			});
		request.after(this._success.bind(this));
		request.execute();
	},
	addUser:function(){
		var service_id = this._service_id.value;
		var user_id = this._user_id.value;
		var password = this._password.value;
		var permission_id = this._permission_id.value;

		var request = new Common.Request({
			url:'api/addUser.php',
			method:"get",
			parameter:"service_id="+service_id+"&user_id="+user_id+"&password="+password+"&permission_id="+permission_id,
			type:"JSON",
			});
		request.after(this._success.bind(this));
		request.execute();
	},
	updateUserList:function(){
		var service_id = this._service_id.value;
		var request = new Common.Request({
			url:'api/getUserList.php',
			method:"get",
			parameter:"service_id="+service_id,
			type:"JSON",
			});
		request.after(this._update.bind(this));
		request.execute();
	},
	_update:function(request){
		var response = request.json;
		if(response.user_list){
			var user_list = response.user_list;
			
			var html_list = user_list.map(this._replace.bind(this));
			this._area.update(html_list.join("\n"));
		}
	},
	_success:function(request){
		var response = request.json;
		if(response.result){
			this._result.update("完了");
		}else{
			this._result.update("失敗");
		}
		this.updateUserList().bind(this);
	},
	_replace:function(object,index){
		return Common.TemplateReplace(this._template,object);
	}
});

AccountManager.UserManagePage = (function(){
	var init=function(){
		var user_manage = new AccountManager.UserManage({
			service_id:$("service_id"),
			user_id:$("user_id"),
			password:$("password"),
			permission_id:$("permission_id"),
			area:$("user_list_area"),
			template:$F("user_list_js_template"),
			result:$("result")
		});
		$("send_button").observe(
			'click',
			user_manage.updateUserList.bind(user_manage)
		);
		$("add_button").observe(
			'click',
			user_manage.addUser.bind(user_manage)
		);
		$("edit_button").observe(
			'click',
			user_manage.editUser.bind(user_manage)
		);
		$("delete_button").observe(
			'click',
			user_manage.deleteUser.bind(user_manage)
		);
		user_manage.updateUserList();
	};
	return {
		init:init,
	}
})();

//ページ読み込み時に実行される
Event.observe(
	window,
	"load",
	AccountManager.UserManagePage.init
);
