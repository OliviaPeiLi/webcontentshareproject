/**
 * Search page test (people)
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

var query = queryParams().q;

testModule( 'Search People', function () {
	var keyword = '.primary_title';
	var newsfeed = '#show_connections';
	var items = ' li .user_badge_right';
	
	test("basic page state", function() {
		equal($(keyword).text(), query);
		ok($(newsfeed+items).length, "no results found");
	});
	
});