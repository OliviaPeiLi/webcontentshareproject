/* *********************************************************
 * Page Lists
 *  sends the follow list or unfollow list request 
 *
 * ******************************************************* */

define (['jquery'],  function() {	
	
		$('.follow_list').live('click', function() {
			var submit_url = $(this).attr('href');
			var btn = $(this);
            $.get(submit_url, function(data) {
                //console.log(data);
                btn.parent().append($(data));
                btn.remove();
            });
			return false;
		});

		$('.unfollow_list').live('click', function() {
			var submit_url = $(this).attr('href');
			var btn = $(this);
            $.get(submit_url, function(data) {
                //console.log(data);
                btn.parent().append($(data));
                btn.remove();
            });
			return false;
		});		

});
