/**
 * JS logic for the header, will load for all pages that have the fandrop header.
 */
define(["common/utils", "jquery"], function(u) {

    $(document).on('click','#searchButton', function() {
        var form = $(this).closest('form');
        var val = form.find('.token-input-list-search input').val();
        val = val.replace(/(<!--|-->)/gi, '');
        form.find("input[name=q]").val(val);
    });

    $(document).on('submit','#search form', function() {
        if (!$(this).find('input[name=q]').val()) return false;
    });
    
    $(document).on('click', '#dropit-message .close_btn', function() {
    	$.get('/info_dialog_opened');
    	$('#dropit-message').hide();
    	return false;
    });
    
    $(document).on('ft_dropdown_open', '#hdr_notifications', function() {
		var unread = [];
		$('#notifications .unread_notification').each(function() {
			unread.push($(this).attr('data-id'));
		});

		// should mark_as_read for unread items that sent to server to mark
		//var txt = Math.max(parseInt($(this).text())-12,0);
		var txt = Math.max(parseInt($(this).text())-unread.length,0);
		$(this).text(txt);
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
    		var href = $this.attr('href') ? $this.attr('href') : $this.attr('data-url');
    		if (href && href.substr(0,1) != '#' ) {
    			window.location.href = '/signup?redirect_url='+href;
    		} else if($this.closest('[data-newsfeed_id]').length){
    			window.location.href = '/signup?redirect_url='+php.baseUrl+'drop/'+$this.closest('[data-newsfeed_id]').attr('data-url');
    		} else if ($this.parent().closest('[data-url]').length) {
    			window.location.href = '/signup?redirect_url='+$this.parent().closest('[data-url]').attr('data-url');
    		} else if ($this.closest('[data-url]').length) {
    			window.location.href = '/signup?redirect_url='+$this.closest('[data-url]').attr('data-url');
    		} else{
    			window.location.href = '/signup';
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

    		if($this.closest('[data-newsfeed_id]').length){
    			window.location.href = '/signup?redirect_url='+php.baseUrl+'drop/'+$this.closest('[data-newsfeed_id]').attr('data-url');
    		} else if ($this.parent().closest('[data-url]').length) {
    			window.location.href = '/signup?redirect_url='+$this.parent().closest('[data-url]').attr('data-url');
    		} else if ($(this).find('[name=newsfeed_id]').length) {
    			window.location.href = '/signup?redirect_url='+php.baseUrl+'drop/'+$(this).find('[name=newsfeed_id]').val();
    		} else{
    			window.location.href = '/signup';
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
		})
	}   
        
    
    //Global events
    $(document).ajaxComplete(function(e, xhr, opts) {

        /* Sylwia - FD-788, FD-855, FD-960, FD-1029 - Get More News/No More News issues - begin */
        /* the solution is not language-dependent */
        if( $('.no_more_news').length > 0 ) {
            if( $('.no_more_news').is(":visible") ) {
                if( $('.more_news_link').length > 0) {
                    $('.more_news_link').text( $('.no_more_news').text() );
                    $('.no_more_news').hide();
                }
            }
        }
        /* Sylwia - FD-788, FD-855, FD-960, FD-1029 - Get More News/No More News issues - end */

        if (xhr.getResponseHeader('Location')) {
            document.location.href = xhr.getResponseHeader('Location');
        } else if (
            xhr.getResponseHeader('Refresh') == '0;url='+php.baseUrl || xhr.getResponseHeader('refresh') == '0;url='+php.baseUrl
            || xhr.getResponseHeader('newsfeed_id') || xhr.getResponseHeader('redirect_url')
            || xhr.responseText.match(/<div id="login"[^>]*>/)
        ) {
            //Redirect if user not logged in. This will break all ajax requests including the popup and token input dropdown
            console.log('redirecting');
            //newsfeed_id = xhr.getResponseHeader('newsfeed_id') ? xhr.getResponseHeader('newsfeed_id') : ;
            newsfeed_id = xhr.getResponseHeader('newsfeed_id') ? xhr.getResponseHeader('newsfeed_id') : (u.getUrlParams() && 'newsfeed_id' in u.getUrlParams()) ? u.getUrlParams()['newsfeed_id'] : '';
            action_type = xhr.getResponseHeader('action_type') ? xhr.getResponseHeader('action_type') : (u.getUrlParams() && 'action_type' in u.getUrlParams()) ? u.getUrlParams()['action_type'] : '';
            redirect_url = xhr.getResponseHeader('redirect_url') ? xhr.getResponseHeader('redirect_url') : (u.getUrlParams() && 'redirect_url' in u.getUrlParams()) ? u.getUrlParams()['redirect_url'] : '';
            document.location.href = php.redirectUrl+'?redirect_url='+redirect_url;
        }
        //Include stylesheets to header if the response is HTML

        if (opts.dataType == 'html' || opts.dataTypes && opts.dataTypes[0] == 'text' && opts.dataTypes[1] == 'html') {
            var matches = xhr.responseText.match(new RegExp('<link.*?href="(.*?)"[^>]*?/>',"mgi"));
            //console.log('CSS: matches: '+matches);
            if (matches) for (var i=0;i < matches.length; i++) {
                var url = matches[i].replace(/<link.*?href="/,'').split('"')[0];
                if (!$('head').html().match(new RegExp('<link.*?href="'+url+'"[^>]*>',"mgi"))) {
                    if (document.createStyleSheet) {
                        //console.log('CSS: createCSS');
                        try {
                            document.createStyleSheet(url);
                        } catch (e) {
                            $('head').prepend(matches[i]);
                        }
                    } else {
                        //console.log('CSS: prependCSS: '+matches[i]);
                        $('head').prepend(matches[i]);
                    }
                    //$('body').find('link[href="'+url+'"]').remove();
                    //$('body').hide().show();
                } else {
                    console.log('CSS: do nothing');
                }
            }
            // document.update event is triggered only for html responses. It is used to attach needed events
            // for the html dom or other html related js modifications
            $(document).trigger('update');
        }

    });

    //Default jquery extends
    $.extend({
        post: function(url, data, callback, type) {
            if (typeof data == 'object' && !data[php.csrf.name]) data[php.csrf.name] = php.csrf.hash;
            $.ajax(url, {
                'type': 'post',
                'dataType': type,
                'data': data,
                'success': callback
            });
        }
    });

    //Recursive function to emulate behavior of jQuery.find()
    function iterate_selector(selectors,elt) {
        if (selectors.length === 1) {
            return search_in_element(elt, selectors[0]);    
        } else {
            var elt = search_in_element(elt, selectors[0]);
            selectors.shift();
            if (!elt) {
                return;
            }
            return iterate_selector(selectors,elt);
        }
    }

    function search_in_element(elt,sel) {
        var first, last, res;
        if (sel.indexOf('#') >= 0) {
            first = sel.indexOf('#');
            last = sel.substr(first+1).search(/#|:|\./);
            if (last >= 0) {
                last = first+last+1;
                return document.getElementById(sel.substring(first+1,last));
            } else {
                return document.getElementById(sel.substring(first+1));
            }
        } else if (sel.indexOf('.') >= 0) {
            first = sel.indexOf('.');
            last = sel.substr(first+1).search(/#|:|\./);
            if (last >= 0) {
                last = first+last+1;
                res = elt.getElementsByClassName(sel.substring(first+1,last));
            } else {
                res = elt.getElementsByClassName(sel.substring(first+1));
            }
        } else if (sel.indexOf(':') !== 0) {
            console.log('1');
            last = sel.search(/#|:|\./);
            if (last >= 0) {
                last = last+1;
                res = elt.getElementsByTagName(sel.substring(0,last));
            } else {
                res = elt.getElementsByTagName(sel);
            }
        } else {
            console.log('2');
            res = elt.getElementsByTagName(sel);
        }
        if (res && res.length) {
            return res[0];
        }
        return;
    }


    var _find_func = $.fn.find;
    //Used to catch the jquery errors due to .find. If there is an exception, revert to old-fashioned pure JS way.
    //Not extensive but should be good in most cases. Does not cover : or :not. However, it excludes : altogether to avoid conflicts
    /*$.fn.extend({
        find: function(selectors) {

            try {
                //console.log('FIND');
                //console.log(this);
                //console.log(selectors);
                return _find_func.call(this, selectors);
                //console.log('FIND DONE');
            } catch(exc) {
                console.log('exception thrown');
                var sels = selectors.split(' ');
                var results = iterate_selector(sels,document);
                console.log(results);
                return $(results);          
            }
        }
    });*/


    $(document).on('click', '#external_connect_fb', function(){

        FB.login(function(response) {
            $.post("/signup/fb", {}, function(response) {
                location.reload();
            }, 'json');
        }, {scope:'user_about_me,email,user_birthday,user_interests,publish_actions,offline_access,read_stream'});
        return false;
    });

    function close_external_network() {
        $('#signup_iframe_div').dialog('close');
    }


    //Request Invite popup logic
    if ($('#requestInvitePopup_trigger').length) {
    	setTimeout(function() {
    		$('#requestInvitePopup_trigger').click();
    	},5000);
    }
    
    if ($('#header #search').length) $('#header #search').show();
    $(document).ready(function(){
        $('#header #search').show();
        if (window.location.hash.indexOf('/drop/') > -1) { //For IE link preview popup when user sends a link to another user
            window.location = php.baseUrl+window.location.hash.substr(2);
        }
    });

    $(window).one("scroll", function(){
        $("#dropperPop").show().animate({
            right:'0px'}, 500);
    });

    $("a").click(function(){
        $("#dropperPop").animate({
      right:'-200px'}, 2000).hide();
    });


    //Closes the Video/Landing page header addition
    $(document).on('preAjax', '#headerLanding .closeBox', function(){
        console.log('closing');
        $('#headerLanding .expandingBox').hide('fade');
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
