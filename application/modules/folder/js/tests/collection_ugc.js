/**
 *  Tests for collection page e.g. /colleciton/Dmitry17/aaaa
 */
QUnit.module("Collection page");

QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'comments' ) {

	/** check right comment section **/
	var msg_time = (new Date()).getTime();
	var message_content = 'test message #';

	testModule('Comments', function () {

		// test mentions
		testVisible("Show mension menu", '.ui-autocomplete li:first',function(){
			$('.ui-autocomplete li:first').trigger("click");
		})
		.trigger(function() {
			// doesn't trigger autocomplete
			$('textarea.folder_commentInputBox.fd_mentions').val("@test").keydown().keyup();
			$('textarea.folder_commentInputBox.fd_mentions').val("@test").keydown().keyup();
		});

		testEvent("Add comment", '.folder_commentBox form', 'success', function() {
			ok( $('.folder_comments li.commentUnit:last .comment_body').text() == ( message_content + ":" + msg_time + " @test_user2" ), "Add Messages doesn't work" );
			ok( $('.folder_comments li.commentUnit:last .comment_body a').length, "Mentions backend doesn't work" );
		})
		.trigger(function(){
			$('.folder_commentInputBox').val(message_content + ":" + msg_time + " @test_user2").focus().keydown().keyup();
			setTimeout(function(){
				$('.folder_commentBox form').trigger("submit");
			},1000)
		});

		testEvent("Upvote the comment", '.folder_comments li.commentUnit:last .upvote', 'success', function() {
			var msg_wrap = $('.folder_comments li.commentUnit:last .comment_body:contains(\'' + ( message_content + ":" + msg_time ) + '\')').closest(".commentUnit");
			ok( $('.downvote:visible',msg_wrap).length, "Can't upvote the comment" );
		})
		.trigger(function(){
			$('.folder_comments li.commentUnit:last .upvote').trigger("click");
		});			

		testEvent("Delete Inserted Comment", '.folder_comments li.commentUnit:last .js-delete_comment', 'success', function() {
			ok($('.folder_comments li.commentUnit:last .comment_body:contains(\'' + ( message_content + ":" + msg_time ) + '\')').length == 0, "Add Messages doesn't work");
		})
		.trigger(function(){
			$('.folder_comments li.commentUnit:last .js-delete_comment').trigger("click");
		});
	
	});

	
}

if ( qunit_module === undefined || qunit_module == 'general' ) {
	
	var folderTop = '#folderTop';

	testModule('Qunit Upvote', function () {

		var up_count = 0;

		testEvent("Upvote the folder", folderTop + ' .upvote', 'success', function() {
			ok( $('#folderTop .downvote:visible').length, "Can't upvote the folder" );
			ok( up_count < parseInt($(folderTop + ' .downvote .num').eq(0).text()), "Can't upvote the folder" );
			//$('#folderTop .downvote').trigger("click");
		})
		.trigger(function(){
			up_count = parseInt($(folderTop + ' .upvote .num').eq(0).text());
			$( folderTop + ' .upvote').trigger("click");
		});

		testEvent("Downvote the folder", '#folderTop .downvote', 'success', function() {
			ok( $( folderTop + ' .upvote:visible').length, "Can't downvote the folder" );
			ok( up_count > parseInt($(folderTop + ' .upvote .num').eq(0).text()), "Can't downvote the folder" );
		})
		.trigger(function(){
			up_count = parseInt($(folderTop + ' .downvote .num').eq(0).text());
			$( folderTop + ' .downvote').trigger("click");
		});

	});

}

