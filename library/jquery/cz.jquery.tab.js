(function($) {
	   $.fn.tab = function(options) {
	     //set main options before element iteration
	     var opts = $.extend({},$.fn.tab.defaults,(options)?options:{});
	     
	     //current index
	     var c_index;
	     
	     this.each(function() {
	    	$this = $(this);
			//build element specific options
			var o = $.metadata ? $.extend({},opts,$this.metadata()):opts;
			//get content sections
			var sections = $this.find('>div'); 
			//get tabs
			var tabs = $this.find('>ul li');
			//set click event
			tabs.click(function(e){
				var index = tabs.index(this);
				if(index != c_index){
						tabs.each(function(){
							if($(this).hasClass(o.current)){
								$(this).removeClass(o.current);	
							}
						});
						sections.eq(c_index).animate({'opacity':0.5},100,function(){
						sections.eq(c_index).hide();
						sections.eq(index).css('opacity',0.5).show().animate({'opacity':1},100);
						c_index = index;
					});
					$(this).addClass(o.current);
				}
				return false;
			}).eq(o.selected).addClass(o.current);
			//hide nonselected sections
			sections.each(function(){
				  var index = sections.index(this);
				  if(index != o.selected){
					$(this).hide();  
				  }
			});
			//set current index
			c_index = o.selected;
	     });
	     return this;
	   };
	   
	   //default options for tab plugin
	   $.fn.tab.defaults = {
			   'selected':0,
			   'current':'current'
	   };
	 })(jQuery);