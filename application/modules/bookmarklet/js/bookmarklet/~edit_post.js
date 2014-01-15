/**
 * 
 */
define(["bookmarklet/communicator", "plugins/token-list", "jquery"], function(communicator) {
	
	$('a.close, a.done-btn').click(function() {
		communicator.close_popup();
	});
	
	communicator._onload = function() {} // These two are used
	communicator.init();                 // for the IE fix. in communicator
	
	communicator._onafter_update = function(data) {
		$('.form img')
			.unbind('load')
			.bind('load', function() { setImageSize($(this)); })
			.attr('src', data.thumb)
	}
	
	function setImageSize(img) {
		if (img.width() < img.height()) {
			img.css({ 'max-height':'', 'max-width':img.parent().width(), 'margin-top': -(img.height()-img.parent().height())/2 })
		} else {
			img.css({'margin-left': -(img.width()-img.parent().width())/2})
		}
	}
	$('.form img').each(function(){
		if ($(this).width()) {
			setImageSize($(this));
		} else {
			$(this).bind('load', function() { setImageSize($(this)); });
		}
	});
	
	$('#bookmarklet_interests_open').live('click', function() {
		$(this).hide();
		$('#bookmarklet_interests').show();
		return false;
	});
	
	$('#done_with_selection').live('click', function() {
	   $('#bookmarklet_interests').hide();
	   $('#bookmarklet_interests_open').show();
	   $('form').submit();
	   return false;
	});
       
	var updater = null;
	$('.media_text').live('keyup', function() {
		if (updater) clearTimeout(updater);
		var $self = $(this);
		updater = setTimeout(function() { $self.parents('form').submit(); }, 500);
	});
	
	$('select[name=folder_id]').live('change', function() {
		$(this).parents('form').submit();
	});
	
	$('form.edit_post_form').live('submit', function() {
		console.info('update user text');
		var $form = $(this);
		$form.find('.data_status').addClass('in_progress').text('Saving…');
		$.post($form.attr('action'), $form.serialize(), function(data) {
			if (data.status === 'OK') {
				$form.find('.data_status').removeClass('in_progress').text('Saved!');
				communicator.update(data);
			}
		}, 'json');
		return false;
	});
		
	this.communicator = communicator;
	return this;
});