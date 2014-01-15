/**
 * Badge (common)
 * Logic for handling opening and hiding of badges 
 * and logic inside badges (such as opening graph popup)
 * All divs that have class 'show_badge' triggers logic in this module
 * @uses jquery
 * @since - RR 1/4/2013 - moved to profile from common because this is not a plugin
 */

var badge_cache = {};
define(['jquery'], function(){

	var win_width=$(window).width();
	
	function position_badge(triggerer) {
		var top = triggerer.offset().top - $('#badge').height()/2;
		var left = triggerer.offset().left - $('#badge').width()/2;
		
		$('#badge').show().css({
			'top': Math.min(Math.max($(window).scrollTop(), top), $(window).scrollTop()+$(window).height()-$('#badge').height() - 20) + $('.popup_right-likes').height(),  
			'left': Math.min(Math.max(10, left), $(window).width() - $('#badge').width() - 20)
		});
	}

	var badge_selector = '.show_badge';
	
	$(document).on('mouseover', badge_selector, function() {
		
		var user_id = $(this).attr('data-user_id');
		var $this = $(this);
		
		//check if this badge is not already cached
		if(badge_cache[user_id]){
			$('#badge').html(badge_cache[user_id]);
			position_badge($this);
			return;
		}

		if ($(this).hasClass('loading')) return;
		var $this = $this.addClass('loading');
		if (! $this.attr('data-user_id')) return;
		if (!$('#badge').length) $('body').append('<div id="badge" style="display:none"></div>');
		console.info($('#badge'));
		$('#badge').load('/badge/user/'+$this.attr('data-user_id'), function() {
			if ($this.hasClass('loading')) {
				$this.removeClass('loading');
				position_badge($this);
				badge_cache[user_id]= $("#badge").html();				
			}
		})
	}).on('mouseout', badge_selector, function() {
		$(this).removeClass('loading');
	});
	
	$(document).on('mouseleave','#badge', function() { $(this).hide(); });
	$(document).on('click', function(e) {
		if (!$(e.target).closest('#badge').length) {
			$('#badge').hide();
		}
	});
});
