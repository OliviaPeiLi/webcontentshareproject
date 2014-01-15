/* *********************************************************
 * Featured Interests logic
 *  Handles the addition of new featured interests
 *   both on interest page (inside right side column, look for + icon)
 *    and on Edit Interest Page under Featured Interests subarea. 
 * (most stuff reused from feature_interests.js)
 *
 * ******************************************************* */

define(['jquery'], function() {
		//console.log($('.autocomplete_input #new_feature_input').length);
		$('#page_new_feature_submit').live('click', function() {
		    var form = $(this).closest('form');
		    var url = form.attr('action');
		    var features = $('.autocomplete_input #new_feature_input').tokenInput('get');
		    var f_url = form.find('input[name=url]').val();
		    var type = form.find('select[name=type]').val();
		    //prepare_autocomplete('#new_topic','#load_topics','#topic_names');
		    var data = {
			page: JSON.stringify(features),
			url: f_url,
			type: type,
			view: 'edit_page',
			ci_csrf_token: $("input[name=ci_csrf_token]").val()
			};
			$.ajax({
			    url: url,
			    type: 'POST',
			    data: data,
			    success: function(msg) {
					$('.autocomplete_input #new_feature_input').tokenInput('clear');
	            	if (msg.indexOf('ERR:') >= 0) {
	            		$('#edit_page_new_feature').append('<div class="feat_interest_err">'+msg.substring(4)+'</div>');
	            	} else {
	            		$('.feat_interest_err').hide().remove();
						$('#new_feature').hide('fade');
						$('#list_of_featured_interests').append(msg);
	                }
				}
			});
			return false;
		});
		
		$('.delete').live('click', function() {
		    var entry = $(this).closest('.profile_friends_list_item');
		    var url = $(this).attr('href');
		    $.ajax({
			url: url,
			type: 'GET',
			success: function(msg) {
			    entry.hide('fade').remove();
			    }
			    });
		    return false;
		    });
		
});
