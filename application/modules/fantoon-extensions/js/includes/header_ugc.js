/**
 * JS logic for the header, will load for all pages that have the fandrop header.
 */
define(["common/utils", "common/custom_title", "jquery"], function(u) {

	$(document).on('click', '#searchButton', function() {
		var form = $(this).closest('form');
		var val = form.find('.token-input-list-search input').val();
		val = val.replace(/(<!--|-->)/gi, '');
		form.find("input[name=q]").val(val);
	});
	$('#search form').bind('submit', function() {
		if (!$(this).find('input[name=q]').val()) return false;
	});
	

	$(document).on('ft_dropdown_open','#hdr_notifications', function() {
		var unread = [];
		$('#notifications .unread_notification').each(function() {
			unread.push($(this).attr('data-id'));
		});

		// should mark_as_read for unread items that sent to server to mark
		//var txt = Math.max(parseInt($(this).text())-12,0);
		var txt = Math.max(parseInt($(this).find('.buttonText').text())-unread.length,0);
		$(this).find('.buttonText').text(txt);
		if (txt == 0) $(this).addClass('empty_count');
		
		//Mixpanel tracking
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			//mixpanel.people.identify(user);
			mixpanel.track('Notification Menu Open', {'user':user});
			//mixpanel.track('Home Select Sort', {'user':user});
		}
		
		$.post('/mark_as_read', {'ids': unread}, function(data) { }, 'json');
	});
	
	/**
	 * Logged out user
	 */
	if (!php || !php.userId) {
		var ajaxButton = ' [rel=ajaxButton]';
		var redropBtn = " [href='#collect_popup']";
		var ajaxForm = ' [rel=ajaxForm]:not(.public)';
		var disabled_btns = [ajaxButton, redropBtn].join(', '); 
		
		function disable_btns(e) {
			console.info('DISABLE BTN');
			var $this = $( this );
			$('[href="#signup-popup"]').trigger('click');
			var href = $this.attr('href') ? $this.attr('href') : $this.attr('data-url');
			if (href && href.substr(0,1) != '#' ) {
				$('#login-popup, #signup-popup').find('[name="redirect_url"]').val(href);
			} else if($this.closest('[data-newsfeed_id]').length) {
				$('#login-popup, #signup-popup').find('[name="redirect_url"]').val('/drop/'+$this.closest('[data-newsfeed_id]').attr('data-url'));
			} else if ($this.parent().closest('[data-url]').length) {
				$('#login-popup, #signup-popup').find('[name="redirect_url"]').val($this.parent().closest('[data-url]').attr('data-url'));
			} else if ($this.closest('[data-url]').length) {
				$('#login-popup, #signup-popup').find('[name="redirect_url"]').val($this.closest('[data-url]').attr('data-url'));
			} else{
				
			}
			e.stopPropagation();
			return false;
		}

		function disable_forms(e) {

			console.info('DISABLE FORM');
			var $this = $( this );

			if ( $(e.target).attr("data-required_login") && $(e.target).attr("data-required_login") == "false" )  {
				return true;
			}

			// comments trigger if user is not logged in
			if ( $(e.target).hasClass("comments_form") )  {
			   $(e.target).trigger("keep_comment");
			}
			$('[href="#signup-popup"]').trigger('click');
			
			if($this.closest('[data-newsfeed_id]').length){
				$('#login-popup, #signup-popup').find('[name="redirect_url"]').val('/drop/'+$this.closest('[data-newsfeed_id]').attr('data-url'));
			} else if ($this.parent().closest('[data-url]').length) {
				$('#login-popup, #signup-popup').find('[name="redirect_url"]').val($this.parent().closest('[data-url]').attr('data-url'));
			} else if ($(this).find('[name=newsfeed_id]').length) {
				$('#login-popup, #signup-popup').find('[name="redirect_url"]').val('/drop/'+$(this).find('[name=newsfeed_id]').val());
			} else{
				
			}
			
			return false;
		}
		
		$(disabled_btns).unbind('click').bind('click', disable_btns);
		$(ajaxForm).unbind('submit').bind('submit', disable_forms);

		$(document).ready(function() {
			$(disabled_btns).unbind('click').bind('click', disable_btns);
			$(ajaxForm).unbind('submit').bind('submit', disable_forms);
		});

		$(document).on('update', function() {
			$(disabled_btns).unbind('click').bind('click', disable_btns);
			$(ajaxForm).unbind('submit').bind('submit', disable_forms);
		});

	}

	//Default jquery extends
	$.extend({
		post: function(url, data, callback, type) {
			if (typeof data == 'object') {
				if (!data[php.csrf.name]) data[php.csrf.name] = php.csrf.hash;
			} else if (data.indexOf('php.csrf.name') == -1) {
				data += '&'+php.csrf.name+'='+php.csrf.hash;
			}
			$.ajax(url, {
				'type': 'post',
				'dataType': type,
				'data': data,
				'success': callback
			});
		}
	});

	//MIXPANEL SETUP
	if (typeof(mixpanel) !== 'undefined') {
		var user = php.userId ? php.userId : 0;
		mixpanel.people.identify(user);
		if (user) {
			mixpanel.people.set({
			"$first_name": php.first_name,
			"$last_name": php.last_name,
			"$email": php.email,
			"$last_,login": new Date(),
			"server": document.location.hostname
			});
		}
	}

});
