/**
 * Profile page test
 * @link http://ft/test_user1
 * @to-do Test collaborators
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'base' )	{
	testModule( 'Autoscroll', function () {
		var len;
		test("Basic state", function() {
			ok( $('#folders .folder_ugc').length);
			len = $('#folders .folder_ugc').length;			
		});
		
		asyncTest("Autoscroll", 1, function() {
			var interval = window.setInterval(function() {
				if ($('#folders .folder_ugc').length > len) {
					window.clearInterval(interval);
					ok(true); start();
				}
			}, 100);
			
			window.scroll(0,document.body.offsetHeight);
		});
		
		test("Test 16 item folder data-url is exists",function(){
			ok( $('#folders .folder_ugc:eq('+len+') h2 a').attr("href").indexOf("//") == -1, " No collection url found." );
		});
	});
}
if ( qunit_module === undefined || qunit_module == 'notifications' ) {
	//RR - notifications are not ready yet
}