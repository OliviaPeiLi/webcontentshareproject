/**
 * Code for "open for contributors" folders popup which shows after clicking on +Add
 * @link - profile page
 * @uses jquery
 */
define(['jquery'], function() {
	/* ================= Variables =================== */
	var self = this;
	
	/* ================= PRivate functions ================== */
	
	/**
	 * Selects current thumbnail after clicking on left, right arrows and on step2 init
	 */
	self.set_thumb = function(i) {
		var $form = $('#drop_into_folder_popup #internal_scraper');
		var form = $form.get(0);
		if (!form.thumbs) return ;
		$form.find('.postbox_img_preview .total').html(form.thumbs.length);
		$form.find('.postbox_img_preview .selected').html(i+1);
		$form.find('.postbox_img_preview img').attr('src', form.thumbs[i]);
		$form.find("[name='activity[link][img]']").val(form.thumbs[i]);		
	}

	
	/* =================== Events ====================== */
	
	/**
	 * Submit the popup form on enter
	 */
	$(document).on('keypress','#drop_into_folder_popup .step1 textarea',function(e){
		if (e.keyCode == 13) {
			if ($.trim($(this).val()) != '') $(this).closest('form').submit();
			return false;
		}
	});
	
	/**
	 * Pull the link / Step1 Form submit
	 */
	 var selector = '#drop_into_folder_popup #scraper_form2';
	$(document)
		.on('preAjax', selector, function() {
			var img = $('#internal_scraper .postbox_img_preview img'); 
			img.attr('src', img.attr('data-loader') );
			$('#drop_into_folder_popup #internal_scraper').show();
			$('#drop_into_folder_popup #add_popup_step1').hide();
		})
		.on('success',selector, function(e, data) {
			var $form = $('#drop_into_folder_popup #internal_scraper');
			if (!data.status) {
				console.info('Error', data);
				return;
			} else {
				data = data.data
			}
			var thumbs = [];
			for (var i=0;i<data.length; i++) thumbs.push(data[i].src);
			$form.get(0).thumbs = thumbs;
			$form.find('.title').html(data[0].alt);
			$form.find("[name='activity[link][link]']").val(data[0].link);
			set_thumb(0);
		});

	/**
	 * Add the data to the backend / Step2 form submit
	 */
	 var selector = '#drop_into_folder_popup #internal_scraper';
	$(document)
		.on('validate', selector, function(e,callback) {
			$(this).find('.error').hide().remove();
			var description = $(this).find("[name='activity[link][text]']");
			if (!description.val() || description.val() == description.attr('placeholder')) {
				$(this).find('.error').html('You Need To Enter A Description');
				callback.call(this, {status:false});
			} else {
	            $(this).find('[type=submit]').removeClass('blue_bg').addClass('disabled_button').attr('disabled','disabled').val('Dropping…');
	            callback.call(this, {status:true});
	        }
		})
		.on('preAjax', selector, function() {
			var img = $('#internal_scraper .postbox_img_preview img'); 
			img.attr('src', img.attr('data-loader') );		
		})
		.on('success', selector, function(e, data) {
			if (!data.status) {
				console.info('ERROR', data);
			}
			$('#drop_into_folder_popup').modal('hide');
			window.location.href = data.url;
		});
	
	/*
	 * Select prev thumbnail
	 * @uses self.set_thumb()
	 */
	 var selector = '#drop_into_folder_popup #internal_scraper .postbox_img_preview a.left';
	$(document).on('click', selector, function() {
		if ($(this).hasClass('disabled')) return false;
		var total = $(this).closest('form').find('.total');
		var current = $(this).closest('form').find('.selected');
		$(this).closest('form').find('a.right').removeClass('disabled');
		if (current.html() == '2') $(this).addClass('disabled');
		current.html(parseInt(current.html()-1));
		set_thumb(parseInt(current.html()-1));
		return false;
	});
	/*
	 * Select next thumbnail
	 * @uses self.set_thumb()
	 */
	 var selector = '#drop_into_folder_popup #internal_scraper .postbox_img_preview a.right';
	$(document).on('click', selector, function() {
		if ($(this).hasClass('disabled')) return false;
		var total = $(this).closest('form').find('.total');
		var current = $(this).closest('form').find('.selected');
		$(this).closest('form').find('a.left').removeClass('disabled');
		if (parseInt(current.html()) == parseInt(total.html())-2) $(this).addClass('disabled');
		current.html(parseInt(current.html())+1);
		set_thumb(parseInt(current.html()-1));
		return false;
	});
	
	/**
	 * Use screenshot checkbox. Replaces selected thumbnail with HMTL placeholder icon. 
	 */
	 var selector = '#drop_into_folder_popup #internal_scraper .use_screenshot';
	$(document).on('change', function(e) {
		if ($(this).is(':checked')) {
			$('#internal_scraper .postbox_img_preview img').attr('src', '/images/RSS_d_large.png');
			$("#internal_scraper [name='activity[link][img]']").val('');		
		} else {
			var current = $(this).closest('form').find('.selected');
			set_thumb(parseInt(current.html())-1);
		}
	});
});