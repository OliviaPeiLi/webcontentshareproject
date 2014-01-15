/**
 * Logic for contact us form
 * @link /about/contactus
 * @uses jquery
 */
define(["common/ajaxForm","jquery"], function() {
	
	/* ========================== Events ======================== */
	
	// $('#promoters, #publishers').on('success', function(ret) {
	// 	$(this).find('.error').hide();
	// 	if (!ret.status) {
	// 		$(this).find('.error').show().html(ret.error);
	// 	}
	// 	$('#thankyou-msg').show();
	// });

	var selector = '#publishers_form form:eq(0)';

	$(document)
	.on('submit', selector, function(){
		$(this).find('.error').hide();
		$('input[type=submit]',this).attr("disabled",true).css({'opacity':0.4});
	})
	.on('success',selector, function(e,ret){
		$('input[type=submit]',this).attr("disabled",false).css({'opacity':1});
		var _this= this;
		$(this).find('.error').hide();
		console.warn(ret);
		if (ret.status) {
			$('#thankyou-msg').show().delay(3000).hide('slow',function(){
				_this.reset();
			});
		}
	})
	
});