/**
 * Search page test (drops)
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

var query = queryParams().q;

testModule( 'Search drops', function () {
	
	var keyword = ' .searchHeader h1';
	var folders = '#folders';
	var items = ' .folder_ugc';
	var message_popup_dialog = '#created_collection_msg';
	var new_collection_name_a = '#new_collection_name';

	if (query == 'test')	{
		// search by keyword

		test("basic page state", function() {
			console.warn('search by keyword');
			equal($('#home ' + keyword).text(), query);
			ok($(folders+items).length, "no results found");
		});
	}

	if (query.indexOf("#") === 0)	{
		// search by hashtags
		
		test("basic page state", function() {
			console.warn('search by hashtags');
			equal($('#home ' + keyword).text(), query);
			ok($(folders+items).length, "no results found");
		});
	}

		// predicted to no have 16 elements
		/*
		testExists("Autoscroll", folders + items + ':eq(16)')
			.trigger(function()	{
				window.scroll(0,document.body.offsetHeight);
		});
		
		test("Test 16 item folder data-url is exists",function(){
			ok( $(folders + items + ':eq(16)').attr("data-url").indexOf("//") == -1, " No list url found." );
		});
		*/
});
