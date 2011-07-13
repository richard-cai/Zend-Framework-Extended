(function($) {
   $.fn.tablegrid = function(options) {
     //set main options before element iteration
     var opts = $.extend({},$.fn.tablegrid.defaults,(options)?options:{});
     
     this.each(function() {
    	var $this = $(this);
    	//build element specific options
		var o = $.metadata ? $.extend({},opts,$this.metadata()):opts,trs=$this.find(o.tablegrid_row),color = trs.find('td').css('backgroundColor'),selectall = $this.find(o.tablegrid_selectall);
		trs.hover(function(){
			changColor(this,o.tablegrid_hovercolor);
		},function(){
			changColor(this,color);
		}).click(function(event){
			var index = trs.index(this),tags = 'A,INPUT,LABEL',t = event.target.tagName;
			if(tags.indexOf(t) ==-1){
				if(index%2 == 1){
					var checkbox = $(trs.get(index-1)).find('input:checkbox');
				}else{
					var checkbox = $(this).find('input:checkbox');
				}
				if(checkbox.attr('checked')){
					checkbox.removeAttr('checked');
				}else{
					checkbox.attr('checked','checked');
				}
			}
		});
		if(selectall.length){
			var cboxs = trs.find('input:checkbox');
			selectall.click(function(){
				if($(this).attr('checked')){
					cboxs.attr('checked','checked');
				}else{
					cboxs.removeAttr('checked');
				}
			});
		}
		var changColor = function(context,c){
			var index = trs.index(context);
			if(index%2 == 1){
				$(trs.get(index-1)).find('td').css('backgroundColor',c);
				$(context).find('td').css('backgroundColor',c);
			}else{
				$(trs.get(index+1)).find('td').css('backgroundColor',c);
				$(context).find('td').css('backgroundColor',c);
			}
		};
     });
     return this;
   };
   //default options for ajaxload plugin
   $.fn.tablegrid.defaults = {
		 'tablegrid_table':'.jquery-tablegrid',
		 'tablegrid_head':'.jquery-tablegrid-head',
		 'tablegrid_row':'.jquery-tablegrid-row',
		 'tablegrid_hovercolor':'#c3bd7c',
		 'tablegrid_selectall':'.jquery-tablegrid-selectall'
   };
})(jQuery);