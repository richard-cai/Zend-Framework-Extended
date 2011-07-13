(function($) {
   $.fn.ajaxload = function(options) {
     //set main options before element iteration
     var opts = $.extend({},$.fn.ajaxload.defaults,(options)?options:{});
     
     this.each(function() {
    	var $this = $(this);
    	//build element specific options
		var o = $.metadata ? $.extend({},opts,$this.metadata()):opts;
		if(!(o.ajaxload_url.length > 0 && o.ajaxload_target.length >0)){
    		throw 'plz specify required url and target for ajaxloading';
    	}
		
		var t = $(o.ajaxload_target),width=t.width(),d;
		t.css('position','relative');
		eval("$this."+o.ajaxload_event+"(function(){var f=$this.closest('form'); if(f.find('.js_input').validate().data('jquery_validate_rs')){ d = f.serialize();t.append('<div class=\"loading-bar\">loading....</div>').find('.loading-bar').css('height',t.height()).animate({width:width/2},300);setTimeout(function(){$.ajax({type:'"+o.ajaxload_method+"',url:'"+o.ajaxload_url+"',data:d,success:success})},200);}return false})");
    	var success = function(data,textStatus){
    		t.find('.loading-bar').animate({width:width},500,function(){
    			t.html(data);
    		});
    	};
     });
     return this;
   };
   //default options for ajaxload plugin
   $.fn.ajaxload.defaults = {'ajaxload_target':'','ajaxload_event':'click','ajaxload_url':'','ajaxload_method':'Get'};
 })(jQuery);
