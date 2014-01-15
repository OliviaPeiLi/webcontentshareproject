QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'base' )	{
	test("basic page state", function() {
		ok($('.listManager_createList_button').length);
		ok($('.listManager_listList li').length);
		ok($('.allLists_body li').length)
	});
}