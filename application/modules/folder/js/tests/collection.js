/**
 *  Tests for collection page e.g. /colleciton/Dmitry17/aaaa
 */
QUnit.module("Collection page");

QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

QUnit.test("Basic collection page contents", function() {
	var $drop = $("#list_newsfeed .newsfeed_entry:first");
	ok($drop.length, "collection page doesn have any drops");
	ok($drop.find('.num_comments').length, "Could not find number of comments in the drop");
});

if ( qunit_module === undefined || qunit_module == 'edit' ) {
	testModule('Edit collection', function () {
		//folder top
		var folder_top = '#folder_top';
		var edit_btn = " .folder_edit_btn";
		var folder_title = ' #folder_title';
		//edit popup
		var edit_popup = '#edit_folder_popup';
		var name_input = ' input[name=folder_name]';
		var hashtags = ' .hashtag_section';
		
		var selected_item = '.token-input-dropdown:visible ul li:nth-child(1)';
		var collection_name = 'test collection 3';
		
		var selected_item_data, $selected_item;
		
		QUnit.test("Warn message shown", function() {
			visible(folder_top+' .js-hashtag.warning');
		});
		
		testEvent("Show edit popup", edit_popup, 'shown', function() {
			equal($.trim($(folder_top+folder_title).text()), $(edit_popup+name_input).val());
			visible(edit_popup+hashtags+' .placeholder-token');
			$(edit_popup+name_input).val(collection_name);
		})
		.trigger(folder_top+edit_btn, 'click')
		
		/*//RR hashtags are not required anymore
		testVisible("Validation", edit_popup+' .error')
		.trigger(edit_popup+' form', 'submit');
		*/
		testVisible("Show hashtags dropdown", selected_item, function() {
			$selected_item = $(selected_item);
			selected_item_data = $selected_item.data('tokeninput');
		})
		.trigger(function() {
			$(edit_popup+hashtags+' .placeholder-token').trigger('click');
		});
		
		testVisible("Select item", edit_popup+hashtags+' .token-input-token')
		.trigger(function() {
			$selected_item.trigger('mousedown');
		})
		
		testEvent("Submit edit popup", edit_popup+' form', 'success', function() {
			ok(!$(folder_top+' .js-topic.warning').length, "Warning message should disapear");
			equal(selected_item_data.id, $(folder_top+edit_btn).attr('data-hashtag_id'), "Hashtag Id not updated");
			equal($.trim($(folder_top+folder_title).text()), collection_name, "Collection name not updated")
		})
		.trigger(edit_popup+' form', 'submit');
		
	});
}

if ( qunit_module === undefined || qunit_module == 'basic' ) {
	
	testModule('Basic collection page', function () {
		var drop = "#list_newsfeed .newsfeed_entry:first";
		var preview_popup = '#preview_popup';
		var popup_comments_container = preview_popup+' .comments_list';
		var popup_comments =  popup_comments_container+' .newsfeed_entry_comments';
		var popup_comments_form = preview_popup+' .comments_form';
		var popup_last_comment = popup_comments_container+' .newsfeed_entry_comment:last';
		var popup_last_comment_delete = popup_last_comment+' .delete_comment';
		
		var num_comments; //original number of comments in the drop on page load;
		var $drop;

		test('basic state', function() {
			$drop = $(drop);
			// test PINT, all drop that has $(drop+' .tl_icon').hasClass('tl_text');
			$('.tl_icon.tl_text').each(function() {
				ok( ! $(this).closest('.newsfeed_entry').find('.ext_pinterest').length, 'pint should not shown in Text drop');
			});
		});
			
		testEvent('Open popup', preview_popup, 'shown')
		.trigger( drop+" [href='#preview_popup']", 'click' );
		
		testExists('Popup comments', popup_comments);
		testExists('Popup comments form', popup_comments_form);
		
		testEvent("Submit popup comment", popup_comments_form, 'success', function() {
			equal(parseInt($(popup_comments).find('.num_comments').text()) || 0, num_comments+1, "Number of comments in popup isnt updated");
			equal(parseInt($drop.find('.num_comments').text()) || 0, num_comments+1, "Number of comments in drop isnt updated");
			num_comments = parseInt($(popup_comments).find('.num_comments').text());
		})
		.trigger(function() {
			num_comments = parseInt($(popup_comments).find('.num_comments').text()) || 0;
			$(popup_comments_form).find('textarea.fd_mentions').val('Some qunit test comment').trigger('keyup');
			$(popup_comments_form).trigger('submit');
		});
	
		var $popup_last_comment;
		testEvent("Popup remove comment",  popup_last_comment_delete, 'success', function() {
			hidden($popup_last_comment);
			equal(parseInt($drop.find('.num_comments').text() || 0), num_comments-1, "Number of comments in drop isnt updated");
		})
		.trigger(function(){
			$popup_last_comment = $(popup_last_comment);
			$(popup_last_comment_delete).click();
		});
	});
		
} //End - qunit_module=popup

