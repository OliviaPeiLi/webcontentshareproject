/**
 *  Tests for winsxsw contest page
 *  @link /winsxsw
 */
QUnit.module("winsxsw");

QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'base' ) {
	testModule('Base', function () {
		test("Basic page state", function() {
			ok($('#sxsw_top').length, "Header not found");
			ok($('#all_folders .js-folder').length, "Folders not found");
			$('#all_folders .js-folder .share_count strong').each(function() {
				ok($.trim($(this).text()) != '', "Share count doesnt appear");
			});
		});
	});
}