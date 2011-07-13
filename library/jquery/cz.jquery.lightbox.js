(function($) {
	   $.fn.lightbox = function(options) {
	     //set main options before element iteration
	     var opts = $.extend({},$.fn.lightbox.defaults,(options)?options:{});
	     var cp = '';
	     var wrap = '';
	     var box = '';
	     var remove = '';
	     var title = opts.top;
	     var bottom = opts.bottom;
	     if(this.length > 1)
	    	 throw "The lightbox plugin only support single instance";
	     
	     if(opts.backend){
	    	 var tp = $('#content');
	     }else{
	    	 var tp = ($.browser.msie)?$('#content').parent():'body';
	     }
	     this.each(function() {
	    	var $this = $(this);
	    	//build element specific options
		    var o = $.metadata ? $.extend({},opts,$this.metadata()):opts;
		    title = o.top;
		    bototm = o.bottom;
	    	box = $('#light_box');
	    	//box exist
	    	if(box.length){
	    		box.show();
	    	}else{
	    		box = $('<div id="light_box"></div>').appendTo(tp).css('opacity',0.5).height($('body').height()+10);
	    	}
	    	if($.browser.msie && $.browser.version == '6.0'){
	    		$('#content').addClass('hideSelect');
	    	}
	    	//get the cloned element
	    	cp = $this.clone(true);
	    	//append copied element to box
	    	cp = cp.appendTo(tp).show()
	    		   .wrap('<div class="lightbox_wrap"><div class="lightbox_content"></div></div>')
	    		   .parent().before('<div class="lightbox_top"><b>'+o.top+'</b></div>')
	    		   .after('<div class="lightbox_bottom">'+o.bottom+'</div>');
	        wrap = cp.closest('.lightbox_wrap').corner().css({'position':'fixed'});
	        if($.browser.msie && ($.browser.version == '7.0' || $.browser.version == '6.0')){
	        	if($.browser.version == '6.0'){
	        		var c = $('.lightbox_content');
	        		var wh = parseInt(c.css('padding'))*2+c.css('display','inline').width()+parseInt(cp.css('padding'))*2;
	        		c.css('display','block');
	        		wrap.width(wh);
	        	}else{
	        		var wh = wrap.width();
	        	}
	        	//jquery corner bug fixed for lightbox
	        	wrap.find('.lightbox_top').css({'display':'block','width':wh+'px','text-align':'left'});
	        	$('#cctopcontainer').css('left','1px');
	        	$('#ccbottomcontainer').css('left','1px');
	        }
	        //get box left position
	        var left = ($(window).width() - cp.width()-parseInt(cp.css('paddingRight'))-parseInt(cp.css('paddingLeft'))-20)/2 + "px";
	    	var top = ($(window).height() - cp.height()-parseInt(cp.css('paddingBottom'))-parseInt(cp.css('paddingTop'))-80)/2 + "px";
	    	wrap.css({
	    		'left':left,
	    		'z-index':100000,
	    		'display':'block',
	    		'margin':0
	    	});
	    	if($.browser.msie && $.browser.version == '6.0'){
	    		wrap.css({
	    			'position':'absolute'
	    		}).addClass('lightboxie');
	    	}else{
	    		wrap.css({
		    		'top':top
		    	});
	    	}
	    	//remove the box
	    	remove = function(){
	    		var delay = arguments[0];
	    		if($.browser.msie && $.browser.version == '6.0'){
		    		$('#content').removeClass('hideSelect');
		    	}
	    		if(delay != undefined){
	    			setTimeout(function(){
	    				wrap.remove();
	    				box.hide();
	    			},delay);
	    		}else{
	    			wrap.remove();
	    			box.hide();
	    		}
	    		return this;
	    	};
	    	//add close button to the box
	    	$('<a href="" class="lightbox_close">Close</a>').appendTo(wrap.find('.lightbox_top')).click(function(){
	    		remove();
	    		return false;
	    	});
	    	box.click(function(){
	    		remove();
	    	});
	   	 });
	     return {
	    	'find':function(){
	    	 	return cp.find(arguments[0]);
	     	},
	     	'close':function(){
	     		remove(arguments[0]);
	     	},
	     	'relocate':function(){
	     		wrap.remove();
	     		if($.browser.msie && $.browser.version == '6.0'){
		    		$('#content').removeClass('hideSelect');
		    	}
	     		return cp.find(':first').lightbox({'top':title,'bottom':bottom});
	     	},
	     	'setTitle':function(t){
	     		title = t;
	     	},
	     	'setBottomText':function(t){
	     		bottom = t;
	     	},
	     	'setCloseButton':function(obj){
	     		obj.click(function(){
	     			$('.lightbox_close').trigger('click');
	     		});
	     	},
	     	'self':function(){
	     		return cp;
	     	}
	     };
	   };
	   var date = new Date();
	   //default options for tab plugin
	   $.fn.lightbox.defaults = {
			'top':'This is a lightbox default top title.',
			'bottom':'Copyright &copy; 2007 - '+date.getFullYear()+' Crazy Sales. All rights reserved.',
			'backend':false
	   };
	 })(jQuery);