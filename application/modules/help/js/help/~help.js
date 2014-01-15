/* 
 * Logic to initialize the help toolbar at top of header
 * and to highlight all elements that have help attribute when clicked
 *
 */
define(["common/utils","plugins/qtip/jquery.qtip.min", 'jquery'],function(utils){
	var self = this;
	utils.loadCss('/css/jquery.qtip.css');

	var groups = new Array();

	$(function(){
		var help_active = false;
        var bar  = $("#help_bar");
		var message = $("#help_bar_message");


		/**
		Highlight all objects on webpage that have help attribute, and add tooltips to those
		 the following 2 attributes need to be present: title and help.
		*/
		if (typeof $(this).qtip != 'function') {
			console.info('qtip not defined'); // IE8
			return;
		}
		$("[title][help]").each(function(){
			//Make sure that help is not reproduced for same elements on the same page (if title is the same)
			if ($(this).attr('helpgroup') == undefined
				|| $(this).attr('helpgroup') != undefined && $.inArray($(this).attr('helpgroup'), groups) < 0) {
		        
		        $(this).qtip({
		        
		        	content: {
		        	/*
		                title: {
						   	//text: function(){return $(this).attr('title') },
						   	text: '',
							button:true
						},
					*/
					   text: function(api) {
				         return $(this).attr('help');
				      },
				      button: true
		        	},
					hide: 'unfocus',
					position: {
						at: 'right center',
						my: 'left center',
						viewport: $(window)
		           	},
					show: {
			            solo: true,
			            ready: true,
						delay: 300
		         	},
					style: {
				     	classes: 'ui-tooltip-maroon ui-tooltip-shadow'
				    },
					 events: {
					      hide: function(event, api) {
					      		console.log('qtip hide');
					    	  //self.onhide(event, api);
					      },
					      show: function(event, api) {
					         if(! help_active) {
					        	try { //IE8 fix
						            event.preventDefault();
					        	} catch (e) {
					        		
					        	}
					            //event.preventDefault();
					            //event.returnValue = false;
					         }
					      }
					 }
		
		        });
		        if ($(this).attr('helpgroup') != undefined) {
		        	groups.push($(this).attr('helpgroup'));
		        	$(this).addClass('help_showtip');
		        }
	        }
		});
		console.log(groups);
       
       $(document).ready(function(){
			$('#help_container').show();
       });
        
        /**
        Handles the activation of help. Cick on a ? icon in top right of webpage to activate help mode.
        that button becomes a 'Done' button
        */
		$(document).on('click', "#btn_help", function(){
			
            if($(this).val()=="?" || !$(this).hasClass('help_active')){
            	$('#help_bar').css('width','100%');
				console.log('help activating');
				$('body').append('<div id="help_overlay"></div>');
            	bar.addClass("help_activated");
				$(this).val('Done');
				$(this).addClass('wide');
				message.fadeIn(1000);
				help_active = true;
				$("[help]").addClass('help');
				console.log(groups);
				$('.help').each(function() {
					if ($(this).attr('helpgroup') == undefined
					|| ($(this).attr('helpgroup') != undefined && $(this).hasClass('help_showtip'))) {
						
						var old_z = $(this).css('z-index');
						if (!old_z) old_z = 0;
						$(this).attr('zindex',old_z).css('z-index','7');
						var old_bg = $(this).css('background');
						if (!old_bg) old_bg = $(this).css('background-color');
						$(this).attr('oldbg',old_bg).css('background','white');
						if ($(this).attr('pos_my')) {
							var tmp_my = $(this).attr('pos_my');
							var tmp_at = $(this).attr('pos_at');
							$(this).qtip('option', {
								'position.at': tmp_at,
								'position.my': tmp_my
							});
						}
					}
				});
				$(this).addClass('help_active');
				$('.help').qtip('toggle');
				//$(document).ready();
            }
			else{
				console.log('closing help');
			   bar.removeClass("help_activated");
			   	$(this).val('?');
				$(this).removeClass('wide');
				message.hide();
				$('.help').each(function() {
					var old_z = $(this).attr('zindex');
					$(this).removeAttr('zindex').css('z-index',old_z);
					var old_bg = $(this).attr('oldbg');
					$(this).removeAttr('oldbg').css('background',old_bg);
				});
				$("[help]").removeClass('help');
				$(this).removeClass('help_active');
			   	help_active = false;
			   	$('#help_overlay').hide().remove();
			   	$('#help_bar').css('width','auto');
			   	//self.onhide();
			}
			return false;

		});
        
        
	});
	/*
	self.onhide = function() {
		console.info('hide');
		$("#btn_help").click();
	};
	*/
	return this;
});