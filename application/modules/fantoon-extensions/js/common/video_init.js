/**
 * Replaces data-videourl  in iframe to its src to speed up page loading
 * @link /about
 * @link /	  @deprecated - was used in landing page
 * @uses jquery
 */
define(['jquery'], function() {

	var selector = '.js-video_init';

	$(document)
	.on('shown', selector, function(){
		if (typeof(mixpanel) !== 'undefined') mixpanel.track("Intro Video");
	})
	.on('show', selector, function() {
		var video = $(this).find('#fandrop_intro_video');
		video.attr('src', video.data('videourl')+'&amp;autoplay=1');
	})
	.on('hide', selector, function() {
		$(this).find('#fandrop_intro_video').attr('src','');
	});

});
