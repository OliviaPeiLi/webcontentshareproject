/**
 * Profile page test
 * @link http://ft/test_user1
 * @to-do Test collaborators
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

// base collection scroll links test
if ( qunit_module === undefined || qunit_module == 'base' )	{
	
	testModule( 'Autoscroll', function () {
		testExists("Autoscroll",'#folders div.js-folder:eq(16)')
			.trigger(function()	{
				window.scroll(0,document.body.offsetHeight);
		});
		
		test("Test 16 item folder data-url is exists",function(){
			ok( $('#folders div.js-folder:eq(16)').attr("data-url").indexOf("//") == -1, " No collection url found." );
		});	
	});
}

if ( qunit_module === undefined || qunit_module == 'edit' ) {
	var new_collection_btn = '#create_new_collection_folder .standalone_btn:first';
	
	testModule( 'Add/Edit collection', function () {
		var edit_popup = '#edit_folder_popup';
		var delete_popup = '#delete_folder';
		var embed_popup = '#embed_collection_overview';
		var edit_popup_form = edit_popup+' form';
		var new_folder = '.folder:not(.unclickable, .new_folder_carcass):first';
		
		var new_collection_name = 'qunit test collection 2';
		
		testEvent( 'Add folder popup show', edit_popup, 'shown')
		.trigger( new_collection_btn, 'click' );
		
		testVisible("Add folder validation", edit_popup_form+' .error') 
		.trigger(function(){
			// remove error class then submit to make validate event is triggered
			// RR - NO - in tests we are duplicating the user actions. User wont 
			//      inspect the html and remove the .error class.
			$(edit_popup_form).submit();
		});
		
		/* RR - hashtag now redirects to /search page so we wont choose hashtag
		var selected_item = '.token-input-dropdown-fd_dropdown:visible ul li:nth-child(3)';
		var selected_item_data;
		testVisible('Choose collection open dropdown', selected_item, function() {
			selected_item = $(selected_item);
			selected_item_data = selected_item.data('tokeninput');
			selected_item.trigger('mousedown');
			console.info("TEST >>>>>>", selected_item[0]);
		})
		.trigger(edit_popup_form+' .token-input-input-token-fd_dropdown input', 'focus');
		*/
		
		testEvent( 'Create collection success', edit_popup_form, 'success', function() {
			ok($(new_folder).length, "new folder created");
			equal($(new_folder).attr('data-folder_name'), new_collection_name);
			ok($(new_folder).attr('data-folder_id'), "new folder attr: folder_id");
			
			new_folder = '.folder[data-folder_id='+$(new_folder).attr('data-folder_id')+']';
		})
		.trigger(function() {
			$(edit_popup_form+" [name='folder_name']").val(new_collection_name);
			$(edit_popup_form).trigger('submit');
		});
		
		testInvisible("New collection popup hide", edit_popup);
		
		testEvent( 'Embed collection popup', embed_popup, 'shown', function() {
			ok($(embed_popup+' textarea:not(.sample)').val().indexOf('/collection/'+$(new_folder).attr('data-folder_id')) > -1, "Wrong embed code");
		})
		.trigger(function() {
			$(new_folder+' .folder_embed').trigger('click');
		});
		
		testEvent('Embed collection popup close', embed_popup, 'hidden')
		.trigger(embed_popup+' [data-dismiss=modal]', 'click')
		
		testEvent( 'Edit collection popup', edit_popup, 'shown', function() {
			equal($(edit_popup+' [name=folder_id]').val(), $(new_folder).attr('data-folder_id'));
			equal($(edit_popup+' [name=folder_name]').val(), new_collection_name);
			//RR - hashtag redirects to /search page so we wont choose hashtag
			//equal($(edit_popup+' .hashtag_section .tokenInput-hidden').attr('name'), 'hashtag_id['+selected_item_data.id+']');
		})
		.trigger(function() {
			console.info('Edit button: ', new_folder+' .folder_edit');
			$(new_folder+' .folder_edit').trigger('click');
		});
		
		testEvent( 'Delete collection popup', delete_popup, 'shown')
		.trigger(new_folder+' .folder_delete', 'click');
		
		testEvent( 'Delete collection', delete_popup+' .delete_yes', 'success', function() {
			waitInvisible(delete_popup);
			waitInvisible(new_folder);
			ok(true);
		})
		.trigger(delete_popup+' .delete_yes', 'click');
	
	}); //End - Add/Edit collection
}

if ( qunit_module === undefined || qunit_module == 'follow' ) {
	
	testModule( 'folder follow/unfollow', function () {
		var folder = '.folder:first';
		var folder_follow = folder+' .folder_follow'
		var folder_unfollow = folder+' .folder_unfollow'
		
		test("basic state", function() {
			equal($.trim($('#profile_name a').text()), 'Test User 2');
			ok($(folder_follow).length, "follow button not found");
			ok($(folder_unfollow).length, "unfollow button not found");
			notEqual($(folder_follow).css('display'), 'none', "Folder follow should be visible");
		});
		
		testEvent("Follow folder", folder_follow, 'success', function() {
			notEqual($(folder_unfollow).css('display'), 'none', "Follder unfollow should be visible");
			equal($(folder_follow).css('display'), 'none', "Follder unfollow should be hidden");
		})
		.trigger(folder_follow, 'click');
		
		testEvent("Unfollow folder", folder_unfollow, 'success', function() {
			equal($(folder_unfollow).css('display'), 'none', "Follder unfollow should be hidden");
			notEqual($(folder_follow).css('display'), 'none', "Follder unfollow should be visible");
		})
		.trigger(folder_unfollow, 'click');
		
	});
}

if ( qunit_module === undefined || qunit_module == 'avatar' ) {
	
	testModule( 'Change user avatar', function () {
		var avatar_popup = '#upload_profilepic_dlg';
		
		testVisible("Open edit popup", '#upload_profilepic_dlg')
		.trigger('#link_to_edit_photo', 'click');
		
		test("Select file input exists", function() {
			ok($('#select_file_btn_hidden').length, "Select file input doesnt exists");
		});
				
		testEvent("Change image", '#select_file_btn_hidden', 'change')
		.trigger(function() {
			window.callPhantom('uploadFile', ['#select_file_btn_hidden']);
		})
		
		var orig_img_src;
		testCondition("Image uploaded", function() {
			return  $(avatar_popup+' #preview img').attr('src') != orig_img_src;
		})
		.trigger(function() {
			orig_img_src = $(avatar_popup+' #preview img').attr('src');
			console.info("Orig src", orig_img_src);
		});
		
		testVisible("Change image save button", '#save_preview')
		
		testEvent('Change image - submit', avatar_popup+' form#profile_pic', 'success', function() {
			notEqual($('#profilePic_auth').attr('src'), orig_img_src);
			notEqual($('#account_avatar').attr('src'), orig_img_src);
		})
		.trigger('#save_preview', 'click')
	});
}
