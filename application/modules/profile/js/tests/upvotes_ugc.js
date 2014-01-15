/**
 * Profile page test
 * @link http://ft/test_user1
 * @to-do Test collaborators
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'avatar' ) {
	
	if ( qunit_module === undefined || qunit_module == 'upvote page' )	{
		testModule( 'Upvotes list page', function () {

			var items_wrap = '#list_newsfeed';
			var item = ' .newsfeed_entry';

			test("basic page state", function() {
				ok($(items_wrap + item).length, "no results found");
			});

		});
	}

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