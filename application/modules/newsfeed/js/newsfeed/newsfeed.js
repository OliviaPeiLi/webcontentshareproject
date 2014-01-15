/**
 * Newsfeed General logic - delete, scroll to top
 * @uses like/newsfeed - for up/down vote logic
 * @uses social/all - for the facebook button in the newsfeed
 */
define(['like/newsfeed', 'common/autoscroll_new', 'social/all', 'jquery'],function(){
	
	var self = this;
	
    /**
     * Enlargement of images upon hover in List view newsfeed items
     * @deperecated - RR
     */
	//$('.newsfeed_entry_photo img, .page_album_img img').on('mouseover', function(){
	//	$(this).addClass('img_hover');
	//	return false;
	//}).on('mouseout', function() {
	//	$(this).removeClass('img_hover')
	//	return false;
	//});
	
	/**
	 * RR - Probably to prevent popup, but I am not really sure what it does
	 */
	$(document).on('click','.newsfeed_entry submit, .newsfeed input', function(e) {
		e.stopPropagation();
	});
	
	//@update RR - 07/17/2012 - some preAjax stuff for faster UI
	$(document).on('preAjax', '#delete_dialog .delete_yes', function() {
		var newsfeed_id = $(this).attr('href').replace('/del_link/','');
		console.info('{newsfeed} - delete fast...', newsfeed_id);
		$('#delete_dialog').modal('hide');		
		$("#preview_popup").modal('hide');//FD-3388
		//bobef: #FD-2163
		$( '#newsfeed_popup_edit:visible' ).find( '.new_close' ).click();
		//end of #FD-2163
		
		$('[data-newsfeed_id='+newsfeed_id+']').hide();
	});
    
    /*
     * "Scroll to top" Button
     */
    $(window).scroll(function(){
		if($(this).scrollTop()>=100)
			$('#ScrollToTop').fadeIn();
		else
			$('#ScrollToTop').fadeOut();
	});
    
    /*
     * Scroll to top button
     */
	$("#ScrollToTop").click(function(){
		$("html, body").animate({scrollTop:"0px"},400);
		return false
	});

	$('#filter_by_menu_trigger').click(function(event){
		event.preventDefault();
		window.location.href = $(this).attr("data-url");
	return false;
	})

	return this;
});
