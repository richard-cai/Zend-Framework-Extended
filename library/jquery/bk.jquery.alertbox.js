(function($) {
	   $.fn.alertBox = function(options) {
	     //set main options before element iteration
	     var opts = $.extend({},$.fn.alertBox.defaults,(options)?options:{}),show,t;
	     
	     this.each(function() {
	    	var $this = $(this);
	    	//build element specific options
			var o = $.metadata ? $.extend({},opts,$this.metadata()):opts,d=new Date(),
					timestamp = d.getTime(),
					id="alert_"+timestamp,
					msg = $this.html(),
					s,
					html = '<div class="alertBox '+o.alertBox_cls+'" id="'+id+'"><div class="innerWrap">'+msg+'</div></div>';
			if(msg.length == 0) return false;
			$('body').append(html);
			t = $("div#"+id);
			t.hover(function(){
				if(s!=undefined){
					clearTimeout(s);
				}
			},function(){
				remove();
			}).find('.innerWrap').corner();
			var remove = function(){
				t.fadeTo("slow",o.alertBox_opacity,function(){
					t.remove();
					if(o.alertBox_callback != ''){
						o.alertBox_callback.call(o.alertBox_range);
					}
				});
			};
		    show = function(){
				/*get msg box position*/
				if(!($.browser.msie && $.browser.version == '6.0')){
					var right = 10+($("div.alertBox").size()-1)*160+"px";
					t.css({'right':right,'bottom':'10px','position':'fixed','opacity':0});
				}else{
					var left = document.documentElement.clientWidth - ($("div.alertBox").size())*200-30+"px";
					t.css({'left':left,'opacity':0,'position':'absolute'});
				}
				t.show().animate({opacity:1},1000);
				s = setTimeout(function(){remove();},o.alertBox_delay);
			};
	     });
	     return {
	    	'self':function(){
	    	 	return this;
	     	},
	     	'show':function(){
	     		show();
	     	},
	     	'msg':function(){
	     		return t;
	     	}
	     };
	   };
	   //default options for alertBox plugin
	   $.fn.alertBox.defaults = {
			  'alertBox_delay':5000,
			  'alertBox_callback':'',
			  'alertBox_range':'',
			  'alertBox_opacity':0.1,
			  'alertBox_cls':''
	   };
	})(jQuery);