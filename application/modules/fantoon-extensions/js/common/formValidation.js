/**
 * General formValidation plugin
 * @link http://tools.fantoon.com/dokuwiki/doku.php?id=ui_components#form_validation
 */
define(['jquery','common/popup_info'], function() {
	
	var error_tpl = '<span class="error_contents"></span>';
	var valid_tpl = '<span class="valid_contents"></span>';
	var success_tpl = '<span class="success_contents"></span>';
	var validation_error = false;
	
	window.validate_email = function($input, is_live) {

		$input.closest('.form_row').find('.error, .valid').hide();
		
		//email validation doesnt make the field required
		//it validates on submit: http://dev.fantoon.com:8100/browse/FD-4235
		if (!$input.val() || (is_live && is_live != 'submit')) return true;

		var valid = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test($input.val());
		if (!valid) {
			var msg = error_tpl+( $input.attr('data-error-email') || php.lang.error.email);
			if ($input.attr('data-error') && $input.attr('data-error') == 'popup') {
				console.info(msg);
				$(document).trigger('popup_info', [msg, 'append error']);
			} else  {
				$input.closest('.form_row').find('.error').html(msg).show()
			}			
			return false;
		}
		
		$input.closest('.form_row').find('.valid').show();

	return true;
	}

	window.validate_confirm = function($input,is_live)	{

		var field_name = $($input).attr("name").replace("re-","");
		var remote_input = $('input[name=' + field_name + ']');
		
		var valid = ( $($input).val() == remote_input.val() );
		
		if (!valid) {
			$input.closest('.form_row').find('.error').html(error_tpl+( $input.attr('data-error-confirm') || php.lang.error.confirm)).show()
			return false;
		}

	return true;
	}

	window.validate_url = function($input, is_live) {
		var val = $input.val();
		$input.closest('.form_row').find('.error, .valid').hide();
		if (!val || (is_live && is_live != 'submit')) return true; //url validation doesnt make the field required
		
		if (val.indexOf('www.') == -1 && val.match(/\./g) && val.match(/\./g).length == 1) {
			if (val.indexOf('http://') > -1) {
				val = val.replace('http://','http://www.');
			} else if (val.indexOf('https://') > -1) {
				val = val.replace('https://','https://www.');
			} else {
				val = 'www.'+val;
			}
		}

		if (val.indexOf('http') == -1) {
			val = 'http://'+val;
		}

		$input.val(val);
		
		if (!val.match( new RegExp("(http://|https://|www\\.)[a-zA-Z0-9_\\-]+\\.[a-zA-Z0-9_\\-]+[^ ]*","gi") )) {
			var msg = error_tpl+($input.attr('data-error-url') || php.lang.error.url);
			if ($input.attr('data-error') && $input.attr('data-error') == 'popup') {
				$(document).trigger('popup_info', [msg, 'append error']);
			} else if ($input.closest('.form_row').find('.error').length) {
				$input.closest('.form_row').find('.error').show().html(msg);
			} else {
				$input.closest('form').find('> .error').show().html(msg);
			}
			return false;
		}

		$input.closest('.form_row').find('.valid').show();

		return true;
	}
	
	window.validate_required = function($input, is_live) {
		$input.closest('.form_row').find('.error, .valid').hide();
		// code for reset counter for textarea ( messages for example )
		var text_limit_helper = $input.closest('.form_row').find('.textLimit');
		var _maxlength = $input.attr('maxlength');

		if (!_maxlength)	{
			_maxlength = $input.attr("data-maxlength");
		}		
		console.info('validate required', $input[0], is_live);
		if (is_live && ! $.trim( $input.val() ) ) {
			var msg = error_tpl+($input.attr('data-error-required') || php.lang.error.required);
			if (msg) {
				if ($input.attr('data-error') && $input.attr('data-error') == 'popup') {
					$(document).trigger('popup_info', [msg, 'append error']);
				} else if ($input.closest('.form_row').find('.error').length) {
					$input.closest('.form_row').find('.error').show().html(msg)
					// reset counter of textarea
					if (text_limit_helper.length) {
						text_limit_helper.text( _maxlength );
					}
				} else {
					$input.closest('form').find('.error:first').show().html(msg);
				}
			}
			return false;
		}

		$input.closest('.form_row').find('.valid').show()
		return true;
	}
	
	window.validate_hashtag = function($input, is_live) {
		$input.closest('.form_row').find('.error, .valid').hide();

		// validate if hashtag is exist
		if ((!is_live || is_live == 'submit') && !$input.val().match(/#[a-zA-Z]/)) {
			if ($input.closest('.form_row').find('.error').length) {
				$input.closest('.form_row').find('.error').show().html(error_tpl+($input.attr('data-error-hashtag') || php.lang.error.hashtag));
			} else {
				$input.closest('form').find('.error:first').show().html(error_tpl+($input.attr('data-error-hashtag') || php.lang.error.hashtag));
			}
			return false;
		}

		// validate if hashtag is unique
		// http://dev.fantoon.com:8100/browse/FD-2893
		if (!is_live || is_live == 'submit') {
			var hashtag_arr = $input.val().match(/#[^ ]/g);
			if (hashtag_arr.length != $.unique(hashtag_arr).length) {
				if ($input.closest('.form_row').find('.error').length) {
					$input.closest('.form_row').find('.error').show().html(error_tpl+($input.attr('data-error-hashtaguniq') || php.lang.error.hashtaguniq));
				} else {
					$input.closest('form').find('.error:first').show().html(error_tpl+($input.attr('data-error-hashtaguniq') || php.lang.error.hashtaguniq));
				}
			}
			return false;
		}

		$input.closest('.form_row').find('.valid').show()
		return true;
	}
	
	window.max_length_retries = 3;
	window.validate_maxlength = function($input, action) {

		$input.closest('.form_row').find('.error, .valid').hide();
		
		var text_limit_helper = $input.closest('.form_row').find('.textLimit');
		var text_length = $input.val().replace(/\n/g, "\r\n").length;
		
		// if (text_limit_helper.length && $input.attr('maxlength')) {
		// 	text_limit_helper.text( $input.attr('maxlength') - text_length );
		// }
		// var text_limit_helper = $input.closest('.form_row').find('.textLimit'); 

		var _maxlength = $input.attr('maxlength');

		// http://dev.fantoon.com:8100/browse/FD-3976 !!
		if (text_limit_helper.length) {

			// var maxlength = $input.attr('maxlength');
			// browser specific bug - chrome doesn't count new lines
			if (navigator.userAgent.indexOf('Firefox') > -1)	{
				var current_length = $input.val().length;
			}	else {
				var current_length = $input.val().length + $input.val().split('\n').length - 1;
			}
			
			if ($input.attr('data-maxlength'))	{

				_maxlength = $input.attr('data-maxlength');

				var length = _maxlength - current_length;
				text_limit_helper.text( length );

				if (length < 0)	{
					// disable submit button
					text_limit_helper.addClass("negative");
					$input.css({color:'red'});
					$( 'input[type="submit"]', $input.closest('form')).addClass("disabled_button").removeClass("blue_bg");
				} else {
					// enable submit button
					text_limit_helper.removeClass("negative");
					$input.css({color:'black'});
					$( 'input[type="submit"]', $input.closest('form')).removeClass("disabled_button").addClass("blue_bg");
				}

			}	else {
				// browser specific bug - chrome doesn't count new lines
				if (navigator.userAgent.indexOf('Firefox') > -1)	{
					var current_length = $input.val().length;
				} else {
					var current_length = $input.val().length + $input.val().split('\n').length - 1;
				}

				text_limit_helper.text( _maxlength - current_length );
			}
		}

		// http://dev.fantoon.com:8100/browse/FD-4380
		if ($($input).attr("data-nokey") && action == true) return false;
		
		if (action!='submit' && text_length > _maxlength || text_length > _maxlength) {

			//window.max_length_retries--;
			//if (window.max_length_retries <= 0) {

				var msg = error_tpl+(php.lang.char_limit || "Maximum allowed length is: "+_maxlength);
				if (msg && $input.closest('.form_row').find('.error').length) {
					$input.closest('.form_row').find('.error').html(msg).show()
				} else if (msg) {
					$input.closest('form').find('.error').html(msg).show();
				}

		//	}

			//RR - browser internal validation doesnt work on paste
			// var diff = text_length - $input.val().length;
			// $input.val($input.val().substr(0, _maxlength-diff));
			
			return false;
		}
		//window.max_length_retries = 3;
		$input.closest('.form_row').find('.valid').show()
		return true;
	}
	
	window.validate_minlength = function($input) {
		$input.closest('.form_row').find('.error, .valid').hide();
		if ($input.val().length < $input.attr('minlength')) {
			var msg = error_tpl+($input.attr('data-error-minlength') || php.lang.error.minlength);
			
			if ($input.attr('data-error') && $input.attr('data-error') == 'popup') {
				$(document).trigger('popup_info', [msg, 'append error']);
			} else {
				$input.closest('.form_row').find('.error').html(msg).show();
			}
			
			return false;
		}
		$input.closest('.form_row').find('.valid').show()
		return true;
	}

	// Quang: added callback function. Because validate_ajax often takes time to
	// get response from BE, so that 'valid' needs to reconfirmed by this
	// callback func
	window.validate_ajax = function($input, url, error, callback,callback_false) {
		$input.closest('.form_row').find('.loading').show();
		var data = {};
		data[$input.attr('name')] = $input.val();
		$.post(url, data, function(result) {
			$input.closest('.form_row').find('.loading').hide();
			if( ! result.status){
				$input.closest('.form_row').find('.valid').hide();
				$input.closest('.form_row').find('.error').html(error_tpl+(error || result.error)).show();
				$input.closest('form').addClass('error');
				if (callback_false)	{
					callback_false($input);
				}
			} else {
				$input.closest('.form_row').find('.error').hide();
				$input.closest('.form_row').find('.valid').show();
				if (typeof callback == 'function') {
					callback($input);
				}
			}
		},'json');
		return true;
	}
	
	window.validate_embed = function ($input) {

		if ($input.closest('.form_row').find('.loading').is(':visible')) return false;
		
		$input.closest('.form_row').find('.loading').show();
		
		$.post('/internal_scraper/get_content', {'link': $input.val()}, function(result) {
			$input.closest('.form_row').find('.loading').hide();
			if( ! result.status){
				$input.closest('.form_row').find('.valid').hide();
				$input.closest('.form_row').find('.error').html(error_tpl+(result.error)).show();
			} else if (!result.media) {
				$input.closest('.form_row').find('.valid').hide();
				$input.closest('.form_row').find('.error').html(error_tpl+($input.attr('data-error-embed'))).show();				
			} else {
				$input.closest('.form_row').find('.error').hide();
				$input.closest('.form_row').find('.valid').show();
			}
		},'json');

		return true;
	}
	
	window.validate_username = function($input) {
		return window.validate_ajax($input, '/validate_username', $input.attr('data-error-username') || php.lang.error.username);
	}
	
	window.validate_contest = function($input) {
		return window.validate_ajax($input, '/validate_contest', $input.attr('data-error-contest') || php.lang.error.contest);
	}
	
	window.validate_uniqemail = function($input) {

		if ( $.trim($input.val()) ) {

			var valid = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test($input.val());

			if (valid) {
				return window.validate_ajax($input, '/validate_email', $input.attr('data-error-uniqemail') || php.lang.error.uniqemail);
			}

		}

	}

	window.validate_invitedemail = function($input,is_live) {

		if ( $.trim($input.val()) ) {

			$input.closest('.form_row').find('.error, .valid').hide();

			if (!$input.val() || (is_live && is_live != 'submit')) return true;

			var valid = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test($input.val());

			if (!valid) {
				$input.closest('.form_row').find('.valid,.success').hide();
				$input.closest('.form_row').find('.error').html(error_tpl+( $input.attr('data-error-email') || php.lang.error.email)).show()
				return false;
			}
/*
			$input.closest('.form_row').find('.valid').show();
			return window.validate_ajax($input, '/validate_invited_email', $input.attr('data-error-invitedemail') || php.lang.error.invitedemail, function(){);*/

		return true;
		}

	}
	
	window.validate_uniqcollection = function($input) {
		$input.closest('.form_row').find('.loading').show();
		var err = $input.attr('data-error-uniqcollection') || php.lang.error.uniqcollection;
		var folder_id = $input.closest('form').find('[name=folder_id]');
		
		$.post('/validate_collection/'+(folder_id.length ? folder_id.val() : ''), {'folder_name': $input.val()}, function(result) {
			$input.closest('.form_row').find('.loading').hide();
			if(result.status){
				$input.closest('.form_row').find('.valid').hide();
				$input.closest('.form_row').find('.error').html(error_tpl+err).show();
			} else {
				$input.closest('.form_row').find('.error').hide();
				$input.closest('.form_row').find('.valid').show();
			}
		},'json');
		return true;
	}
		
	window.validate_password = function($input) {
		
		$input.closest('.form_row').find('.error, .valid').hide();
		
		if ( !$input.attr("data-obvious") || $input.attr("data-obvious") != 'false' )	{
			var obvious = ["000000","111111","11111111","112233","121212","123123","123456","1234567","12345678","123456789","131313","232323","654321","666666","696969","777777","7777777","8675309","987654","nnnnnn","nop123","nop123","nopqrs","noteglh","npprff","npprff14","npgvba","nyoreg","nyoregb","nyrkvf","nyrwnaqen","nyrwnaqeb","nznaqn","nzngrhe","nzrevpn","naqern","naqerj","natryn","natryf","navzny","nagubal","ncbyyb","nccyrf","nefrany","neguhe","nfqstu","nfqstu","nfuyrl","nffubyr","nhthfg","nhfgva","onqobl","onvyrl","onanan","onearl","onfronyy","ongzna","orngevm","ornire","ornivf","ovtpbpx","ovtqnqql","ovtqvpx","ovtqbt","ovtgvgf","oveqvr","ovgpurf","ovgrzr","oynmre","oybaqr","oybaqrf","oybjwbo","oybjzr","obaq007","obavgn","obaavr","obbobb","obbtre","obbzre","obfgba","oenaqba","oenaql","oenirf","oenmvy","oebapb","oebapbf","ohyyqbt","ohfgre","ohggre","ohggurnq","pnyiva","pnzneb","pnzreba","pnanqn","pncgnva","pneybf","pnegre","pnfcre","puneyrf","puneyvr","purrfr","puryfrn","purfgre","puvpntb","puvpxra","pbpnpbyn","pbssrr","pbyyrtr","pbzcnd","pbzchgre","pbafhzre","pbbxvr","pbbcre","pbeirggr","pbjobl","pbjoblf","pelfgny","phzzvat","phzfubg","qnxbgn","qnyynf","qnavry","qnavryyr","qroovr","qraavf","qvnoyb","qvnzbaq","qbpgbe","qbttvr","qbycuva","qbycuvaf","qbanyq","qentba","qernzf","qevire","rntyr1","rntyrf","rqjneq","rvafgrva","rebgvp","rfgeryyn","rkgerzr","snypba","sraqre","sreenev","sveroveq","svfuvat","sybevqn","sybjre","sylref","sbbgonyy","sberire","serqql","serrqbz","shpxrq","shpxre","shpxvat","shpxzr","shpxlbh","tnaqnys","tngrjnl","tngbef","trzvav","trbetr","tvnagf","tvatre","tvmzbqb","tbyqra","tbysre","tbeqba","tertbel","thvgne","thaare","unzzre","unaanu","uneqpber","uneyrl","urngure","uryczr","uragnv","ubpxrl","ubbgref","ubearl","ubgqbt","uhagre","uhagvat","vprzna","vybirlbh","vagrearg","vjnagh","wnpxvr","wnpxfba","wnthne","wnfzvar","wnfcre","wraavsre","wrerzl","wrffvpn","wbuaal","wbuafba","wbeqna","wbfrcu","wbfuhn","whavbe","whfgva","xvyyre","xavtug","ynqvrf","ynxref","ynhera","yrngure","yrtraq","yrgzrva","yrgzrva","yvggyr","ybaqba","ybiref","znqqbt","znqvfba","znttvr","zntahz","znevar","znevcbfn","zneyobeb","znegva","zneiva","znfgre","zngevk","znggurj","znirevpx","znkjryy","zryvffn","zrzore","zreprqrf","zreyva","zvpunry","zvpuryyr","zvpxrl","zvqavtug","zvyyre","zvfgerff","zbavpn","zbaxrl","zbaxrl","zbafgre","zbetna","zbgure","zbhagnva","zhssva","zhecul","zhfgnat","anxrq","anfpne","anguna","anhtugl","app1701","arjlbex","avpubynf","avpbyr","avccyr","avccyrf","byvire","benatr","cnpxref","cnagure","cnagvrf","cnexre","cnffjbeq","cnffjbeq","cnffjbeq1","cnffjbeq12","cnffjbeq123","cngevpx","crnpurf","crnahg","crccre","cunagbz","cubravk","cynlre","cyrnfr","cbbxvr","cbefpur","cevapr","cevaprff","cevingr","checyr","chffvrf","dnmjfk","djregl","djreglhv","enoovg","enpury","enpvat","envqref","envaobj","enatre","enatref","erorppn","erqfxvaf","erqfbk","erqjvatf","evpuneq","eboreg","eboregb","ebpxrg","ebfrohq","ehaare","ehfu2112","ehffvn","fnznagun","fnzzl","fnzfba","fnaqen","fnghea","fpbbol","fpbbgre","fpbecvb","fpbecvba","fronfgvna","frperg","frkfrk","funqbj","funaaba","funirq","fvreen","fvyire","fxvccl","fynlre","fzbxrl","fabbcl","fbppre","fbcuvr","fcnaxl","fcnexl","fcvqre","fdhveg","fevavinf","fgnegerx","fgnejnef","fgrryref","fgrira","fgvpxl","fghcvq","fhpprff","fhpxvg","fhzzre","fhafuvar","fhcrezna","fhesre","fjvzzvat","flqarl","grdhvreb","gnlybe","graavf","grerfn","grfgre","grfgvat","gurzna","gubznf","guhaqre","guk1138","gvssnal","gvtref","gvttre","gbzpng","gbctha","gblbgn","genivf","gebhoyr","gehfgab1","ghpxre","ghegyr","gjvggre","havgrq","intvan","ivpgbe","ivpgbevn","ivxvat","ibbqbb","iblntre","jnygre","jneevbe","jrypbzr","jungrire","jvyyvnz","jvyyvr","jvyfba","jvaare","jvafgba","jvagre","jvmneq","knivre","kkkkkk","kkkkkkkk","lnznun","lnaxrr","lnaxrrf","lryybj","mkpioa","mkpioaz","mmmmmm"];
			for (var i in obvious) {
				if ($input.val() == obvious[i]) {
					$input.closest('.form_row').find('.error').show().html(error_tpl+($input.attr('data-error-password') || php.lang.error.password));
					return false;
				}
			}
		}
		
		if ($input.val().length >= 16 && ($input.attr('data-password-perfect') || php.lang.password.perfect) ) {
			$input.closest('.form_row').find('.valid').html(valid_tpl+($input.attr('data-password-perfect') || php.lang.password.perfect));
		} else if ($input.val().length >= 9 && ($input.attr('data-password-ok') || php.lang.password.ok) ) {
			$input.closest('.form_row').find('.valid').html(valid_tpl+($input.attr('data-password-ok') || php.lang.password.ok));
		} else if ($input.attr('data-password-weak') || php.lang.password.weak) {
			$input.closest('.form_row').find('.valid').html(valid_tpl+($input.attr('data-password-weak') || php.lang.password.weak));
		}
		
		if ($input.closest('.form_row').find('.score').length) {
			var percent = Math.round($input.val().length / 20 * 100);
			$input.closest('.form_row').find('.score span b').css('width',percent+'%');
		}
		$input.closest('.form_row').find('.valid').show()
		return true;
	}
	
	window.validate_specialchars = function($input) {
		$input.closest('.form_row').find('.error, .valid').hide();
		if (/[^A-Za-z0-9-_\s\.]/.test($input.val())) {
			$input.closest('.form_row').find('.error').html(error_tpl+($input.attr('data-error-specialchars') || php.lang.error.specialchars)).show();
			return false;
		}
		$input.closest('.form_row').find('.valid').show()
		return true;
	}

	window.validate = function($input, is_live) {
		is_live = is_live || false; //set to true for keyup events and false on form.submit
		console.log('validate', $input[0], is_live);
		var $row = $input.closest('.form_row');
		var $form = $input.closest('form');
		
		// RR - this code is moved to every non-ajax validation func. For ajax funcs valid or .error should
		// appear after the ajax request
		//if ($row.find('.error').length) {
		//	$row.find('.error, .valid').hide();
		//} else {
		//	$form.find('.error').hide();
		//}

		$row.find('.field_tip').hide();

		var validations = $input.attr('data-validate').indexOf('|') > -1 ? $input.attr('data-validate').split('|') : [$input.attr('data-validate')];
		validation_error = false;
		
		for (var i in validations) {
			if (typeof window['validate_'+validations[i]] == 'function') {
				//console.log('validate_'+validations[i]);
				if (!window['validate_'+validations[i]]($input, is_live)) {
					//console.log('not valid');
					validation_error = true;
					break; 
				}
			} else { //required field by default
				console.warn("validate_"+validations[i]+" doesnt exists");
				if (!window['validate_required']($input, is_live)) {
					validation_error = true;
					break; 
				}
			}
		}
		
		//all required fields should be validated
		var fields = $form.find('[data-validate]');
		for (var i=0;i<fields.length;i++) {
			if ($(fields[i]).attr('data-validate') && $(fields[i]).attr('data-validate').indexOf('required') > -1) {
				if ($(fields[i]).closest('.form_row').find('.valid:hidden').length) {
					validation_error = true;
					console.info('Field not valid - 1: ', fields[i]);
				}
			}
		}

		console.warn('validation_error', validation_error, $form.find('.error:visible').length);

		//Other fields shouldnt have error
		if (!validation_error && (!$form.hasClass('error') && is_live == 'submit' ) ) {
			$form.removeClass('error');
			return true;
		} else {
			console.info('Field not valid: - 2', $form.find('.error:visible')[0]);
			$form.addClass('error');
			return false;
		}
	
	}
	
	//BP: #FD-2961
	//Quang(FD-4245): if 'keyup' of TAB key, $this becomes current object (not the
	//objective that we want to validate) -> skip validate
	
	var selector = 'form input[data-validate]:not([type="file"]), form textarea[data-validate]';
	
	$( document ).on( 'keyup', selector, function (e) {

		// if ($(this).attr("data-nokey")) return false;

		if ( (e.keyCode || e.which) == '9' ) return false;    // TAB

		var $this = $( this );
		var lastValue = $this.data( '_ft_formValidation_lastValue' );
		var value = $this.val();
		$this.data( '_ft_formValidation_lastValue', value );
		if (lastValue != value ) {
			$(document).trigger('popup_info_clear');
			validate( $this, true );
		}

	});

	$.fn.resetValidation = function () {
		this.find( 'input[data-validate]:not([type="file"]), textarea[data-validate]' ).each( function () {
			$( this ).data( '_ft_formValidation_lastValue', null );
		} );
	};
	
	var selector = 'form [data-validate]';

	//end of #FD-2961
	$(document).on('change', selector, function() {
		var $this = $( this );
		//BP: #FD-2961
		$this.data( '_ft_formValidation_lastValue', $this.val() );
		//end of #FD-2961
		$(document).trigger('popup_info_clear');
		validate( $this, true );
	}).on('focus', selector, function() {
		var $row = $(this).closest('.form_row');
		if (!$row.find('.valid:visible, .error:visible, .success:visible').length) {
			$row.find('.field_tip').show();
		}
	}).on('blur', selector, function() {
		var $row = $(this).closest('.form_row');
		$row.find('.field_tip').hide();
	});
	
	/**
	 * VAlidate on page load - for prepopulated forms
	 */
	$(function() {
		$(document).trigger('popup_info_clear');
		$('form [data-validate]').each(function() {
			if ($(this).val() && $(this).val() != $(this).attr('placeholder')) {
				validate($(this));
			}
		});
	});
	
	/**
	 * Trigger the validations on form submit. To validate the form if the user doesnt
	 * type any info (keyup, change not triggered)
	 */
	$(document).on('submit prevalidate', 'form', function(e, callback){

		console.info('Validate on submit');
		$(document).trigger('popup_info_clear');

		$(this).removeClass("error");

		$(this).find('[data-validate]:visible, .form_row.js-validate-hidden [data-validate], select.tokenInput').each(function() {
			validate( $(this), 'submit' );
		});

		var _this = this;

		setTimeout(function(){
			if ($(_this).hasClass('error')) return false;			
		},300);

	});

});
