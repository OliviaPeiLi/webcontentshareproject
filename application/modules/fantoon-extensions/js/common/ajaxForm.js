/**
 * ajaxForm
 * Deals with all AJAX form submissions on site 
 *  contains 3 components:
 *		init (submit click event)
 *		ajaxRequestHandler
 *		ajaxResponseHandler
 * @uses jquery
 * @uses formValidation to trigger the validations on submit
 */
//define(['jquery'], function() { //DK - REVERT {ADDED}
define(['jquery', 'common/formValidation'], function() { //DK - REVERT {REMOVED}


	function disableForm ( $self ) {
		$self.addClass( 'loading' );
		$self.find( 'input[type="submit"]' ).attr( 'disabled', 'disabled' );
		$self.find( 'a[data-dismiss="modal"]' ).attr( 'disabled', 'disabled' ).addClass( 'disabled_button' );
	}

	function enableForm ( $self ) {
		$self.removeClass( 'loading' );
		$self.find( 'input[type="submit"]' ).removeAttr( 'disabled' );
		$self.find( 'a[data-dismiss="modal"]' ).removeAttr( 'disabled' ).removeClass( 'disabled_button' );
	}

	/**
	 * called on form.submit
	 * @since RR 7/18/2012 - Added postAjax event. Extecutes after the ajax is sent before its loaded. good for cleaning out stuff.
	 * @since DK 8/28/2012 - Renamed data(preAjax) event to data(validate) event to handle server-saide validation before final request is sent
	 */

	$(document).off('submit','[rel=ajaxForm]').on('submit','[rel=ajaxForm]', function(event) {

		var $this = $( this );
		if ($this.hasClass('error')) return false;
		event.preventDefault();
		
		if ($this.hasClass('loading')) return false;
		
		console.log($this);
		console.log('FDAJAXFORM SUBMIT');

		disableForm( $this );
		$this.trigger('preAjax');
        // prevent multiple click

		var has_validate = typeof $._data(this, "events") != 'undefined' && typeof($._data(this, "events").validate) !== 'undefined';
		
		console.log('has_validate: '+has_validate);
		if (has_validate) {
			console.log('AJAXForm: validate');
			$this.trigger('validate', function(response) {
				console.info('AJAXForm: response', response);
		      	if (response.status) {
		       		ajaxRequestHandler($this);
		 			$this.trigger('postAjax');
		      	} else {
		 			enableForm( $this );
		 		}
	     	});
		} else {
			console.log('AJAXForm: no validate');
			ajaxRequestHandler($this);
			$this.trigger('postAjax');
		}
					
		$this.addClass('initalized');
		return false;
	});
	
	/**
	 * Called on ajax response
	 */
    function ajaxRequestHandler($self) {
 		//console.log($self);
 		console.info('{ajaxForm} - Submit', $self.attr('action'));
 		if ($self.attr('data-progress')) {
 			$(document).trigger('popup_info', $self.attr('data-progress'),'loading');
 		}
 		//console.log($self.serialize());
        //Send ajax request
		$.ajax({
			url: $self.attr('action'),
			type: $self.attr('method'),
			dataType: ( $self.attr('data-type') ? $self.attr('data-type') : 'json' ),
			data: $self.serialize(),
			success: function(data) {
				console.info('{ajaxForm} - responce', data);
				enableForm( $self );
				$self.trigger('success',[data]);

				// if there is notification setting, showing a notification box
				//console.info('data.status=(', data.status, ')data.error=(', data.error,')');
				if (typeof(data.status) !== 'undefined' && ( data.status == true || data.status == 'ok' )) {
					// success -> notify data from 'success' attribute
					if ($self.attr('success') && $self.attr('success') != undefined && $self.attr('success') !== '') {
						//BP: this check is added due to #FD-2996
						//var nopopupinfo = $self.attr( 'data-nopopupinfo' );
						//if ( nopopupinfo != 'true' && nopopupinfo != 'success' ) {
							$(document).trigger('popup_info', [$self.attr('success'), 'success']);
						//} 
					}
				} else {
					// fail -> notify error returned from BE
					console.log('{ajaxForm} - Success but something wrong in data returned');
					if (typeof(data.error) !== 'undefined' && data.error !== '') {
						//BP: this check is added due to #FD-2996
						//var nopopupinfo = $self.attr( 'data-nopopupinfo' );
						//if ( nopopupinfo != 'true' && nopopupinfo != 'error' ) {
						if ($self.attr('data-error')) {
							$(document).trigger('popup_info', [data.error.replace(/\n/,"</p><p>"), 'error']);
						} else if ($self.find('.error').length) {
							$self.find('.error:first').html(data.error).show();
						}
					}
				}
			
			},
			error: function(xhr, status, error) {
				console.log('{ajaxForm} - Fail', xhr/*.responseText.substr(0, 10000)*/, status, error);
				enableForm( $self );
			}
		})
		/*.fail(function(data) {
			console.log('{ajaxForm} - Fail', JSON.stringify(data));
			enableForm( $self );
		});*/
        return;
    }

});
