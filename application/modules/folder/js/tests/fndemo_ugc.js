/**
 *  Tests for collection page e.g. /colleciton/Dmitry17/aaaa
 */
QUnit.module("Collection page");

QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

QUnit.test("Basic collection page contents", function() {
	ok( $('#sxsw_top').length, "Header not found");
	ok( $('.demo_topBox').length, "Title not found");
	ok( $('#list_newsfeed .newsfeed_entry').length, "Posts not found");
	ok( $('#list_newsfeed .newsfeed_entry:first .ext_share .share_twt_app').length, "Twitter button not found");
	ok( !$('#list_newsfeed .newsfeed_entry:first .ext_share .share_fb_app').length, "FB button found");
	ok( !$('#list_newsfeed .newsfeed_entry:first .ext_share .share_gplus_app').length, "G+ button found");
	ok( !$('#list_newsfeed .newsfeed_entry:first .ext_share .pin-it-button').length, "Pinterest button found");
	ok( !$('#list_newsfeed .newsfeed_entry:first .ext_share .share_likedin_app').length, "LinkedIn button found");
});

