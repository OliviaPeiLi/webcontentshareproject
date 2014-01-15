/*
 * Individual folder JS.
 * Logic for page that displays folder contents
 * @link /collection/Robert1/facebook_collection
 * @uses jquery
 * @uses common/utils - for the getParamFromURL() function
 */
define(['common/utils', "social/all", 'common/fd-scroll', "common/autoscroll_new", 'jquery'], function(utils) {

	/* ================== Variables ================= */
	var folder_top = '#folder_top';
	var num_drops = ' .profile_info_stats .num_drops strong';
	var num_upvotes = ' .upbox .up_count';
	var num_likes = ' .up_count';
	var notification_wrap = '#scroll_notifications';

	/* ================== Direct code =================== */
	
	/**
	 * MIXPANEL
	 */
	if (utils.getParamFromURL('link_from') === 'email_digest') {
		console.log('SENDING TO MIXPANEL');
		mixpanel.track('collection_view_from_email', {'id':$('#folder_contents').attr('rel')});
	}

	/* ================================== Events =================================== */
	
	/**
	 * Populate the embed collection code popup
	 */
	$(document).on('before_show', '#share_embed', function(ui, content) {
		var folder_id = $(this).attr('data-folder_id');
		$('#embed_collection_overview #embed_code').first().text(
					$('#embed_collection_overview textarea.sample').first()
						.text()
						.replace('{folder_id}',folder_id)
				);
	});
	
	/**
	 * Embed popup - selects the whole textarea contents
	 */
	$(document).on('click','#embed_code', function(){
		$(this).select();return false;
	});
	
	/**
	 * Delete folder. The button is in folder_top
	 */
	$(document).on('before_show', '.del_folder_btn', function(ui, content) {
		var $this = $(this);
		content.find('.delete_yes')
			.attr('href', '/delete_folder/'+$this.attr('data-folder_id'))
			.unbind('success').bind('success', function() {		
				location.href = php.back_url;
				content.modal('hide');
				return false;
			});
	});
	
	/**
	 * Up button
	 */
	$( document ).on('preAjax',  '#folder_top .up_button', function() {
		console.info('Up collection', this);
		//toggle buttons
		$this = $( this );
		$this.hide().parent().find( '.undo_up_button' ).show();
		//update count
		$this.parent().find( '.up_count' ).each(function() {
			$this = $( this );
			$this.text(parseInt($this.text()) + 1);
		}) 
	});
	
	/**
	 * UnUp button
	 */
	$( document ).on('preAjax', '#folder_top .undo_up_button', function() {
		console.info('UnUp collection', this);
		//toggle buttons
		$this = $( this );
		$this.hide().parent().find( '.up_button' ).show();
		//update count
		$this.parent().find( '.up_count' ).each(function() {
			$this = $( this );
			$this.text(parseInt($this.text()) - 1);
		}) 
	});

	/**
	 *  show more button of collaborators list in profile_top
	 *  @to-do - optimize variables like "t", "h", "iu" are unacceptable
	 */
	$( document ).on('update', '#collection_collaborator_list',  function() {
		var t = $(this).offset().top;
		var h = $(this).height();
		var iu = 0;		// item that is unvisible
		console.log('collaborator box height = ', h, ' -- top = ', t);

		$(this).find('.item').each( function() {
			var it = $(this).offset().top;
			if (it > h+t) {		//not visible items
				iu++;
				console.log('item ', $(this), ' -- display ', $(this).offset().top);
			}
		});
		console.log('there are ', iu, ' unvisible items');
		var showmore = $(this).parent().find('.collaborator_showmore');
		if (iu == 1) {
			showmore.html('(Show '+iu+' more collaborator)').show();
		} else if (iu > 1) {
			showmore.html('(Show '+iu+' more collaborators)').show();
		} else {
			showmore.html('').hide();
		}
	}).trigger('update');
	
	/**
	 * Specific delete drop code for folder top view
	 */
	$(document).on('preAjax', '#delete_dialog .delete_yes', function(){
	    var newsfeed_id = $(this).attr('href').replace('/del_link/','');
	    console.info('{Delete drop} - update num drops in folder top ', newsfeed_id);

	    if( !$(folder_top+num_drops).length ) return;
	    $(folder_top+num_drops).text( parseInt($(folder_top+num_drops).text()) - 1 );
	    // update upvotes = total_upvote - current deleted upvote
	    var nsf_wrap = $('li[data-newsfeed_id="' + newsfeed_id + '"]');
	    	var total_likes = $(folder_top+num_likes).text();
	    	var cnt = parseInt($('.up_count',nsf_wrap).text());

	    $(folder_top+num_upvotes).text( total_likes - cnt );
	 });
	 
	/*
	 * Specific Up vote drop code for folder top view
	 */
	$(document)
		.on('success', '#list_newsfeed .upbox .up_button', function() {
			console.info('{newsfeed} - like fast');
			$(folder_top+num_upvotes).text(parseInt($(folder_top+num_upvotes).text()) + 1 ); 
		})
		
	/*
	 * Specific Down vote drop code for folder top view
	 */
	$(document)
		.on('success', '#list_newsfeed .upbox .undo_up_button', function() {
			console.info('{newsfeed} - unlike fast');
			$(folder_top+num_upvotes).text(parseInt($(folder_top+num_upvotes).text()) - 1 ); 
		})		
	
	/*
	 * Contests specific - demo
	 */
	$(document).on('click','#contest_tabs a', function() {
		$(this).parent().find('a').removeClass('active');
		$(this).addClass('active');
		
		if ($(this).attr('href') == '#') {
			$('#folder_top').hide();
			$('#list_options, .submitStartup_container, #folder_contents, #all_folders').show();
		} else {
			$('#folder_top').show();
			$('#list_options, .submitStartup_container, #folder_contents, #all_folders').hide();
			$( $(this).attr('href') ).parent().children().hide();
			$( $(this).attr('href') ).show();
		}
	});
	
	/*
	 * Set landing folder (new design - responsive)
	 */
	$(document).on('success', folder_top+' .set_landing_folder', function() {
		$(this).parent().find('.rem_landing_folder').show();
		$(this).hide();
	});

	/*
	 * Remove folder from landing (new design - responsive)
	 */
	$(document).on('success',folder_top+' .rem_landing_folder', function() {
		$(this).parent().find('.set_landing_folder').show();
		$(this).hide();
	});

});
