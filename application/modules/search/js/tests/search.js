/**
 * Search page test (drops)
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

var query = queryParams().q;

testModule( 'Search drops', function () {
	
	var keyword = '.primary_title';
	var newsfeed = '#list_newsfeed';
	var items = ' .newsfeed_entry';
	var message_popup_dialog = '#created_collection_msg';
	var new_collection_name_a = '#new_collection_name';
	
	test("basic page state", function() {
		equal($(keyword).text(), query);
		ok($(newsfeed+items).length, "no results found");
	});
	
	if (queryParams().action == 'created_collection')	{
		// begin test
		test("popup message for a new collection",function()	{
			ok($(message_popup_dialog).length,"No popup found.");
			equal(queryParams().name,$(new_collection_name_a).text());
		});
		testNotExists("Close popup button test",message_popup_dialog)
		.trigger(function(){
			$(message_popup_dialog + " span.close").trigger("click");
		});
	} else {
		testExists("Autoscroll",'#folders div.js-folder:eq(16)')
			.trigger(function()	{
				window.scroll(0,document.body.offsetHeight);
		});
		
		test("Test 16 item folder data-url is exists",function(){
			ok( $('#folders div.js-folder:eq(16)').attr("data-url").indexOf("//") == -1, " No list url found." );
		});
		
	}
		
});
