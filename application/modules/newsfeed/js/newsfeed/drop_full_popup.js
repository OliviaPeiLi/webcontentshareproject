/**
 * Newsfeed preview popup used in notifications and activity. Extending instant popup code
 * @link /
 * @uses newsfeed/drop_preview_popup - the parent module
 * @uses jquery
 */
define(['newsfeed/drop_preview_popup','jquery'], function(popup) {

	//BP: #FD-2216
	//re-enable the rel=popup attribute
	var links = $( "a[href='#preview_popup'][rel='popup-disabled'].link-popup" );
	for ( var i = links.length - 1; i >= 0; --i ) {
		links[i].setAttribute( 'rel', 'popup' );
	}
	//end of #FD-2216

	/**
	 * Drop full popup (used in notifications and activity)
	 */

	$(document).on('before_show', "a[href='#preview_popup'].link-popup", function(e, content) {

		console.info('{drop full popup} - open');
		popup.set_popup_size($('#preview_full_popup'));

		var data = {
			'id': $(this).attr('data-newsfeed_id'),
			'drop_link': '/drop/'+( $(this).attr('data-url') || $(this).attr('data-newsfeed_url') || $(this).attr('data-newsfeed_id') ),
			'drop_image': ( $(this).attr('data-thumbnail') ? $(this).attr('data-thumbnail').replace('small','thumb') : ''),
			'drop_title': $(this).attr('data-description'),
			'drop_desc': $(this).attr('data-description'),
			'drop_desc_plain': $(this).attr('data-description'),
			'complete' : $('img',this).attr('data-complete')
		};

		console.info('{drop full popup} - basic data', data);
		popup.set_popup_basic_data(content, data);

		// http://dev.fantoon.com:8100/browse/FD-3956
		if ($(this).closest("div.new_activity_feed"))	{
			// add flag to point all next and previous object will get from activity tab
			$('#preview_popup').addClass("newsfeed_activity");
			// init arrows
			popup.init_arrows( content, $(this).closest('li') );
		}

		$.get('/popup-info/'+data['id']+'/extended', function(data) {
			
			popup.set_popup_link_data(content, data);
			popup.set_popup_data(content, data);
			// FD-3534
			if ( data['iframe'] ) {
				content.find('.preview_popup_main iframe')
					.width(parseInt(Math.abs(data['width'])) > 10 ? Math.abs(data['width']) : 800).height(parseInt(Math.abs(data['height'])) > 10 ? Math.abs(data['height']) : 800)
					.attr('src', php.baseUrl.replace('https://','http://') + data['iframe'] );
			}

		},'json');

	});
});