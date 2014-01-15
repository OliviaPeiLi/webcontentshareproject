/**
 * Profile page test
 * @link http://ft/test_user1
 * @to-do Test collaborators
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

if (qunit_module == 'follow' ) {
	
	testModule( 'user follow/unfollow', function () {
		var follow_btn = '.currentUser_panelFollowBox .currentUser_panelFollow';
		var unfollow_btn = '.currentUser_panelFollowBox .currentUser_panelUnfollow';
		test("basic state", function() {
			ok($('.currentUser_panelFollowBox').length, "follow button not found");
			visible($(unfollow_btn));
		});
		
		testEvent("Unfollow user", unfollow_btn, 'success', function() {
			hidden($(unfollow_btn));
			visible($(follow_btn));
		})
		.trigger(unfollow_btn, 'click');
		
		testEvent("Follow user", follow_btn, 'success', function() {
			visible($(unfollow_btn));
			hidden($(follow_btn));
		})
		.trigger(follow_btn, 'click');
		
		
	});
}

// base collection scroll links test
if ( qunit_module === undefined || qunit_module == 'base' )	{
	testModule( 'Autoscroll', function () {
		testExists("Autoscroll",'#folders .folder_ugc:eq(14)')
			.trigger(function()	{
				window.scroll(0,document.body.offsetHeight);
		});
		
		test("Test 16 item folder data-url is exists",function(){
			ok( $('#folders .folder_ugc:eq(14) h2 a').attr("href").indexOf("//") == -1, " No collection url found." );
		});	
	});
}

if ( qunit_module === undefined || qunit_module == 'avatar' ) {
	
	if ( qunit_module === undefined || qunit_module == 'upvote page' )	{
		testModule( 'Autoscroll', function () {

			var items_wrap = '#folders';
			var item = ' .folder_ugc';

			test("basic page state", function() {
				ok($(items_wrap + item).length, "no results found");
			});

		});
	}
	
	testModule( 'Change user avatar', function () {
		var avatar_popup = '#upload_profilepic_dlg';
		var avatar_btn = '.currentUser_panelFollowBox a.avatarPopup';
		var file_input = '#select_file_btn_hidden';
		var preview_img = avatar_popup+' img';
		var orig_img_src;
		
		testVisible("Open edit popup", avatar_popup)
		.trigger(avatar_btn, 'click');
		
		asyncTest("Select file input exists", 1, function() {
			window.setTimeout(function() {
				ok($(file_input).length, "Select file input doesnt exists");
				orig_img_src = $(preview_img).attr('src');
				start();
			}, 500);
		});
		
		testCondition("Image uploaded", function() {
			//console.info(orig_img_src, $(preview_img).attr('src'));
			return  $(preview_img).attr('src') != orig_img_src;
		})
		.trigger(function() {
			window.callPhantom('uploadFile', [file_input]);
		})
		
		testVisible("Change image save button", avatar_popup+' input[type=submit]');
		
		testEvent('Change image - submit', avatar_popup+' form', 'success', function() {
			notEqual($('.currentUser_panelFollowBox img').attr('src'), orig_img_src);
			notEqual($('#account_link img').attr('src'), orig_img_src);
		})
		.trigger(avatar_popup+' input[type=submit]', 'click')
	});

}