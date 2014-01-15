/* *********************************************************
 * Image search in google (for image upload)
 *
 * ******************************************************* */

define(['jquery'], function(){
	console.log('gis_view.js');
	
	//Auto submit
	 $("#gis_images_form input[name='query']").live('blur', function(){
		$(this).closest('form').submit();
	 });
	 $(document).live('update', function() {
		 if ($('#gis_results').length && !$('#gis_results').html() ) {
			 $('#gis_images_form').submit();
		 }
	 })

	 //do Search
	$('#gis_images_form').live('submit', function() {
		
		$(".images").html($("#loader").html());
		
		$.get($(this).attr('action'), $(this).serialize(), function(data){
			$("#gis_results").html(data);

			$("#prev_btn").click(function(){
				$("#gis_images_form input[name='page']").val(parseInt($("#gis_images_form input[name='page']").val())-1);
				$('#gis_images_form').submit();
			});
			$("#next_btn").click(function(){
				console.info(parseInt($("#gis_images_form input[name='page']").val()));
				$("#gis_images_form input[name='page']").val(parseInt($("#gis_images_form input[name='page']").val())+1);
				$('#gis_images_form').submit();
			});
		});
		return false;
	}).trigger('submit');

});