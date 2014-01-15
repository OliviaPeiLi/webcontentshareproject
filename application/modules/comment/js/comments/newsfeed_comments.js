/*
 * Newsfeed Comments
 * Logic for executing Like/Unlike, Submit Comment, Delete comment
 * @link drop page, home page, preview popup 
 * @see newsfeed/drop_page.js
 * @see newsfeed/drop_preview_popup.js
 * @see newsfeed/newsfeed_postcard.js
 * @uses plugins/mentions - for the comment textarea
 * @uses jquery
 */
define(['jquery', 'plugins/mentions', 'common/ajaxList'], function() {

	/* ============= Variables ==================== */
    var self = $(this);
    var comment_sample = '#tmpl-newsfeed_entry_comment, #tmpl-postcard_comment';
    
    /* ================== Events ===================== */
    
	/**
	 * NEWSFEED
	 * Link to view more comments in hierarchial comments
	 */
    /*
	$('.linkToViewMore a').on('click', function() {
		//console.log($(this).closest('.child_comments').find('.blockLevel'));
		$(this).closest('.child_comments').find('.blockLevel').show('fade');
		$(this).parent().hide('fade').remove();
		return false;
	});
	*/
	
	/**
	 * NEWSFEED
	 * Reply to comments.
	 */
	/*
	$('.reply_to_comment_lnk').live('click', function() {
		//console.log('reply clicked');
		$('.add_comment_reply_box').hide();
		var parent_width = $(this).closest('.blockLevel').width();
		//$(this).closest('.blockLevel').children('.add_comment_reply_box').find('.reply_textbox').width(parent_width-100);
		$(this).closest('.blockLevel').children('.add_comment_reply_box').toggle('fade');
		$(this).closest('.newsfeed_entry_comments').find('.newsfeed_entry_add_comment').hide();
		$(this).closest('.newsfeed_entry_comments').find('.newsfeed_entry_comment_options:last').show();
		$(this).closest('.blockLevel').children('.add_comment_reply_box').find('.reply_textbox').focus();
	});
	*/
	
	/**
	 * Add new comment link
	 */
	/*
	$('.add_new_comment_lnk').on('click', function() {
		var $parent = $(this).closest('.newsfeed_entry_comments'); 
		$parent.find('.add_comment_reply_box').hide();
		$parent.find('.newsfeed_entry_add_comment').show();
		$parent.find('.newsfeed_entry_comment_options:last').hide();
	});
	*/
    
    /**
     * Like comments (like button in comments)
     */
    $(document)
    	.on('preAjax','.newsfeed_entry_comment_options .up_button', function() {
    		console.info('{newsfeed comments} - like fast');
    		var comment_id = $(this).closest('[data-comment_id]').attr('data-comment_id');
    		var up_count = parseInt($(this).hide().parent().find('.up_button .actionButton_text').text()) || 0;
    		$('[data-comment_id='+comment_id+']')
    			.find('.undo_up_button').show()
    			.end().find('.up_button').hide()
    			.end().find('.actionButton_text').html(up_count + 1)
    	})
	    .on('success','.newsfeed_entry_comment_options .up_button', function(event, msg) {
	    	console.log('{newsfeed comments} - like success');
	        if ($(this).closest('.newsfeed_entry_comment').find('.like_text').length > 0) {
	        	$(this).closest('.newsfeed_entry_comment').find('.like_text').html(msg.html);
	        }
	        $('.ui-effects-wrapper').remove();
	    });
    
    /**
     * Unlike comemnts (Unlike button in comments)
     */
    $(document)
    	.on('preAjax','.newsfeed_entry_comment_options .undo_up_button', function() {
    		console.info('{newsfeed comments} - unlike fast');
    		var comment_id = $(this).closest('[data-comment_id]').attr('data-comment_id');
    		var up_count = parseInt($(this).hide().parent().find('.up_button .actionButton_text').text()) || 1;
    		$('[data-comment_id='+comment_id+']')
    			.find('.undo_up_button').hide()
    			.end().find('.up_button').show()
    			.end().find('.actionButton_text').html(up_count - 1)
    	})
	    .on('success','.newsfeed_entry_comment_options .undo_up_button', function(event, msg) {
	        if ($(this).closest('.newsfeed_entry_comment').find('.like_text').length > 0) {
	        	$(this).closest('.newsfeed_entry_comment').find('.like_text').html(msg.html);
	        }
	        $('.ui-effects-wrapper').remove();
	    });

	/**
	 * Toggle show/hide comments in hierarchial comments
	 */
    /*
	$('.newsfeed_entry_comment_options .see_children_comments_lnk').on('click', function() {
		$(this).closest('.newsfeed_entry_comment').parent().children('ul.child_comments').toggle('fade');
		if ($(this).hasClass('hide_comment')) {
			$(this).text('Hide Replies').removeClass('hide_comment');
		} else {
			$(this).text('View Replies').addClass('hide_comment');
		}
	});


	$('.reply_button').on('click', function() {
		var user_name = $(this).parent().parent().find('.user_name').find('a').text();
		$(this).closest('.newsfeed_entry').find('.reply_comm').val('Reply to '+user_name+':');
		var comment_box = $(this).closest('.newsfeed_entry_comments').find('.newsfeed_entry_add_comment');
		if (comment_box.is(':hidden')) {
			//console.log('---5---');
        	$(this).closest('.newsfeed_entry_comments').find('.newsfeed_entry_add_comment').show('blind').focus();
        	$(this).closest('.newsfeed_entry_comments').find('.newsfeed_entry_add_comment').find('.newsfeed_view_comments_lnk').text('View comments');
        }
	});
	*/

    /**
     * Submits the comment form on enter and update the char count
     */
	$(document).on('keydown keypress keyup','#list_newsfeed .commentArea textarea, #preview_popup form.comments_form textarea, .drop-page .comments_form textarea', function(e){

		if(e.keyCode ==13) {
			$(this).closest('form').submit();
			e.preventDefault();
			return false;
		} else {

			var count = $(this).attr('data-maxlength') - $(this).val().length;

			if (count < 0)	{
				$(this).parent().find('.comment_char_count').text(count).addClass("negative");
				$(this).css({color:'red'});
				$( 'input[type="submit"]', $(this).closest('form')).addClass("disabled_button");
			}	else	{
				$(this).parent().find('.comment_char_count').text(count).removeClass("negative");
				$(this).css({color:'black'});
				$( 'input[type="submit"]', $(this).closest('form')).removeClass("disabled_button");
			}
			
			$(this).closest('form').find('.error').hide();
		}
		
	});

	/**
	 * Submits the comment to the db
	 */

	var tags = '#list_newsfeed .commentArea form, .drop-page form.comments_form, #preview_popup form.comments_form';
    $(document)
    	.on('keep_comment',tags,function(){
    		
    		var _comment = $('textarea[name=comment]').val();
    		var newsfeed_id = $('input[name=newsfeed_id][value!=""]').val();

    		var cookie_string = newsfeed_id + "|~|" + _comment;
    		setCookie( "comment_" + (new Date()).getTime(), cookie_string, 3);

    		return true;
    	})
	    .on('validate', tags, function(e, callback) {
	    	
	    	if ($('input.disabled_button',$(this)).length)	{
	    		// $('input[type=submit]',this).removeClass("disabled_button");
	    		callback.call(this, {status:false});
	    		return false;
	    	}

			var $commsg = $(this).find('textarea.fd_mentions');
			if( ! $.trim($commsg.val()) || $commsg.val() == $commsg.attr('placeholder')){
				callback.call(this, {status:false});
				$(this).find('.error').show().html('Type in some text');
				return;
			}
			$(this).find('.error').hide();
			callback.call(this, {status:true});
		})
	    .on('postAjax',tags, function() {
	    	console.info('{newsfeed comments} - add fast');
	    	var $this = $(this);
	    	var $parent = $this.closest('.newsfeed_entry_comments, .preview_popup_comments, .postcard_comments');
	    	
	    	//Create the new comment
	    	//BP: #FD-2834
		    $new_comm = $( comment_sample ).tmpl( {'val': $this.find("[name='comment']").val() } );
			$new_comm.appendTo( $parent.find( '.newsfeed_entry_comments' ).show() );
			$new_comm.show( 'fade' );
			$parent.find('.actionButton_text').html(1);
			this.last_comment = $new_comm;
			//end of #FD-2834
	    	
			//Update comments count

	    	var num_comments = parseInt($parent.find('.num_comments:first').text()) || 0;
				num_comments += 1;
			$parent.find('.num_comments').text(num_comments);
			$('[data-newsfeed_id='+$(this).find('[name=newsfeed_id]').val()+'] .num_comments').text(num_comments);
	    	
	    	//Reset
			$(this).find('.comment_char_count').text(250);
	    	$(this).find('textarea.fd_mentions').val('');
			
			//Popup specific
			if (parent.find('.comments_list').length) {
				var scrollTo = Math.max(0, parent.find('.comments_list')[0].scrollHeight - parent.find('.comments_list').height() - parent.find('#popup_right').height());
				parent.find('.comments_list').animate({'scrollTop': scrollTo});
			}

	    })
	    .on('success',tags, function(event, result) {
	    	console.log('{newsfeed comments} - add success');
	    	//Server Error handling
	    	if (!result.status) {
	        	this.last_comment.hide().remove();
	        	$(this).find('.error').show().text(result.error);
	    		return;
	    	}
	    	
	    	//Populate new comment with server data
			this.last_comment.attr('data-comment_id', result.data.comment_id)
					.find('.delete_comment').attr('href', result.data.del_url)
					.end().find('.comment_body, .comment_content').html(result.data.body)
					.end().find('.up_button').attr('href', result.data.like_url)
					.end().find('.undo_up_button').attr('href', result.data.unlike_url)
			
			//Update newsfeed
			//RR - comments in newsfeed was disabled
			//var newsfeed_id = $(this).find('input[name=newsfeed_id]').val();
			//$('[data-newsfeed_id='+newsfeed_id+']').each(function() {
				//if ($(this).find('[data-comment_id='+result.data.comment_id+']').length) return;
			    //var $parent = $(this).find('.newsfeed_entry_comments, .preview_popup_comments, .postcard_comments');
			    //if (!$parent.length) return;
			    ////Create the new comment
			    ////BP: #FD-2834
				//var $new_comm = $( '#tmpl-newsfeed_entry_comment, #tmpl-postcard_comment' ).tmpl( result.data, function ( item, data ) {
				//	item.attr( 'data-comment_id', data.comment_id );
				//	item.find( '.delete_comment' ).attr( 'href', data.del_url );
				//	item.find('.comment_body, .comment_content').html( data.body );
				//	item.find('.up_button').attr( 'href', data.like_url );
				//	item.find('.undo_up_button').attr( 'href', data.unlike_url );
				//} );
				//$new_comm.insertAfter( $parent.find( '.newsfeed_entry_comments' ) );
				//$new_comm.show( 'fade' );
				////end of #FD-2834
			//});
			
			//Mixpanel tracking
			if (typeof(mixpanel) !== 'undefined') {
				var user = php.userId ? php.userId : 0;
				mixpanel.people.identify(user);
				mixpanel.track('Add Comment', {'id':$(this).closest('.newsfeed_entry').data('newsfeed_id'), 'view':'postcard', 'user':user});
			}
			
	    });
    
    /*
     * Delete a comment
     */
    $(document).on('success','.delete_comment', function(event) {
    	console.log('{newsfeed comments} - Delete comment');
        var entry = $(this).closest('.blockLevel, .postcard_comment'); //for hierarchial comments
        if (entry.length <= 0) {
        	entry = $(this).closest('.newsfeed_entry_comment, .list_comment'); //for non-hierarchial comments
        }
        var container = entry.closest('[data-newsfeed_id]');
        
        //update num comments txt
    	var num_comments = parseInt(container.find('.num_comments').text()) || 1;
    		num_comments -= 1;
    	container.find('.num_comments').text(num_comments);
		$('[data-newsfeed_id='+container.attr('data-newsfeed_id')+'] .num_comments').text(num_comments);
        //Delete from newsfeed
        if ( ! $(this).closest('.newsfeed_entry').length) { //if in popup
    		var comment_id = $(this).closest('[data-comment_id]').attr('data-comment_id');
    		$('[data-comment_id='+comment_id+']').hide().remove();
        }
        entry.hide('fade').remove();
        $('.ui-effects-wrapper').remove();
    });
	

});
