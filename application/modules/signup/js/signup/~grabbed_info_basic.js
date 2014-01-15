define(['jquery'], function(){
	$(function() {
		var v = $('.youtube-player').clone();
		var p = $('.youtube-player').parent();
		$('.youtube-player').hide().remove();
		$('#open_grabbed_info_popup').click();
		
		$('#preview_info_finish').click('live', function() {
			console.log('1');
			var $self = $('#grabbed_info_body form');
			$.ajax({
				url: $self.attr('action'),
				type: $self.attr('method'),
				dataType: 'json',
				data: $self.serialize(),
				success: function(data) {
					if (data.status === 'OK') {
						console.log('2');
					} else { console.log('3'); }
				}
			});
			p.prepend(v);
			$('#grabbed_info_body').modal('hide');
			return false;
		});
	});
	
	
});