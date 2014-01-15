(function($){

	$.fn.validate = function(){

		return this.each(function(){

			var _this = this;

			var error_tpl = '<span class="error_contents"></span>';
			var valid_tpl = '<span class="valid_contents"></span>';
			var validation_error = false;
			
			var lang = {
					error : {
						email : 'You have to provide a valid email',
						confirm :'Passwords don\'t match.',
						required : 'Password can\'t be blank.',
						url : '',
						hashtag : '',
						hashtaguniq : '',
						minlength : 'Field must be at least 6 characters.',
						username : 'It is not valid username',
						contest : '',
						unique : '',
						uniqemail : '',
						uniqcollection : '',
						password : 'Your password is too obvious.',
						specialchars : ''
					},
					password : {
						weak : 'Your password is okay!',
						ok : 'Your password could be more secure.',
						perfect : 'Your password is perfect!'
					}
				}

			function hide_errors($input)	{
				$input.closest('.form_row').find('.error, .valid').hide();
			}

			function error_show($input,$msg)	{
				var $obj = $input.closest('.form_row').find('.error');
				if (arguments.length == 2)	{
					$obj.html($msg).show();
				}
				if (arguments.length == 3 )	{
					return $obj.length;
				}
			}

			function f_error_show($msg)	{
				var $obj = $input.closest('form').find('.error:first');
				if (arguments.length == 2)	{
					$obj.html($msg).show();
				}		
			}

			function error_hide($input)	{
				$input.closest('.form_row').find('.error').hide();
			}

			function get_meta($input,key)	{
				if ($($input).attr(key))	{
					return $input.attr(key);
				}
			return '';
			}

			this.validate_email = function($input, is_live) {

				hide_errors($input);
				
				//email validation doesnt make the field required
				if (!$input.val() || (is_live && is_live != 'submit')) return true;

				var valid = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test($input.val());
				if (!valid) {
					var msg = error_tpl + ( get_meta($input,'data-error-email') );
					if ( get_meta($input, 'data-error') == 'popup') {
						//$(document).trigger('popup_info', [msg, 'append error']);
					} else  {
						error_show($input,msg);
					}			
					return false;
				}
				
				$input.closest('.form_row').find('.valid').show();

			return true;
			}

			this.validate_confirm = function($input,is_live)	{

				var field_name = get_meta( $input,'name').replace("re-","");
				var remote_input = $('input[name=' + field_name + ']');
				
				var valid = ( $($input).val() == remote_input.val() );
				
				if (!valid) {
					error_show( $input, error_tpl+( get_meta($input,'data-error-confirm') || lang.error.confirm ) );
					return false;
				}

			return true;
			}

			this.validate_url = function($input, is_live) {
				var val = $input.val();
				hide_errors($input);
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
					var msg = error_tpl+( get_meta( $input, 'data-error-url') || lang.error.url);
					if ( get_meta($input, 'data-error') == 'popup') {
						$(document).trigger('popup_info', [msg, 'append error']);
					} else if (error_show($input,'',true).length) {
						error_show($input,msg);
					} else {
						$input.closest('form').find('> .error').show().html(msg);
					}
					return false;
				}

				$input.closest('.form_row').find('.valid').show();

				return true;
			}
			
			this.validate_required = function($input, is_live) {

				hide_errors($input);

				var text_limit_helper = $input.closest('.form_row').find('.textLimit');
				var _maxlength = get_meta( $input, 'maxlength' );

				if (!_maxlength)	{
					_maxlength = get_meta( $input, 'data-maxlength');
				}		
				
				if (is_live && ! $.trim( $input.val() ) ) {

					var msg = error_tpl + ( get_meta($input, 'data-error-required') || lang.error.required);

					if (msg) {
						if ( get_meta( $input, 'data-error') == 'popup') {
							$(document).trigger('popup_info', [msg, 'append error']);
						} else if ( error_show( $input, '', true ) ) {
							error_show( $input, msg );
							// reset counter of textarea
							if (text_limit_helper.length) {
								text_limit_helper.text( _maxlength );
							}
						} else {
							f_error_show($input,msg);
						}
					}
					return false;
				}

				$input.closest('.form_row').find('.valid').show()
				return true;
			}
			
			this.validate_hashtag = function($input, is_live) {

				hide_errors($input);

				// validate if hashtag is exist
				if ((!is_live || is_live == 'submit') && !$input.val().match(/#[a-zA-Z]/)) {
					if (error_show($input,'',true)) {
						error_show( $input, error_tpl + ( get_meta( $input, 'data-error-hashtag') || lang.error.hashtag) );
					} else {
						f_error_show($input,error_tpl + ( get_meta($input, 'data-error-hashtag') || lang.error.hashtag));
					}
					return false;
				}

				// validate if hashtag is unique
				if (!is_live || is_live == 'submit') {
					var hashtag_arr = $input.val().match(/#[^ ]/g);
					if (hashtag_arr.length != $.unique(hashtag_arr).length) {
						if (error_show($input,'',true)) {
							error_show($input, error_tpl + ( get_meta($input, 'data-error-hashtaguniq') || lang.error.hashtaguniq) );
						} else {
							f_error_show($input,error_tpl+ ( get_meta($input, 'data-error-hashtaguniq') || lang.error.hashtaguniq));
						}
					}
					return false;
				}

				$input.closest('.form_row').find('.valid').show()
				return true;
			}
			
			this.max_length_retries = 3;
			this.validate_maxlength = function($input, action) {

				hide_errors($input);
				
				var text_limit_helper = $input.closest('.form_row').find('.textLimit');
				var text_length = $input.val().replace(/\n/g, "\r\n").length;

				var _maxlength = get_meta($input,'maxlength');

				if (text_limit_helper.length) {

					// browser specific bug - chrome doesn't count new lines
					if (navigator.userAgent.indexOf('Firefox') > -1)	{
						var current_length = $input.val().length;
					}	else {
						var current_length = $input.val().length + $input.val().split('\n').length - 1;
					}
					
					if (get_meta($input,'data-maxlength'))	{

						_maxlength = get_meta($input,'data-maxlength');

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

				if ( get_meta( $input, 'data-nokey') && action == true) return false;
				
				if ( action!='submit' && text_length > _maxlength || text_length > _maxlength ) {

						var msg = error_tpl+(lang.char_limit || "Maximum allowed length is: "+_maxlength);
						if (msg && error_show($input,'',true)) {
							error_show($input,msg);
						} else if (msg) {
							$input.closest('form').find('.error').html(msg).show();
						}

					return false;
				}

				$input.closest('.form_row').find('.valid').show()
				return true;
			}
			
			this.validate_minlength = function($input) {
				hide_errors($input);
				if ( $input.val().length < get_meta($input, 'minlength')) {
					var msg = error_tpl + ( get_meta( $input, 'data-error-minlength') || lang.error.minlength);
					
					if ( get_meta( $input, 'data-error') == 'popup') {
						$(document).trigger('popup_info', [msg, 'append error']);
					} else {
						error_show($input,msg);
					}
					
					return false;
				}
				$input.closest('.form_row').find('.valid').show()
				return true;
			}

			// callback func
			this.validate_ajax = function($input, url, error, callback,callback_false) {
				$input.closest('.form_row').find('.loading').show();
				var data = {};
				data[get_meta($input,'name')] = $input.val();
				$.post(url, data, function(result) {
					$input.closest('.form_row').find('.loading').hide();
					if( ! result.status){
						if ( get_meta( $input, 'data-error') != 'popup') {
							$input.closest('.form_row').find('.valid').hide();
							error_show($input,error_tpl+(error || result.error));
						} else	{
							$(document).trigger('popup_info', [ error_tpl+(error || result.error), 'append error']);
						}
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

			this.validate_unique = function($input)	{
				return _this.validate_ajax($input, get_meta($input,'data-unique-url') + $input.val(), get_meta($input, 'data-error-unique') || lang.error.unique);
			}

			this.validate_password = function($input) {
				
				hide_errors($input);
				$input.closest('.form_row').find('.error, .valid').hide();
				
				if ( !get_meta($input,'data-obvious') || get_meta($input,'data-obvious') != 'false' )	{
					var obvious = ["000000","111111","11111111","112233","121212","123123","123456","1234567","12345678","123456789","131313","232323","654321","666666","696969","777777","7777777","8675309","987654","nnnnnn","nop123","nop123","nopqrs","noteglh","npprff","npprff14","npgvba","nyoreg","nyoregb","nyrkvf","nyrwnaqen","nyrwnaqeb","nznaqn","nzngrhe","nzrevpn","naqern","naqerj","natryn","natryf","navzny","nagubal","ncbyyb","nccyrf","nefrany","neguhe","nfqstu","nfqstu","nfuyrl","nffubyr","nhthfg","nhfgva","onqobl","onvyrl","onanan","onearl","onfronyy","ongzna","orngevm","ornire","ornivf","ovtpbpx","ovtqnqql","ovtqvpx","ovtqbt","ovtgvgf","oveqvr","ovgpurf","ovgrzr","oynmre","oybaqr","oybaqrf","oybjwbo","oybjzr","obaq007","obavgn","obaavr","obbobb","obbtre","obbzre","obfgba","oenaqba","oenaql","oenirf","oenmvy","oebapb","oebapbf","ohyyqbt","ohfgre","ohggre","ohggurnq","pnyiva","pnzneb","pnzreba","pnanqn","pncgnva","pneybf","pnegre","pnfcre","puneyrf","puneyvr","purrfr","puryfrn","purfgre","puvpntb","puvpxra","pbpnpbyn","pbssrr","pbyyrtr","pbzcnd","pbzchgre","pbafhzre","pbbxvr","pbbcre","pbeirggr","pbjobl","pbjoblf","pelfgny","phzzvat","phzfubg","qnxbgn","qnyynf","qnavry","qnavryyr","qroovr","qraavf","qvnoyb","qvnzbaq","qbpgbe","qbttvr","qbycuva","qbycuvaf","qbanyq","qentba","qernzf","qevire","rntyr1","rntyrf","rqjneq","rvafgrva","rebgvp","rfgeryyn","rkgerzr","snypba","sraqre","sreenev","sveroveq","svfuvat","sybevqn","sybjre","sylref","sbbgonyy","sberire","serqql","serrqbz","shpxrq","shpxre","shpxvat","shpxzr","shpxlbh","tnaqnys","tngrjnl","tngbef","trzvav","trbetr","tvnagf","tvatre","tvmzbqb","tbyqra","tbysre","tbeqba","tertbel","thvgne","thaare","unzzre","unaanu","uneqpber","uneyrl","urngure","uryczr","uragnv","ubpxrl","ubbgref","ubearl","ubgqbt","uhagre","uhagvat","vprzna","vybirlbh","vagrearg","vjnagh","wnpxvr","wnpxfba","wnthne","wnfzvar","wnfcre","wraavsre","wrerzl","wrffvpn","wbuaal","wbuafba","wbeqna","wbfrcu","wbfuhn","whavbe","whfgva","xvyyre","xavtug","ynqvrf","ynxref","ynhera","yrngure","yrtraq","yrgzrva","yrgzrva","yvggyr","ybaqba","ybiref","znqqbt","znqvfba","znttvr","zntahz","znevar","znevcbfn","zneyobeb","znegva","zneiva","znfgre","zngevk","znggurj","znirevpx","znkjryy","zryvffn","zrzore","zreprqrf","zreyva","zvpunry","zvpuryyr","zvpxrl","zvqavtug","zvyyre","zvfgerff","zbavpn","zbaxrl","zbaxrl","zbafgre","zbetna","zbgure","zbhagnva","zhssva","zhecul","zhfgnat","anxrq","anfpne","anguna","anhtugl","app1701","arjlbex","avpubynf","avpbyr","avccyr","avccyrf","byvire","benatr","cnpxref","cnagure","cnagvrf","cnexre","cnffjbeq","cnffjbeq","cnffjbeq1","cnffjbeq12","cnffjbeq123","cngevpx","crnpurf","crnahg","crccre","cunagbz","cubravk","cynlre","cyrnfr","cbbxvr","cbefpur","cevapr","cevaprff","cevingr","checyr","chffvrf","dnmjfk","djregl","djreglhv","enoovg","enpury","enpvat","envqref","envaobj","enatre","enatref","erorppn","erqfxvaf","erqfbk","erqjvatf","evpuneq","eboreg","eboregb","ebpxrg","ebfrohq","ehaare","ehfu2112","ehffvn","fnznagun","fnzzl","fnzfba","fnaqen","fnghea","fpbbol","fpbbgre","fpbecvb","fpbecvba","fronfgvna","frperg","frkfrk","funqbj","funaaba","funirq","fvreen","fvyire","fxvccl","fynlre","fzbxrl","fabbcl","fbppre","fbcuvr","fcnaxl","fcnexl","fcvqre","fdhveg","fevavinf","fgnegerx","fgnejnef","fgrryref","fgrira","fgvpxl","fghcvq","fhpprff","fhpxvg","fhzzre","fhafuvar","fhcrezna","fhesre","fjvzzvat","flqarl","grdhvreb","gnlybe","graavf","grerfn","grfgre","grfgvat","gurzna","gubznf","guhaqre","guk1138","gvssnal","gvtref","gvttre","gbzpng","gbctha","gblbgn","genivf","gebhoyr","gehfgab1","ghpxre","ghegyr","gjvggre","havgrq","intvan","ivpgbe","ivpgbevn","ivxvat","ibbqbb","iblntre","jnygre","jneevbe","jrypbzr","jungrire","jvyyvnz","jvyyvr","jvyfba","jvaare","jvafgba","jvagre","jvmneq","knivre","kkkkkk","kkkkkkkk","lnznun","lnaxrr","lnaxrrf","lryybj","mkpioa","mkpioaz","mmmmmm"];
					for (var i in obvious) {
						if ($input.val() == obvious[i]) {
							error_show( $input, error_tpl + ( get_meta($input, 'data-error-password') || lang.error.password) );
							return false;
						}
					}
				}
				
				if ($input.val().length >= 16 && ( get_meta($input, 'data-password-perfect') || lang.password.perfect) ) {
					$input.closest('.form_row').find('.valid').html(valid_tpl+( get_meta($input, 'data-password-perfect') || lang.password.perfect));
				} else if ($input.val().length >= 9 && ( get_meta($input, 'data-password-ok') || lang.password.ok) ) {
					$input.closest('.form_row').find('.valid').html(valid_tpl+( get_meta($input,'data-password-ok') || lang.password.ok));
				} else if ( get_meta($input,'data-password-weak') || lang.password.weak) {
					$input.closest('.form_row').find('.valid').html(valid_tpl+( get_meta($input,'data-password-weak') || lang.password.weak));
				}
				
				if ($input.closest('.form_row').find('.score').length) {
					var percent = Math.round($input.val().length / 20 * 100);
					$input.closest('.form_row').find('.score span').css('width',percent+'%');
				}

				$input.closest('.form_row').find('.valid').show()
				return true;
			}
			
			this.validate_specialchars = function($input) {
				hide_errors($input);
				if (/[^A-Za-z0-9-_\s\.]/.test($input.val())) {
					error_show($input,error_tpl+(get_meta($input,'data-error-specialchars') || lang.error.specialchars));
					return false;
				}
				$input.closest('.form_row').find('.valid').show()
				return true;
			}

			this._validate = function($input, is_live) {

				is_live = is_live || false; //set to true for keyup events and false on form.submit
				
				var $row = $input.closest('.form_row');
				var $form = $input.closest('form');
				
				$row.find('.field_tip').hide();

				var validations = get_meta($input, 'data-validate').indexOf('|') > -1 ? get_meta($input,'data-validate').split('|') : [get_meta($input,'data-validate')];
				validation_error = false;
				
				for (var i in validations) {
					if (typeof _this['validate_'+validations[i]] == 'function') {
						if (!_this['validate_'+validations[i]]($input, is_live)) {
							validation_error = true;
							break; 
						}
					} else { //required field by default
						if (!_this['validate_required']($input, is_live)) {
							validation_error = true;
							break; 
						}
					}
				}
				
				//all required fields should be validated
				var fields = $form.find('[data-validate]');
				for (var i=0;i<fields.length;i++) {
					if ( get_meta($(fields[i]), 'data-validate').indexOf('required') > -1 ) {
						if ($(fields[i]).closest('.form_row').find('.valid:hidden').length) {
							validation_error = true;
							console.info('Field not valid - 1: ', fields[i]);
						}
					}
				}

				//Other fields shouldnt have error
				if (!validation_error && (!$form.hasClass('error') && is_live == 'submit' ) ) {
					$form.removeClass('error');
					return true;
				} else {
					$form.addClass('error');
					return false;
				}

			}

			/* main section */

			var selector = 'form input[data-validate]:not([type="file"]), form textarea[data-validate]';
			
			$( document ).on( 'keyup', selector, function (e) {

				if ( (e.keyCode || e.which) == '9' ) return false;    // TAB

				var $this = $( this );
				var lastValue = $this.data( '_ft_formValidation_lastValue' );
				var value = $this.val();
				$this.data( '_ft_formValidation_lastValue', value );
				if (lastValue != value ) {
					$(document).trigger('popup_info_clear');
					_this._validate( $this, true );
				}

			});

			$.fn.resetValidation = function () {
				this.find( 'input[data-validate]:not([type="file"]), textarea[data-validate]' ).each( function () {
					$( this ).data( '_ft_formValidation_lastValue', null );
				} );
			};
			
			var selector = 'form [data-validate]';

			$(document).on('change', selector, function() {

				var $this = $( this );
				
				$this.data( '_ft_formValidation_lastValue', $this.val() );
				
				$(document).trigger('popup_info_clear');
				_this._validate( $this, true );
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
			 * Validate on page load - for prepopulated forms
			 */
			$(function() {
				$(document).trigger('popup_info_clear');
				$('form [data-validate]').each(function() {
					if ($(this).val() && $(this).val() != get_meta($(this),'placeholder')) {
						_this._validate($(this));
					}
				});
			});

			$(_this).off('submit prevalidate').on('submit prevalidate', function(e,callback) {

				$(document).trigger('popup_info_clear');

				$(_this).removeClass("error");
				$(_this).find('[data-validate]:visible, .form_row.js-validate-hidden [data-validate], select.tokenInput').each(function() {
					_this._validate( $(this), 'submit' );
				});

				if ($(_this).hasClass('error')) return false;
			
			return false;
			});
		});
	}
})(jQuery);