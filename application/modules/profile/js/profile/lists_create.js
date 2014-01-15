define(['jquery', 'jquery-ui', 'common/formValidation'], function() {
	
	var self = '.newList_body';
	var hashtags_container = self+' .form_row.hashtags';
	var hashtags_input = hashtags_container+' label input';
	
	$(document)
		.on('focus',hashtags_input, function() {
			var $this = $(this);

			$this.autocomplete({
				'source': '/hashtags?add=true',
				'autoFocus': true,
				'select': function(e, ui) {
					var has_it = false;
					$('#hashtags_template').nextAll('li').each(function() {
						console.info($.trim($(this).find('strong').text()), ui.item.value);
						if ($.trim($(this).find('strong').text()) == ui.item.value) has_it = true;
					});
					if (has_it) {
						$this.val('');
						return;
					}
					$('#hashtags_template').after($('#hashtags_template').tmpl(ui.item));
					window.setTimeout(function() {
						$this.val('');
					}, 100)
				}
			});

		}).on('keydown',hashtags_input, function(e) {
			var $this = $(this); 
			if (e.which == 188) {
				var val = '#'+$this.val().replace('#', '');
				$('#hashtags_template').after( $('#hashtags_template').tmpl({'value': val}) );
				$this.val('');
				return false;
			}
	});
	
	$(document).on('click',hashtags_container+' ul li a', function() {
		$(this).closest('li').remove();
		return false;
	});
	
	var submit_action = '';
	$(document).on('click',self+' form [type=submit]', function() {
		submit_action = $(this).attr('name');
	});
	
	$(document).on('success', self+' form', function(e, res) {
		if (!res.status) {
			$(this).find('.error:first').show().html(res.error);
			return;
		}
		console.info('redirecting...');
		
		if (submit_action == 'finish') {
			window.location.href =  '/manage_lists/'+res.folder_id;
		} else {
			window.location.href =  '/manage_lists/'+res.folder_id+'?add=true';
		}
		
	});

	return this;
});
