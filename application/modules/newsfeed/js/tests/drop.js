/**
 * 
 */
QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'updownvote' ) {
	testModule('updownvote', function () {
		var content     = '#content';
		var up_button   = content+' .item_top .upbox > .up_button';
		var down_button = content+' .item_top .upbox > .undo_up_button';
		var upvote_text = content+' .item_top .upbox > .up_count';
		var upvote_count;

		// if up_down is enable, we test up_button before down_button
		// unless we test down_button before up_button
		testVisible("Basic state check", up_button);
		
		test("Basic state check", function() {
			hidden (down_button);
			upvote_count = parseInt($(upvote_text).text());			
		}) ;

		testEvent("Upvote", up_button, 'success', function(){
			visible(down_button);
			hidden (up_button);
			equal( parseInt($(upvote_text).text()), upvote_count+1 );
		})
		.trigger( up_button, 'click' );

		testEvent("Downvote", down_button, 'success', function(){
			visible(up_button);
			hidden (down_button);
			equal( parseInt($(upvote_text).text()), upvote_count );
		})
		.trigger( down_button, 'click' );
	});
}


if ( qunit_module === undefined || qunit_module == 'comment' ) {
	var comment_form; //drop page comment form
	var comments_container = '.newsfeed_entry_comments';
	var	new_entry = comments_container+' .newsfeed_entry_comment:last';
	var val = 'Some qunit test comment'; //Sample text for the testing comment
	var $new_entry;

	testModule( 'drop page', function () {
		var comment_form    = '.comments_form'; 
		var orig_name   = comment_form+' textarea.fd_mentions'; 
		var orig_count  = comment_form+' .comment_char_count';
		var comment_new = '.comments-bottom';
		var num_comments;

		var num_upvotes = 0;
		var num_redrops = 0;

		// removed: http://dev.fantoon.com:8100/browse/FD-2976
		//testVisible('drop new comment shown', comment_new, function(){ })
		//.trigger( '.newsfeed_comments_lnk', 'click' );

		test('new comment mentions and char count', function(){
			var orig_count_txt = parseInt( $(orig_count).text() );
			var orig_name_txt = $(orig_name).attr('name');
				
			ok(!isNaN($(orig_count).text()), "Original charcount");
			$(orig_name).val(val).trigger('keyup');
			
			equal(parseInt($(orig_count).text()), orig_count_txt - val.length, "Char count bad");
			equal($(orig_name).attr('name'), orig_name_txt+'_orig', "Mentions should be loaded");
		})

		testEvent('submit comment ajax', comment_form, 'success', function(){
				equal($(comments_container+' .newsfeed_entry_comment').length, num_comments+1, "New entry not added");
				
				//Check the comment contents
				visible(new_entry);
				equal($(new_entry+' .comment_content').text(), val, "New entry text is wrong");
				hidden (new_entry+' .up_button .upvote_text');
				visible(new_entry+' .undo_up_button .upvote_text');
		})
		.trigger( function(){
			num_comments = $(comments_container+' .newsfeed_entry_comment').length;
			$(comment_form+' textarea.fd_mentions').val(val).trigger('keyup');
			$(comment_form).submit();
		});

		testEvent('downvote comment', new_entry+' .undo_up_button', 'success', function(){
			visible(new_entry+' .up_button .upvote_text');
			hidden( new_entry+' .undo_up_button .upvote_text');
			hidden( new_entry+' .undo_up_button .actionButton_text');
		})
		.trigger( new_entry+' .undo_up_button', 'click' );

		testEvent('upvote comment', new_entry+' .up_button', 'success', function(){
					hidden( new_entry+' .up_button .upvote_text');
					visible(new_entry+' .undo_up_button .upvote_text');
					visible(new_entry+' .undo_up_button .actionButton_text');
					equal($(new_entry+' .undo_up_button .actionButton_text').text(), '1', "Up count should be 1");
		})
		.trigger( function(){
			$(new_entry+' .up_button').click();
		});

		testEvent('Upvote a Drop', '#main-content .up_button','success',function(){
			ok(parseInt($('div.up_count').text()) != num_upvotes, "Upvote is not updated");
			$('#main-content .undo_up_button').click();
		})
		.trigger(function(){
			num_upvotes = parseInt($('div.up_count').text());
			$('#main-content .up_button').click();
		});

		testEvent('Redrop a drop', '#main-content .up_button','success',function(){
			ok(parseInt($('div.up_count').text()) != num_upvotes, "Upvote is not updated");
			$('#main-content .undo_up_button').click();
		})
		.trigger(function(){
			num_upvotes = parseInt($('div.up_count').text());
			$('#main-content .up_button').click();
		});

		testEvent('remove comment', new_entry+' .delete_comment', 'success', function(){
				hidden($new_entry);
				equal($(comments_container+' .newsfeed_entry_comment').length, num_comments-1, "Comment should be removed");
		})
		.trigger(function(){
			num_comments = $(comments_container+' .newsfeed_entry_comment').length;
			$new_entry = $(new_entry);

			$(new_entry+' .delete_comment').click();
		});

	});
}

