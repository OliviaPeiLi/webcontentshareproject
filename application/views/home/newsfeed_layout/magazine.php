<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/home/newsfeed_layout/magazine.php ) --> ' . "\n";
	} ?>
<?php echo $mag_lay->rend_social();?>
<script type="text/javascript">
			var page = 1; var loading = false;
			
            var h3div = $('#mag_lay_popup');
            
   			$(document).ready(function() {
   				init_mag_lay();
   				
   				if (! h3div.length) {
                    h3div = $(document.createElement('div'))
                    			.attr('id', 'mag_lay_popup')
                    			.css({'position':'absolute'})
                    $('#mag_lay').append(h3div);
                }
   				h3div.mouseout(function() {
                	$(this).hide();
            	}).hide();
				
   				// no image posts - set title
				var loaded = 0;
				$('#mag_lay img').error(function() {
					$(this)
						.hide()
						.parents('.media:first').hide()
						.parents('.mag_lay-content').find('h3').css({width:'100%'});
					loaded++;
				}).load(function() {
					loaded++;
					if (loaded == $('#mag_lay img').length) {
						$('#mag_lay img').each(function() {
							if (!$(this).attr('src')) $(this).parents('.media:first').hide();
							//init_post($(this).parents('.mag_lay-row:first'));
							//Causes FF to break
						});
					}
				}).each(function() {
					if (!$(this).attr('src')) {
						$(this).parents('.media:first').hide();
						loaded++;
					}
				});
				
				//set row size
				$('#mag_lay .mag_lay-col:not(.changed)').each(function(){
					var min = 0; var min_el = null;
					var max = 0; var max_el = null;
					$(this).find('.mag_lay-content').each(function() {
						if (min > $(this).height()-$(this).parents('.mag_lay-row ').height()) {
							min_el = ($(this));
							min = $(this).height()-$(this).parents('.mag_lay-row ').height();
						}
						if (max < $(this).height()-$(this).parents('.mag_lay-row ').height()) {
							max_el = $(this);
							max = $(this).height()-$(this).parents('.mag_lay-row ').height();
						}
					});
					if (!min_el || !max_el) return;
					
					var i = 1000;
					while(i && min_el.parents('.mag_lay-row').height() > $(this).height()*0.167 && min_el.height() < min_el.parents('.mag_lay-row ').height()) {
						min_el.parents('.mag_lay-row').height(min_el.parents('.mag_lay-row').height() - 1);
						max_el.parents('.mag_lay-row').height(max_el.parents('.mag_lay-row').height() + 1);
						i--;
					}
				});
				$('#mag_lay .mag_lay-col.changed').each(function(){
					var min = 100000; var min_el = null;
					var max = 0; var max_el = null;
					var max_w = 0; var max_width_el = null;
					$(this).find('.mag_lay-content').each(function() {
						if (min > $(this).height()) {
							min_el = ($(this));
							min = min_el.height();
						}
						if (max < $(this).height()) {
							max_el = $(this);
							max = max_el.height();
						}
						if (max_w < $(this).width()) {
							max_w = $(this).width();
							max_width_el = $(this).parents('.mag_lay-row');
						} 
					})
					
					var i = 1000;
					var min_width = Math.max($(this).width()*0.167,$(min_el).find('.link').width()) + 17;
					$(this).find('.mag_lay-row').each(function() {
							$(this).width($(this).width());
							i = 1000;
							if ($(this) != max_width_el) while (i-- && $(this).width() < min_width) {
								$(this).width($(this).width() + 1);
								max_width_el.width(max_width_el.width() - 1);
							}
					});
					          
					i = 1000
					while(i-- && min_el.parents('.mag_lay-row').width() >  min_width && min_el.height() < max_el.height()) {
						min_el.parents('.mag_lay-row').width(min_el.parents('.mag_lay-row').width() - 1);
						max_el.parents('.mag_lay-row').width(max_el.parents('.mag_lay-row').width() + 1);
					}
					
					i = 1000
					while (i-- && $(this).height() > max_el.height()+20) {
						$(this).height($(this).height() - 1);
						$(this).parent().height($(this).parent().height() - 1);
					}
					var h = 0; $(this).parent().find('.mag_lay-col').each(function() { h += $(this).height() });
					$(this).parent().height(h);
					
				});
				$('#mag_lay .border').each(function() {
					$(this).height($(this).parent().height()-20);
				})
				$('#mag_lay .media').each(function() {
					if ($(this).height() > $(this).parents('.border').height()) {
						$(this).height($(this).parents('.border').height());
					}
				})
			});

			function set_iframe() {
				//set the iframe
				$('.mag_lay-content').click(function() { 
					var link = $(this).find('a.link');
					var iframe = $('#magazine-layout-iframe');
					if (!iframe.length) {
					    iframe = $(document.createElement('iframe'));
						iframe.attr('id', 'magazine-layout-iframe');
						iframe.css({width:'100%',height:'100%'});
					}
					iframe.attr('src', link.html()); 
					
					var div = $(iframe).parent();
					if (!div.length) {
						div = $(document.createElement('div'));
						div.css({overflow:'hidden',margin:0,padding:0}).append(iframe);
						$('body').append(div);
						var wind_w = $(window).width();
						var wind_h = $(window).height()-100;
					    div.dialog({
								modal: true,
								title: link.html(),
								draggable: false,
								resizable: false,
								width: wind_w,
								height: wind_h,
								position: ['center', 'top'],
								autoOpen: true,
						        show: 'fadeIn',
						        open: function(event, ui) {
						        	$('.ui-widget-overlay').click(function() {
						        		$(this).prev().find('.ui-dialog-content').dialog('close');
						        	});
						        }
						});
					} else {
						div.dialog('open');
					}
					return false;
				});
			}
			
			function init_post(el) {
                var texts = el.find('p:not(.info)').each(function() {
	                 var j = 1000;
	                 var orig_text = $(this).parent().find('.original-text');
	                 if (! orig_text.length) {
	                 	$(this).parent().append($(document.createElement('p')).hide().html($(this).html()).addClass('original-text'));
	                 } else {
	                 	$(this).html(orig_text.html());
	                 }
	                 while($(this).html().length > 0 && $(this).parent().height() > $(this).parent().parent().height()-10 && j--) {
		                  $(this).html($(this).html().substr(0,$(this).html().length-5)+'…');
		             }
                });
                init_title(el.find('h3'));
			}
            
            function init_title(el) {
            	if (el.parents('.mag_lay-content').height() < $(el).parents('.mag_lay-row').height()) {
            		$(el).css({'max-height':'3em'});
            	} else {
            		$(el).css({'max-height':'1em'});
            	}
            	
            	if (el[0]) el = el[0];
            	if (el.title) el.innerHTML = el.title;
                if (el.clientWidth < el.scrollWidth || el.clientHeight < el.scrollHeight-2) {
                    if (el.clientWidth < el.scrollWidth || el.clientHeight < el.scrollHeight-2) {           
                    	el.innerHTML = el.innerHTML.substr(0, el.innerHTML.length-2)+' <span>…</span>';    
                    	var i = 1000;
	                    while((el.clientWidth < el.scrollWidth || el.clientHeight < el.scrollHeight-2) && i--) {
	                        el.innerHTML = el.innerHTML.substr(0, el.innerHTML.length-18)+' <span>…</span>';
	                    }
                    }                  
                    el.className = 'expandable';
                    $(el).mouseover(function() {
	                	h3div.show();
	                    h3div.css({'left': $(el).position().left,'top':$(el).position().top,height: $(el).height()});	
	                    h3div.html($(el).attr('title'));
	                    console.info(h3div);
	                });
                } else {
                	
                }
            }
            
			function init_mag_lay() {
				
				var pages = $('#mag_lay .page');
                var pg_height = $(window).height() - 200;
                pages.css('height',pg_height+'px');
                
				var containers = $('#mag_lay .mag_lay-row');
				for (i=0;i<containers.length;i++) {
					if ($(containers[i]).height() > $(containers[i]).width()) {
						$(containers[i]).addClass('height-layout');
					} else {
						$(containers[i]).addClass('width-layout');
					}
				}
				
				if ($.browser.msie) {
					$('#mag_lay img').css({width:'auto'});
					var els = $('#mag_lay div');
					for (i=0;i<els.length;i++) {
						if (els[i].style.height == '100%') {
							els[i].style.height = $(els[i]).parent().height()+'px';
						}
					}
				}
			}	
	
	

			var last_timestamp = '<?=$most_recent_timestamp?>';
			$(document).scroll(function() {
				$('#mag_lay').css({overflow:'hidden'});
				if ($(window).height()+$(this).scrollTop() >= $(this).height() && !loading) {
					console.info('load page');
					loading = true;
					$.post('/get_more_newsfeed',{
						ajax: 1,
						last_timestamp: last_timestamp,
						type:'interests_feed',
						view: 'page',
						view_type:'page_view_magazine',
						ci_csrf_token: $("form input[name='ci_csrf_token']").val()
					},function(data) {
						last_timestamp = data.last_timestamp;
						loading = false;
						var content = $(document.createElement('div')).html(data.items);
						$('#mag_lay').append(content.find('#mag_lay .page'));
			
					},'json');
				}
			})
</script> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/home/newsfeed_layout/magazine.php ) -->' . "\n";
} ?>
