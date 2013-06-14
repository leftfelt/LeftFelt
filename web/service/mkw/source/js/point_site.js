//require common/common.js

Mkw = {};
Mkw.PointSite = Class.create({
	initialize:function(args){
		this._area = args.area;
		this._template = args.template;
	},
	updatePointSite:function(response){
		if(response){
			this._point_site = response;
			
			var html = Common.TemplateReplace(this._template.this._point_site);
			this._area.update(html);
		}
	}
});

//もっときれいにかけるはず
Mkw.PointSitePage = (function(){
	var point_site = new Mkw.PointSite({
		area:$("point_site_info_area"),
		template:$F("point_site_info_template"),
	});
	var init = function(){
		point_site.updatePointSite();
	};
	return {
		init:init,
	};
})();

//ページ読み込み時に実行される
GetPointSiteResult = function(response){
	Mkw.PointSite.init(response);
}
