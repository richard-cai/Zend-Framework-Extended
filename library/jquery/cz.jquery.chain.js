(function($) {
   $.fn.chain = function(options) {
     //set main options before element iteration
     var opts = $.extend({},$.fn.chain.defaults,(options)?options:{});
     
     this.each(function() {
    	var $this = $(this);
    	//build element specific options
		var o = $.metadata ? $.extend({},opts,$this.metadata()):opts;
		
		if(!(o.chain_target.length > 0 && o.chain_event.length >0)){
    		throw 'plz specify which element to chain on which event';
    	}
		var t = $(o.chain_target);
		eval("$this."+o.chain_event+"(function(){t.val($this.val());})");
     });
     return this;
   };
   //default options for ajaxload plugin
   $.fn.chain.defaults = {
		'chain_target':'',
		'chain_event':''
   };
})(jQuery);