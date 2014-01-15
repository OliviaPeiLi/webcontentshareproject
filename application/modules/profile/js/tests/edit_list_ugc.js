QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'base' )	{
	testModule( 'Validation', function () {
		var form = '.newList_body form';
		//var error = form+' .error';
		var error = '#notification_bar p'; 
		
		testInvisible("Basic state", error);
		
		testVisible("Name - required", error)
		.trigger(function() {
			$(form+' [name=folder_name]').val('');
			$(form).submit();
		})
		
	});
	
}