if ( qunit_module === undefined || qunit_module == 'newsfeed' ) {
	
	var $drop;

	testModule('Drop', function () {

		var collect_popup = '#collect_popup';
		
		test("Basic state", function() {
			ok($('#list_newsfeed .newsfeed_entry .text-container').closest('[data-newsfeed_id]').length, "Text post not found");
			$drop = $('#list_newsfeed .newsfeed_entry .text-container:first').closest('[data-newsfeed_id]');
			$('#list_newsfeed .newsfeed_entry:first .newsfeed_dropInfoContent').show();
		});

		testEvent("Upvote the drop", '#list_newsfeed .newsfeed_entry .upvote', 'success', function() {
			visible( $drop.find('.downvote') );
			hidden( $drop.find('.upvote') );
		})
		.trigger(function() { $drop.find('.upvote').trigger("click"); });
		
		testEvent("Downvote the drop", '#list_newsfeed .newsfeed_entry .downvote', 'success', function() {
			hidden( $drop.find('.downvote') );
			visible( $drop.find('.upvote') );
		})
		.trigger(function() { $drop.find('.downvote').trigger("click"); });	

		testVisible("Show redrop popup", collect_popup)
		.trigger(function() { $drop.find('a[href="'+collect_popup+'"]').trigger('click'); });	

		test("Redrop popup data", function() {
			equal($.trim($( collect_popup + ' textarea[name=description]').val()), $.trim($drop.find('.js-description').text()));
			equal( parseInt($(collect_popup).find('[name="newsfeed_id"]').val()), parseInt($drop.attr('data-newsfeed_id')), "redrop newsfeed_id is wrong");
			
			var folder_id = $( collect_popup + ' input[name^=folder_id]' ).attr("name");
			ok( parseInt(/\[(\d+)\]/.exec(folder_id)[1]) > 0 , "Collection is not selected or there are not any collections");
		});

		 testInvisible("Close redrop popup", collect_popup)
		 .trigger(collect_popup + ' button[data-dismiss=modal]', 'click');

		 // Edit newsfeed modal //

		// TODO Redrop section //
		// may need to added action for redrop
		// in this case the redrop should be deleted after created

	});

	testModule( 'newsfeed edit popup', function () {

		// only test information in popup
		// full test already done by same logic of home.js
		var edit_button = ' [href="#newsfeed_popup_edit"]';
		var delete_btn = ' [href="#delete_dialog"]';
		var id_input = ' [name=id]';
		var title_input = ' textarea.fd_mentions';
		//page selectors
		var drop_desc = ' .js-description';

		var	edit_popup = '#newsfeed_popup_edit';
		var delete_popup = '#delete_dialog';
		var num_drops = '#folderTop .js-total-redrop-count';
		
		//temp
		var _num_drops;

		// edit popup should show when click on edit btn
		testVisible( 'click on edit btn', edit_popup)
		.trigger(function() { $drop.find(edit_button).click(); });

		// check newsfeed_id & description
		test("Check edit popup data", function() {
			equal( $(edit_popup+id_input).val(), $drop.attr('data-newsfeed_id'), "EDIT: newsfeed_id is wrong");
			equal( $(edit_popup+title_input).val() , $.trim($drop.find(drop_desc).text()), "EDIT: title is wrong");
			equal( $(edit_popup+delete_btn).attr('data-delurl'), '/del_link/'+$drop.attr('data-newsfeed_id'));
		});

		testEvent("Save and Check Popup", edit_popup + ' .edit_post_form', 'success', function() {
			equal( $( edit_popup + title_input ).val() , $.trim($drop.find(drop_desc).text()), "Edit popup - title is wrong");
		})
		.trigger(function(){
			$( edit_popup + title_input ).val( "TEXT" );
			$(edit_popup + ' .done_button').trigger("click");
		});
		
		testEvent("Delete popup show", delete_popup, 'shown', function() {
			equal( $(delete_popup+' .delete_yes').attr('href'),  '/del_link/'+$drop.attr('data-newsfeed_id'));
			_num_drops = parseInt($(num_drops).text());
		})
		.trigger(edit_popup+delete_btn, 'click')
		
		testEvent("Delete drop", delete_popup+' .delete_yes', 'success', function() {
			equal(parseInt($(num_drops).text()), _num_drops-1, "Number of drops in #folder_top is not updated");
		})
		.trigger(delete_popup+' .delete_yes', 'click');
		
	});
}
