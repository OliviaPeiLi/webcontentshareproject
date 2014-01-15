/**
 * Miscellaneous JS
 *  JS that could not be placed in any module
 *
 */
define(['jquery'],function() {
	
    (function ($) {

        $.topZIndex = function (selector) {
            return Math.max(0, Math.max.apply(null, $.map(((selector || "*") === "*")? $.makeArray(document.getElementsByTagName("*")) : $(selector),
                function (v) {
                    return parseFloat($(v).css("z-index")) || null;
                }
            )));
        };

        $.fn.topZIndex = function (opt) {

            // Do nothing if matched set is empty
            if (this.length === 0) {
                return this;
            }
            
            opt = $.extend({increment: 1}, opt);

            // Get the highest current z-index value
            var zmax = $.topZIndex(opt.selector),
                inc = opt.increment;

            // Increment the z-index of each element in the matched set to the next highest number
            return this.each(function () {
                this.style.zIndex = (zmax += inc);
            });
        };

    })(jQuery);

	/**
	 * browser support check
	 */
	if (typeof document.createElement('textarea').placeholder == 'undefined') {
		$('*[placeholder]').on('focus', function() {
			if ($(this).val() == $(this).attr('placeholder')) {
				$(this).val('').removeClass('input_placeholder');
			}
		}).on('blur', function() {
			if ($(this).val() == '') {
				$(this).val($(this).attr('placeholder')).addClass('input_placeholder');
			}
		})
	}
	
    //For loading Css via JS
    this.loadCss = function(url) {
        url = (php.baseCSS)+url;
        if (document.createStyleSheet) {
            document.createStyleSheet(url);
        } else {
            $('head').prepend($('<link rel="stylesheet" type="text/css" href="'+url+'"/>'));
        }
    };

    this.getUrlParams = function() {
           var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    };

    this.isConnectedToFb = function() {
        if (typeof FB === 'undefined') {
            console.log('reinit fb');
            window.setTimeout(function() {
                console.log('timeout on isConnectedToFb');
                isConnectedToFb();
            },100);
            FB.getLoginStatus(function(resp) {
                console.log('FB.getLoginStatus');
                if (resp.status === 'connected') {
                    console.log('synced with fb');
                    return true;
                } else {
                    console.log('no fb association found');
                    return false;
                }
            });
        }
    };

    this.getParamFromURL = function(name) {
        var match = RegExp('[?&]' + name + '=([^&]*)')
                        .exec(window.location.search);
        return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
    };

    this.nl2br = function(str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
    };
    this.htmlspecialchars = function(str) {
        return $('<span>').text(str).html();
    };

    this.br2nl = function(str) {
        return jQuery(this).val().replace(/(<br>)|(<br \/>)|(<p>)|(<\/p>)/g, "\r\n");
    };

    // add reverse elements function to public jquery
    jQuery.fn.reverse = [].reverse;

    // settime if is not set
    //if (!getCookie("timeoffset"))   {
        
    /*
            var timeoffset = ( ( new Date() ).getTimezoneOffset() / 60 );
            $.get("/time/set",{'timeoffset':timeoffset,'time': Math.round((new Date()).getTime() / 1000) },function(){
                setCookie("timeoffset",timeoffset);
            });
        //}
    */

    return this;
});

    function setCookie(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    
    function deleteCookie(name) {
        setCookie(name,"",-1);
    }