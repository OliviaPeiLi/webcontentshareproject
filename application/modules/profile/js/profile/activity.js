/**
 * Newsfeed Activity action
 * Click on the activity item will open the drop that activity related to
 */
define(['jquery'],function(){

	var activity_entry = '.profile_newsfeed_entry';

	$(document).on('click', activity_entry, function(e){
		if ( $(e.srcElement).hasClass('newsfeed_text_entry') ) {
			e.preventDefault();
			e.stopPropagation();

			if ( $(this).find('[rel="popup"]').length ) {
				$(this).find('[rel="popup"]').click();
			}
		}
	});
	
	$( document ).on( 'scroll_bottom', 'div.js_activity', function() {
		if ( this.ajaxList_process instanceof Function ) return;
		this.ajaxList_process = function ( data ) {   
		   $('a.activity_user_from',this).html( data.user_from.first_name );

		   if (typeof data.newsfeed_id != 'undefined') {
			   $('a.newsfeed_avatar_a',this).hide();
			   $('a.link-popup',this).show();
		   } else  {
			   $('a.newsfeed_avatar_a',this).show();
			   $('a.link-popup',this).hide();
		   }

		   if (data.type = 'connection' || data.type == 'folder_user') {
			   $('.newsfeed_entry_avatar_user.link_avatar',this).addClass('show_badge').addClass('user_avatar');
		   }

		   if (typeof data.af_type != 'undefined') {
			   $('.af_icon',this).addClass(data.af_type);
		   }

		   $('.post_info',this).attr( "id", "activity_" + data.id );
	   }

   });

	return this;
});
