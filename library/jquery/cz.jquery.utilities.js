$.extend({
	toJsonObject:function(b){
		var a=new Object;
		$.each(b,function(){
			a[this.name]=this.value
			});
		return a;
	},
	cookie:function(){
		if(arguments.length == 1){
			var c_name = arguments[0];
			if (document.cookie.length>0)
			{
				c_start=document.cookie.indexOf(c_name + "=");
				if (c_start!=-1)
				{
				    c_start=c_start + c_name.length+1;
				    c_end=document.cookie.indexOf(";",c_start);
				    if (c_end==-1) c_end=document.cookie.length;
				    return unescape(document.cookie.substring(c_start,c_end));
				}
			}
			return "";
		}else if(arguments.length >1 & arguments.length < 5){
			var name = arguments[0];
			var value = arguments[1];
			var today = new Date();
			var expires = new Date(today.getTime() + ((arguments[2])?arguments[2]:3600*1000));
			if(arguments[3]){
				var json = arguments[3];
				var path = json.path;
				var domain = json.domain;
				var secure = json.secure;
			}
		    var cookieString = name + "=" +escape(value) +
		       ( (expires) ? ";expires=" + expires : "") +
		       ( (path) ? ";path=" + path : "") +
		       ( (domain) ? ";domain=" + domain : "") +
		       ( (secure) ? ";secure" : "");
		    document.cookie = cookieString; 
		}else{
			alert('jquery cookie utitility no supported.');
		}
	}
});