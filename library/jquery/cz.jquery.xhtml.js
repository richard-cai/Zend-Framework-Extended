(function($) {
   $.fn.xhtml = function(settings) {
     var config = {};
     var flag = true;
     if (settings) $.extend(config, settings);
     this.each(function() {
		var str = $(this).val().replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace('  ','').toLowerCase();
		if(/<javascript[^<>]*>|<form[^<>]*>|<input[^<>]*>/gi.test(str) == true){
			flag = false;
			return;
		}
		var brs = str.match(/<br[\s]*>/gi);
		if(brs != null){
			for(var x in brs){
				if(brs[x].lastIndexOf('/') == -1) {
					flag = false;
					return;
				}
			}
		}
		var imgs = str.match(/<img[^<>]+>/gi);
		if(imgs != null){
			var y;
			for(y=0;y++;y<imgs.length){
				var ps = imgs[y].lastIndexOf('/');
				if(ps != imgs[y].length - 2){
					flag = false;
					return;
				}
			}
		}
		if(str.length == 0){
			flag = -1;
		}else{
			str = str.replace(/<br[\s]*>|<br \/>/gi,'').replace(/<img[^<>]+>/gi,'');
		    var open_tags = str.match(/<[^<>\/]+>|<a href=[^<>]+>|<embed src=[^<>]+|<param [^<>]+>/gi);
			var close_tags = str.match(/<\/[^<>]+>/gi);
			if(open_tags == null && close_tags == null){
				return;
			}
			if(open_tags == null || close_tags == null){
				flag = false;
				return;
			}
			var length = open_tags.length,m,n;
			if(length != close_tags.length){
				flag = false;
				return;
			}
			for(n=0;n<length;n++){
					if(open_tags[n].indexOf(' ') != -1){
						var tmp = open_tags[n].split(' ');
						open_tags[n] = jQuery.trim(tmp[0])+">";
					}
				close_tags[n] = close_tags[n].replace(/\//g,'');
					}
			var num = 1;
			while(num <= open_tags.length){
				var item = open_tags.slice(0,num);
				if(item.join(',') == close_tags.slice(0,num).reverse().join(',')){
					open_tags = open_tags.slice(num);
					close_tags = close_tags.slice(num);
					num = 1;
				}else if(num == 1){
					if(item == close_tags[close_tags.length-1]){
						close_tags.pop();
						open_tags.shift();
					}else{
						num++;
				}
				}else if(num > 1){
					num++;
			}
			}
			if(open_tags.length == 0 && close_tags.length == 0){
				flag = true;	
			}else{
				flag = false;
			}
			
		}
     });
     return flag;
   };
 })(jQuery);