if ( qunit_module === undefined || qunit_module == 'updownvote' ) {
	
	testModule( 'up/down vote drop collection', function () {
		var drop = '#list_newsfeed .newsfeed_entry:first';
		var up_button = drop+' .upbox .up_button';
		var down_button = drop+' .undo_up_button';
		var up_count_el = drop+' .upbox .up_count';
		var up_count; //will be populated with the current up count
		
		// Make sure the up_button is visible and define the up count so the tests can continue
		test("Basic state check", function() {
			visible(up_button);
			hidden(down_button);
			up_count = parseInt($(up_count_el).text());
		})

		// --------- test upvote in home -----------
		testEvent('Upvote', up_button, 'success', function(){
			hidden(up_button);
			visible(down_button);
			visible(up_count_el);
			equal(parseInt($(up_count_el).text()), up_count+1, "Up count should be +1");			
		})
		.trigger(up_button, 'click');

		// --------- test downvote in home -----------
		testEvent('Downvote', down_button, 'success', function(){
			visible(up_button);
			hidden(down_button);
			equal(parseInt($(up_count_el).text()), up_count, "Up count should be -1");			
		})
		.trigger(down_button, 'click');
	});

	testModule( 'up/down vote (in POPUP) drop collection', function () {
		var popup = '#preview_popup';
		var drop = "#list_newsfeed .newsfeed_entry:first";
		
		var up_button = ' .upbox .up_button';
		var down_button = ' .undo_up_button';
		var up_count_el = ' .upbox .up_count';
		var up_count; //will be populated with the current up count

		// --------- open the popup to test in it
		testVisible('drop popup should open', popup)
		.trigger( drop+" [href='#preview_popup']", 'click');
		
		// Make sure the up_button is visible and define the up count so the tests can continue
		test("Basic state check", function() {
			visible(popup+up_button);
			hidden(popup+down_button);
			up_count = parseInt($(popup+up_count_el).text());
			console.info('-----------Upcount is: ', up_count);
		})

		testEvent('up btn in popup click', popup+up_button, 'success', function(e, d) {
			hidden(popup+up_button);
			visible(popup+down_button);
			visible(popup+up_count_el);
			equal(parseInt($(popup+up_count_el).text()), up_count+1, "Up count should be +1");			
			equal(parseInt($(drop+up_count_el).text()), up_count+1, "Up count not synced to newsfeed");			
		})
		.trigger(popup+up_button, 'click');

		testEvent('down btn in popup click', popup+down_button, 'success', function(e, d) {
			visible(popup+up_button);
			hidden(popup+down_button);
			equal(parseInt($(popup+up_count_el).text()), up_count, "Up count should be -1");			
			equal(parseInt($(drop+up_count_el).text()), up_count, "Up count not synced to newsfeed");			
		})
		.trigger(popup+down_button, 'click');
	});
}

// redrop module
if ( qunit_module === undefined || qunit_module == 'redrop' ) {
	testModule( 'newsfeed redrop collection', function () {
		// check redrop function. Because there are common logic with home
		// so that do not check all
		var drop = "#list_newsfeed .newsfeed_entry:first";
		var button = ' [href="#collect_popup"]';
		var description ='.drop_desc_plain';
		var redrop_popup = '#collect_popup';

		testEvent('redrop click -> popup show', redrop_popup, 'show', function(){
			// check newsfeed_id
			equal( parseInt($(redrop_popup).find('[name="newsfeed_id"]').val()), parseInt($(drop).attr('data-newsfeed_id')), "redrop newsfeed_id is wrong");

			// check description of popup
			equal( $.trim($('#redrop_description').val()), $.trim($(drop).find(description).text()), "redrop description is wrong");
		})
		.trigger(drop+button, 'click');

	});
}

if ( qunit_module === undefined || qunit_module == 'drop' ) {
	testModule( 'newsfeed edit popup', function () {
		// only test information in popup
		// full test already done by same logic of home.js
		var drop = "#list_newsfeed .newsfeed_entry:first";
		var edit_button = ' .newsfeed_edit_lnk[href="#newsfeed_popup_edit"]';
		var delete_btn = " [href='#delete_dialog']";
		var id_input = ' [name=id]';
		var title_input = ' textarea.fd_mentions';
		//page selectors
		var drop_desc = ' .drop-description';
		var	edit_popup = '#newsfeed_popup_edit';
		var delete_popup = '#delete_dialog';
		var num_drops = '#folder_top .profile_info_stats .num_drops strong';
		//temp
		var drop_id, _num_drops;
		
		// -------------- test edit popup information -----------------
		// edit popup should show when click on edit btn
		testVisible( 'click on edit btn', edit_popup)
		.trigger(drop+edit_button, 'click');

		// check newsfeed_id & description
		test("Check edit popup data", function() {
			drop_id = $(drop).closest('[data-newsfeed_id]').attr('data-newsfeed_id');
			equal( $(edit_popup+id_input).val(), drop_id, "EDIT: newsfeed_id is wrong");
			equal( $(edit_popup+title_input).val() , $.trim($(drop+drop_desc).text()), "EDIT: title is wrong");
			equal( $(edit_popup+delete_btn).attr('data-delurl'), '/del_link/'+drop_id);
		});
		
		testEvent("Delete popup show", delete_popup, 'shown', function() {
			equal( $(delete_popup+' .delete_yes').attr('href'),  '/del_link/'+drop_id);
			_num_drops = parseInt($(num_drops).text());
		})
		.trigger(edit_popup+delete_btn, 'click')
		
		testEvent("Delete drop", delete_popup+' .delete_yes', 'success', function() {
			equal(parseInt($(num_drops).text()), _num_drops-1, "Number of drops in #folder_top is not updated");
		})
		.trigger(delete_popup+' .delete_yes', 'click');
	});
	
} //End qunit_module=drop
