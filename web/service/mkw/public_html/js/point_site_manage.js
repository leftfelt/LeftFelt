//require common/common.js

MkwManage = {};
MkwManage.PointSiteManage = Class.create({
	initialize:function(args){
		this._area = args.area;
		this._edit_area = args.edit_area;
		this._template = args.template;
		this._edit_template = args.edit_template;
		this._delete_template = args.delete_template;
		this._result = args.result;
		this._point_site_list = {};
	},
	updateEditArea:function(name){
		var html = Common.TemplateReplace(
			this._edit_template,
			this.popByName(name)
		);
		this._edit_area.update(html);
		$('send_button').observe('click',this.deletePointSite.bind(this));
		$('send_button').observe('click',this.addPointSite.bind(this));
		$('close_button').observe('click',this.hideEditArea.bind(this));
		this._edit_area.show();
	},
	popByName:function(name){
		for(var i in this._point_site_list){
			if(this._point_site_list[i].name == name){
				return this._point_site_list[i];
			}
		}
	},
	showEditArea:function(){
		var html = Common.TemplateReplace(this._edit_template,{});
		this._edit_area.update(html);
		$('send_button').observe('click',this.addPointSite.bind(this));
		$('close_button').observe('click',this.hideEditArea.bind(this));
		this._edit_area.show();
	},
	showDeleteArea:function(){
		this._edit_area.update(this._delete_template);
		$('send_button').observe('click',this.deletePointSite.bind(this));
		$('close_button').observe('click',this.hideEditArea.bind(this));
		this._edit_area.show();
	},
	hideEditArea:function(){
		this._edit_area.hide();
	},
	addPointSite:function(){
		var request = new Common.Request({
			url:'api/addPointSite.php',
			method:"get",
			parameter:
			"name="+$('name').value+
			"&description="+$('description').value+
			"&rate="+$('rate').value+
			"&to_webmoney="+$('to_webmoney').value+
			"&min_pay="+$('min_pay').value+
			"&exchange_place="+$('exchange_place').value+
			"&how_to_get_point="+$('how_to_get_point').value+
			"&register_info="+$('register_info').value+
			"&register_link="+$('register_link').value+
			"&banner_link="+$('banner_link').value
			,
			type:"JSON",
			});
		request.after(this._success.bind(this));
		request.execute();
	},
	deletePointSite:function(){
		var request = new Common.Request({
			url:'api/deletePointSite.php',
			method:"get",
			parameter:"name="+$('name').value,
			type:"JSON",
			});
		request.after(this._success.bind(this));
		request.execute();
	},
	updatePointSiteList:function(){
		var request = new Common.Request({
			url:'api/getPointSiteList.php',
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
		this.updatePointSiteList().bind(this);
	},
	_update:function(request){
		var response = request.json;
		if(response.point_site_list){
			this._point_site_list = response.point_site_list;
			
			var html_list = this._point_site_list.map(this._replace.bind(this));
			this._area.update(html_list.join("\n"));
		}
	},
	_replace:function(object,index){
		return Common.TemplateReplace(this._template,object);
	}
});

//もっときれいにかけるはず
MkwManage.PointSiteManagePage = (function(){
	var point_site_manage = new MkwManage.PointSiteManage({
		area:$("point_site_list_area"),
		edit_area:$("point_site_edit_area"),
		template:$F("point_site_list_js_template"),
		edit_template:$F("point_site_edit_js_template"),
		delete_template:$F("point_site_delete_js_template"),
		result:$("result")
	});
	var init = function(){
		$("add_button").observe(
			'click',
			point_site_manage.showEditArea.bind(point_site_manage)
		);
		$("delete_button").observe(
			'click',
			point_site_manage.showDeleteArea.bind(point_site_manage)
		);
		point_site_manage.updatePointSiteList();
	};
	var edit = function(name){
		point_site_manage.updateEditArea(name);
	};
	return {
		init:init,
		edit:edit
	};
})();

//ページ読み込み時に実行される
Event.observe(
	window,
	"load",
	MkwManage.PointSiteManagePage.init
);