// drop module
if ( qunit_module === undefined || qunit_module == 'edit' ) {
	testModule('drop edit', function () {
		var content = '#content';
		var title = content+' .pop_up_title';
		var title_test = 'TITLE TEST #test';
		var source = content+' #permalinks > a';
		var edit_btn = content+' .newsfeed_edit_lnk[href="#newsfeed_popup_edit"]';
		var edit_dialog = '#newsfeed_popup_edit';
		var edit_dialog_title = edit_dialog+' textarea.fd_mentions';
		var edit_dialog_source = edit_dialog+' [name="activity[link][link]"]';

		// ------------ edit & cancel ------------
		testVisible('edit_btn -> edit dialog show', edit_dialog)
		.trigger( edit_btn, 'click' );

		test("check title & source", function() {
			equal($(title).text(), $(edit_dialog_title).val(), 'edit popup caption must be drop title');
			//RR - loaded via ajax
			//equal($(source).attr('href'), $(edit_dialog_source).val(), 'edit popup source must be drop source');
		});

		testInvisible('cancel btn -> edit dialog hide', edit_dialog, function(){})
		.trigger( edit_dialog+' .new_close', 'click' );

		// ------------ edit & save ------------
		testVisible('edit_btn -> edit dialog show', edit_dialog, function(){ })
		.trigger( edit_btn, 'click' );

		testEvent('edit drop title', edit_dialog+' .edit_post_form', 'success', function() {
			equal($(title).text(), title_test, 'drop title must be updated when saving');
		})
		.trigger(function() {
			$(edit_dialog_title).val(title_test);
			$(edit_dialog+' .done_button' ).click();
		});

		// ------------ edit & delete & delete no ------------
		var delete_dialog = '#delete_dialog';
		var delete_btn = edit_dialog+' .delete_button';
		var delete_no = delete_dialog+' .delete_no';
		var delete_yes = delete_dialog+' .delete_yes';

		testVisible('edit_btn -> edit dialog show', edit_dialog)
		.trigger( edit_btn, 'click' );

		testVisible('delete btn -> delete dialog show', delete_dialog)
		.trigger( delete_btn, 'click' );

		testInvisible('delete no -> delete dialog hide', delete_dialog)
		.trigger( delete_no, 'click' );

		//// ------------ edit & delete & delete yes ------------
		//testVisible('edit_btn -> edit dialog show', edit_dialog)
		//.trigger( edit_btn, 'click' );

		//testVisible('delete btn -> delete dialog show', delete_dialog)
		//.trigger( delete_btn, 'click' );

		//testEvent('delete yes -> delete drop', delete_yes, 'success')
		//.trigger( delete_yes, 'click' );
	});

}

if ( qunit_module === undefined || qunit_module == 'redrop' ) {
	testModule('redrop', function () {
		var content = '#content';
		var title = content+' .pop_up_title';
		var redrop_btn = content+" [href='#collect_popup']";
		var redrop_dialog = '#collect_popup';
		var num_redrops = 0;

		testVisible('redrop -> collect dialog show', redrop_dialog)
		.trigger( redrop_btn, 'click' );

		test('check title', function() {
			equal($(title).text(), $(redrop_dialog+' [name="description"]').val(), 'redrop description must be drop title as default');
		});

		testEvent("Redrop count",redrop_dialog + "  form" ,'success',function(){
			ok( parseInt($( content + ' .redrop_count').text()) != num_redrops, "Number of redrops is not changed" );
		})
		.trigger(function(){
			num_redrops = parseInt($( content + ' .redrop_count').text());
			$(redrop_dialog+' form [name="submit"]').click();
		})

		testInvisible('redrop click -> form hide', redrop_dialog)
		.trigger( function(){
			 $(redrop_dialog+' form [name="submit"]').click();
		});

	});
}

