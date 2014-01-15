{
				content: {
					text: 'loading...',
					ajax: {
						url: '/interest_detail', // URL to the local file
						type: 'POST', // POST or GET
						data: {
							page_id: '33',
							ci_csrf_token: '33'
						},
						once: false,
						success: function(data, status) {
							this.set('content.text', data);
						}
					}
				},
				position: {
					my: '44',
					at: '44',
					adjust: { x: '44' }
				},
				overwrite: false,
				show: {
					solo: true,
					event: 'mouseenter',
					delay: 200,
					effect: { type: 'fade' },
                    ready: true
				},
				hide: false,
				style: {
					classes: 'ui-tooltip-rounded ui-tooltip-fandrop ui-tooltip-fantoon-shadow'
				},
				events: {
					render: function (event, api) {
						$('.qtip').mouseenter(function() {
                            //on_detail = true;
							on_li = false;
						});

						$('.qtip').mouseleave(function() {
                            setTimeout(function() {
                                if (!on_li) {
                                    $(this).hide();
                                }
                            },200);
						});
					}
				}
			}