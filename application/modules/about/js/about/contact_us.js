/**
 * Logic for contact us form
 * @link /about/contactus
 * @uses jquery
 */
define(["jquery"], function() {
	
	/* ========================== Events ======================== */
	
	/**
	 * Form success
	 * @to-do use common/formValidation
	 */
	$(document)
		.on('success','#contact_us_form', function(event, data) {
			var self = $(this);
			
			if ( ! data.status) {
				console.error(data);
				return;
			} else self.trigger('reset');
	
		});
});