/**
 * Deals with all AJAX form submissions on site 
 *  contains 3 components:
 *		init (submit click event)
 *		ajaxRequestHandler
 *		ajaxResponseHandler
 * @package		ajaxForm
 * @author		Dmitry Kashlev & Radil Radenkov
 * @uses		jquery
 *
 */
define(['jquery'], function() {

	/**
	 * Called on click
	 */
	$(document).on('click','[rel=ajaxButton]', function() {

		// ability to disable the button via css
		if ($(this).hasClass("js-disabled"))	{
			return false;
		}

		var $self = $(this);
		// prevent multiple click
		if ($self.hasClass('loading')) return false;
 		$self.addClass('loading');

		console.info('call preAjax', $self);
		$self.trigger('preAjax');

		//Initialize plugin
		var has_validate = typeof $._data(this, "events") != 'undefined' && typeof($._data(this, "events").validate) !== 'undefined';
		console.log('has_validate: '+has_validate);

		if (has_validate) {
			console.log('AJAXButton: validate');
			$self.trigger('validate', function(response) {
		      	if (response.status) {
		       		ajaxRequestHandler($self);
		 			$self.trigger('postAjax');
		      	}
	     	});

		} else {

			console.log('AJAXButton: no validate');
			ajaxRequestHandler($self);
			$self.trigger('postAjax');

		}

		$self.addClass('initalized');
		return false;
	});

	/**
	 * Called on ajax response
	 */
    function ajaxRequestHandler($self) {

		
 		console.info('SUBMIT');
        //Send ajax request
 		var url = $self.attr('href') ? $self.attr('href') : $self.data('url');
 		if (url === '' ) {
 			console.log('if');
 			document.location.href = '/signup';
 		} else {

 			console.log('else');
 			var params = '';

 			if ($self.find('form').length) {
 				params = $self.find('form').serialize();
 			} else if ($self.attr('data-params')) {
 				params = $self.attr('data-params');
 			}

 			if ($self.data('method') && $self.data('method').toLowerCase() == 'post' && params.indexOf(php.csrf.name) == -1) {
 				params += '&'+php.csrf.name+'='+php.csrf.hash;
 			}

			$.ajax({
				url: url,
				type: ( $self.data('method') ? $self.data('method') : 'GET' ),
				dataType: ( $self.attr('data-type') ? $self.attr('data-type') : 'json' ),
				data: params,
				success: function(data) {
					console.log('success');
					if (!data) return;
					console.log('success');
					console.info('SUCCESS 0', data);
					//console.log($self);
					$self.removeClass('loading');
					console.log($self);
					console.warn('self',$self);
					$self.trigger('success',[data]);

					// if there is notification setting, showing a notification box
					console.info('data.status=(', data.status, ')data.error=(', data.error,')');
					if (typeof(data.status) !== 'undefined' && ( data.status == true || data.status == 'ok' )) {
						// success -> notify data from 'success' attribute
						if ($self.attr('success') && $self.attr('success') != undefined && $self.attr('success') !== '') {
							$(document).trigger('popup_info', $self.attr('success'));
						}
					} else {
						// fail -> notify error returned from BE
						if (typeof(data.error) !== 'undefined' && data.error !== '') {
							$(document).trigger('popup_info', data.error);
						}
					}
				}
			});
		
		} 
        return;
    }

});
