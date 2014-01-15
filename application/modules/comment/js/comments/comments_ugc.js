/*
 */
define(['plugins/mentions', 'common/ajaxList', 'common/custom_title', 'jquery'], function() {
	
	var self = this;

	var selector = 'textarea.folder_commentInputBox';

	$(document)
		.on("keypress", selector, function(event){
			if ( ( event.keyCode == 13 || event.keyCode == 10 ) && !event.shiftKey) {
				$(this).closest('form').submit();
				event.preventDefault();
				return false;
			}
		})
		.on('focus', selector, function(){
			$('.actionButton,.textLimit',$(this).closest("form")).show();
			$(this).closest("form").css({'height':'70px'});		
		})
		.on('blur', selector, function(){
			if ($.trim($(this).val()) == "") {
				$('.actionButton,.textLimit',$(this).closest("form")).hide();
				$(this).closest("form").css({'height':'50px'});
			}
		});

	var selector = '.folder_commentBox form';
	
    $(document)
    	.on('keep_comment','.folder_commentBox form',function(){

    		var _comment = $('textarea[name=comment]',this).val();
    		var newsfeed_id = $('input[name=newsfeed_id][value!=""]',this).val();
    		var folder_id = $('input[name=folder_id][value!=""]',this).val();
    		var cookie_string = newsfeed_id + "|~|" + _comment;
    		if (newsfeed_id)	{
    			setCookie( "comment_" + (new Date()).getTime(), cookie_string, 3);
    		}
    		if (folder_id)	{
    			setCookie( "commentf_" + (new Date()).getTime(), cookie_string, 3);
    		}
    		return true;
    	})
	    .on('validate','.folder_commentBox form', function(e, callback) {
	    	if ($('input[name=comment]').val() == "")	{
				callback.call(this, {status:false});
			}
			callback.call(this, {status:true});
		})
	    .on('postAjax','.folder_commentBox form', function() {

	    	console.info('{newsfeed comments} - add fast');
	    	var $this = $(this);
	    	var $parent = $this.closest('.folder_commentsPanel');
	    	
	    	console.warn( $parent.find( '.folder_comments' ) );

	    	//Create the new comment
	    	//BP: #FD-2834
		    $new_comm = $( '#tmpl-newsfeed_entry_comment' ).tmpl( {'val': $this.find("[name='comment']").val() } );
			$new_comm.appendTo( $parent.find( '.folder_comments ul' ).show() );
			$new_comm.show( 'fade' );
		    $('.downvote',$new_comm).show();
		    $('.upvote',$new_comm).hide();			
			this.last_comment = $new_comm;

			var _comm_num = $('.js-count_num',$this.closest(".folder_commentBox")).eq(0);

			_comm_num.text(parseInt(_comm_num.text()) + 1);

			//end of #FD-2834

			//Popup specific
			if ($parent.find('.folder_comments').length) {
				var scrollTo = Math.max(0, $parent.find('.folder_comments')[0].scrollHeight - $parent.find('.folder_comments').height() - $parent.find('.folder_commentsPanel').eq(0).height());
				$parent.find('.folder_comments').animate({'scrollTop': scrollTo});
			}

			$(this).get(0).reset();

	    })
	    .on('success','.folder_commentBox form', function(event, result) {

	    	console.log('{newsfeed comments} - add success');

	    	//Server Error handling
	    	if (!result.status) {
	        	this.last_comment.hide().remove();
	    		return;
	    	}
	    	
	    	//Populate new comment with server data
			this.last_comment.attr('data-comment_id', result.data.comment_id)
					.find('.js-delete_comment').attr('href', result.data.del_url)
					.end().find('.comment_body').html(result.data.body)
					.end().find('.upvote').attr('href', result.data.like_url)
					.end().find('.downvote').attr('href', result.data.unlike_url)
			
			$('.textLimit',this).html( $('textarea',$(this)).attr("maxlength") );
			$('textarea',$(this)).trigger("blur");
			$('.actionButton,.textLimit',this).hide();
			$(this).css({'height':'50px'});
			
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
    $(document).on('success', '.js-delete_comment', function(event) {
    	console.log('{newsfeed comments} - Delete comment');

		var _comm_num = $(".folder_commentBox .js-count_num").eq(0);
		_comm_num.text(parseInt(_comm_num.text()) - 1);    	

        $(this).closest('.commentUnit').remove(); //for hierarchial comments
    });

    /**
     * Like comments (like button in comments)
     */
    $(document)
    	.on('preAjax', '.folder_comments .upvote', function() {
    		console.info('{folder comments} - like fast');
    		var up_count = parseInt($(this).hide().parent().find('.upvote .num').text()) || 0;
    		$(this).closest('.commentUnit')
    			.find('.downvote').show()
    			.end().find('.upvote').hide()
    			.end().find('.num').html(up_count + 1)
    	});
    
    /**
     * Unlike comemnts (Unlike button in comments)
     */
    $(document)
    	.on('preAjax', '.folder_comments .downvote', function() {
    		console.info('{folder comments} - unlike fast');
    		var up_count = parseInt($(this).hide().parent().find('.upvote .num').text()) || 1;
    		$(this).closest('.commentUnit')
    			.find('.downvote').hide()
    			.end().find('.upvote').show()
    			.end().find('.num').html(up_count - 1)
    	});
	

    	var c_wrap = $('.folder_commentsPanel').eq(0);
    	var header_height = $('#header').height();
    	var infoContainer = $('.infoContainer').eq(0);
    	var infoContainer_bottom = $('.infoContainer_bottom').eq(0);
    	var commentsUL = $('.commentsUL').eq(0)

    	$(document).on('scroll', function(){

    		if ($(this).scrollTop() > infoContainer.height())	{
    			infoContainer_bottom.css({
    				position: 'fixed',
    				top : $('#header').length ? '45px' : 0,
    				left : 0,
    				right : 0
    			})
    		}	else	{
    			infoContainer_bottom.css({
    				position: ''
    			})
    		}

    		if ( $(this).scrollTop() > $('#folderTop').height() )	{
    			c_wrap.css({
    				'position':'fixed',
    				'top': $('#header').length ? '95px' : '50px'
    			});
    		}	else {
    			c_wrap.css({
    				'position':'',
    			});
    		}
    		resizeComments();
    	});

    	function resizeComments()	{
	    	commentsUL.css({
	    		'max-height' : ( $(window).height() - ( c_wrap.offset().top - $(document).scrollTop()) ) - 70 + "px"
	    	});
    	}
    	resizeComments();
});
