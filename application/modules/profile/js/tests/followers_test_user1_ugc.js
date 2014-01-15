/**
 * Profile page test
 * @link http://ft/test_user1
 * @to-do Test collaborators
 */

	QUnit.Utils._export( this );

	var qunit_module = queryParams().qunit_module;

	testModule( 'Autoscroll', function () {
		
		testExists("Autoscroll",'#following li:eq(25)')
			.trigger(function()	{
				window.scroll(0,document.body.offsetHeight);
		});
		
		var status = false;

		testEvent('Followers', '#following .unfollow_button.eq(0)', 'success', function() {
			ok( $('#following .request_follow.eq(0)').is(":visible") , " Unfollow button doesn't work." );
		})
		.trigger(function(){
			$('#following .unfollow_button.eq(0)').trigger("click");
		});

	});