(function($) {
   $.fn.validate = function(options){
	 //set main options before element iteration
	 var opts = $.extend({},$.fn.validate.defaults,(options)?options:{});
	 //normal character
	 var charExp = /^[a-zA-Z]+$/;
	 //email
	 var emailExp = /^([\w\-\.])+\@([\w_\-\.])+\.([A-Za-z]{2,4})$/;
	 //number
	 var numExp = /^[\d]+$/;
	 //zip
	 var zipExp = /^[\d]{4}$/;
	 //address
	 var adsExp = /^[a-zA-Z0-9\/'&\-.,\s]+$/;
	 //phone number
	 var phoneExp = /^[0][234578][0-9]{8}$/;
	 //user name
	 var nameExp = /^[a-zA-Z\'\s]+$/;
	 //dollar
	 var dollarExp = /^[0-9]+[\.]?[0-9]+$|^[\d]+$/;
	 //sku
	 var skuExp = /^[^\s]{0,64}$/;
	 
	 this.each(function(){
		 $this = $(this);
		 //build element specific options
		 var o = $.metadata ? $.extend({},opts,$this.metadata()):opts;
		
		 /** first time visit **/
		 var first_time = false;
		 //plugin only applied to input & textarea tags
		 if(this.tagName != 'INPUT' && this.tagName != 'TEXTAREA')
			 throw "jQuery object doesn't support jquery.valid plugin";
		
		 //create message element & responding event
		 var msg = $this.next('span.'+o.msg_class);
		 if(msg.length == 0){
			if(o.msg_id == null){
				var str = '<span class="'+o.msg_class+'">';
			}else{
				var str = '<span class="'+o.msg_class+'" id="'+o.msg_id+'">';
			}
			if(o.required_msg == null){
				str += o.type_msg+'</span>';
			}else{
				str += o.required_msg+'</span>';
			}
			msg = $(str).insertAfter($this);
			msg.mouseover(function(){
				msg.css('display','none');
			});
			$this.focus(function(){
				if(msg.css('display') == 'none'){
					$(this).validate();
				}
			}).change(function(){
				if($(this).val().length == 0 || $(this).validate().data('jquery_validate_rs') == true){
					msg.css('display','none');
				}
			}).blur(function(){
				if($(this).val().length ==0){
					msg.css('display','none');
				}
			});
			first_time = true;
		 }
		 if(!first_time){
			 //if required field, check 
			 if(o.required && jQuery.trim($this.val()).length == 0){
				 //show message slowly
				 msg.text(o.required_msg)
				 	.css({'display':'inline','opacity':0})
				 	.animate({'opacity':1},o.ef.duration);
				 //store valid information in jquery data storage
				 $this.data('jquery_validate_rs',false);
			 }else if(jQuery.trim($this.val()).length != 0){
				 //test value by regex
				 if((o.type == 'char'  && !charExp.test($this.val())) ||
				    (o.type == 'email' && !emailExp.test($this.val())) ||	
				    (o.type == 'numeric' && !numExp.test($this.val())) ||
				    (o.type == 'zip' && !zipExp.test($this.val())) ||
				    (o.type == 'ads' && !adsExp.test($this.val())) ||
				    (o.type == 'phone' && !phoneExp.test($this.val())) ||
				    (o.type == 'name' && !nameExp.test($this.val())) ||
				    (o.type == 'int' && (jQuery.trim($this.val()) <= 0 || !numExp.test($this.val()))) ||
				    (o.type == 'dollar' && !dollarExp.test($this.val())) ||
				    (o.type == 'dollar_strict' && (!dollarExp.test($this.val()) || $this.val() == 0)) ||
				    (o.type == 'xhtml' && !$this.xhtml()) ||
				    (o.type == 'sku' && !skuExp.test($this.val()))
				 ){ 
					msg.text(o.type_msg).css({'opacity':0,'display':'inline'}).animate({'opacity':1},o.ef.duration);
					$this.data('jquery_validate_rs',false);
				 }else{
					$this.data('jquery_validate_rs',true);
				 }
			 }
		 }
	 });
	 return this;
   };

   //default options
   $.fn.validate.defaults = {
		   'msg_class':'alert_msg',
		   'msg_id': null,
		   'required':false,
		   'required_msg':null,
		   'type':null,
		   'type_msg':'',
		   'ef':{'duration':200}
   };
 })(jQuery);