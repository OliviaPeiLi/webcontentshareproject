/**
 * JS logic for fandrop header on external modules, such as bookmarklet
 */
define(["jquery"], function() {
	//console.info('header');
	//Default jquery extends
	$.extend({
		post: function(url, data, callback, type) {
			if (typeof data == 'object' && !data[php.csrf.name]) data[php.csrf.name] = php.csrf.hash; 
			$.ajax(url, {
				'type': 'post',
				'dataType': type,
				'data': data,
				'success': callback
			});
		}
	});
	
});	