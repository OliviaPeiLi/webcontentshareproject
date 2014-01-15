/*
 * Search operations
 * @link /search?q=alexi
 * @uses jquery
 */
define(['jquery'], function() {
	/* ====================== Direct code ======================= */
	
	//Mixpanel tracking
	if (typeof(mixpanel) !== 'undefined') {
		var user = php.userId ? php.userId : 0;
		mixpanel.people.identify(user);
		mixpanel.track('Search', {'user':user, 'query': php.query});
	}
	
	if ($('#created_collection_msg').length > 0)	{
		$('#created_collection_msg span.close').click(function(){
			$('#created_collection_msg').remove();
		});
	}

	
});
