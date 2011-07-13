(function($) {
   $.fn.dialog = function(options) {
     //set main options before element iteration
     var opts = $.extend({},$.fn.dialog.defaults,(options)?options:{});
     
     this.each(function() {
    	var $this = $(this);
    	$this.addClass(opts.default_cls);
    	$('<a href="#" class="'+opts.close_cls+'"></a>').appendTo($this).click(function(){
    		$this.hide();
    		return false;
    	});;
    	
     });
     return this;
   };
   //default options for tab plugin
   $.fn.dialog.defaults = {
		   'default_cls':'dialogpanel',
		   'close_cls':'close'
   };
 })(jQuery);