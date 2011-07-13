(function($) {
	   $.fn.infobox = function(options) {
	     //set main options before element iteration
	     var opts = $.extend({},$.fn.infobox.defaults,(options)?options:{});
	     var cssinfobox = {
	    		 'border':'1px solid #DCDCDC',
	    		 'padding':'10px 20px',
	    		 'position':'relative',
	    		 'z-index':'999',
	    		 'background-color':'#FFFFCC',
	    		 'width':opts.width
	     };
	     var cssinfoboxshadow = $.extend({},cssinfobox,{'z-index':'998',
	    	 											'background-color':'#DCDCDC',
	    	 											'color':'#DCDCDC',
	    	 											'position':'absolute',
	    	 											'left':'1px',
	    	 											'top':'1px'
	     												});
	     this.each(function() {
	    	//build element specific options
	    	var $this = $(this),o = $.metadata ? $.extend({},opts,$this.metadata()):opts;
	    	if(o.box_padding != undefined){
	    		cssinfoboxshadow.padding = cssinfobox.padding = o.box_padding; 
	    	}
	    	if(o['border'] != undefined){
	    		cssinfobox['border']  = o['border'];
	    	}
	    	if(o.target.length == 0)
	    		throw 'target needs to be specified.';
	    	var target = $('#'+o.target),box='',t,flag = false;
	    	$this.hover(function(){
			if(box != ''){
				if(box.css('display') != 'none'){
					clearTimeout(t);
					return false;
				}
				box.css('display','block');
			}else{
		    		var cp = target.remove();
		    		if(o.shadow){
		    			var sa = cp.clone();
		    			sa.find('input,select').attr('disabled','disabled');
		    		}
		    		$('<div class="'+o.arrow_cls+'"></div>').appendTo(cp).css('right',o.right+'px');
		    		cp.removeClass(o.hide_cls).css(cssinfobox).addClass(o.cls).width(o.width);
		    		if(o['background-color'] != undefined){
			    		cp.css('backgroundColor',o['background-color']);
			    	}
			    	if(o['border'] != undefined){
			    		cp.css('border',o['border']);
			    	}
		    		cp.corner(o.options);
		    		if(o.shadow){
		    			sa.removeClass(o.hide_cls).css(cssinfoboxshadow).width(o.width).corner(o.options);
		    		}
		    		$this.parent().css('position','relative');
		    		box = cp.wrap('<div class="infobox_wrap" id="'+o.id+'"></div>').parent();
		    		if(o.shadow){
		    			box.append(sa);
		    		}
		    		$this.after(box);
		    		if($.browser.msie && $.browser.version == '6.0'){
		    			box.append('<iframe src="/blank.html" width="'+box.width()+'" height="'+box.height()+'" scrolling="no" frameBorder="0"></iframe>');
		    			box.find('iframe').each(function(){
		    				this.document.execCommand('Stop');
		    			});
		    		}
		    		box.mouseenter(function(){
		    			flag = true;
		    		}).mouseleave(function(){
		    			flag = false;
		    			clearTimeout(t);
		    			t = setTimeout(function(){
			    			 if(!flag){
						    	box.css('display','none');
			    			 }
						    },o.delay);
		    		});
			}
	    		return false;
	    	},function(){
	    		flag = false;
	    		clearTimeout(t);
	    		t = setTimeout(function(){
	    			 if(!flag){
	    				box.css('display','none');
	    			 }
				    },o.delay);
	    		
	    	});
	    	if(!o.linkable){
	    		$this.click(function(){
	    			return false;
	    		});
	    	}
	   	 });
	     return this;
	   };
	   
	   //default options
	   $.fn.infobox.defaults = {
			'id':'',
			'target':'',
			'cls':'',
			'hide_cls':'hidden',
			'options':'',
			'delay':500,
			'linkable':false,
			'right':'30',
			'width':'200px',
			'arrow_cls':'infobox_arrow_down',
			'shadow':true
	   };
	 })(jQuery);