/*
 * notifications menu in the header and notifications page
 * @link /show_all
 * @uses jquery
 * @uses common
 */
define(['jquery','common/autoscroll_new','common/fd-scroll'], function(){
	
	/**
	 * After the limit of autoscroll is reached. this button triggers the load of more notifications
	 */
	$(document).on('click','#more_notifications_btn a', function() {
		$('.notifications_list').trigger('scroll_bottom');
		return false;
	});
	
	$(document).on('success','.accept_connection', function() {
		$(this).parent().html('<span class="grayed">Already Following</span>');
	});
	
	/**
	 * Make the whole notification area clickable
	 */
	$(document).on('click','#notifications .notification_holder, #all_notifications_container .notification_holder', function(e) {

		if ( $(e.target).hasClass("user_from_link") )	{
			return true;
		}

		if ($(this).find('[href="#preview_popup"]').length) {
			$(this).find('[href="#preview_popup"]').trigger('click');
		} else if ($(this).find('a.link, a.read').length) {
			window.location.href = $(this).find('a.link, a.read').attr('href');
		}

		// FD-4910, to make sure $(document).on('click', '[rel=ajaxButton]',..)
		// will work
		// return false;
	});

	/**
	 * Make the whole notification area clickable
	 */
	$(document).on('click', '#notifications .notification_iconHolder, #all_notifications_container .notification_iconHolder', function() {

		if ($(this).parent().find('[href="#preview_popup"]').length) {
			$(this).parent().find('[href="#preview_popup"]').trigger('click');
		} else if ($(this).parent().find('a.link, a.read').length) {
			window.location.href = $(this).parent().find('a.link, a.read').attr('href');
		}

		return false;		
	});

    $(document).on('success','.request_status',function(){
            $('.js_allready_follow', $(this).closest(".follow_box")).show();
            $('.js_follow', $(this).closest(".follow_box")).hide();
    });

	/**
	 * Notifications page autoscroll
	 */
	$( document ).on('scroll_bottom', '.notifications_list', function() {
		if (this.ajaxList_process instanceof Function) return;
		this.ajaxList_process = function ( data ) {

			var $notifications = $( '#tmpl-notification-list-item' ).tmpl( data.notifications, function ( data ) {
				//set notification class
				this.find('.notification_icon').removeClass('notification_').addClass('notification_'+data.type);
				
				//add notification text
				var tmpl = $('#tmpl-notification-'+data.type).tmpl(data);
				this.find( '.notification_holder' ).prepend( tmpl );
				
                if (typeof data._newsfeed != 'undefined' && (data._newsfeed.link_type == 'text' || data._newsfeed.link_type == 'content'))   {
                    $('.link-popup',this).hide();
                }

                //set follow button
                if ( data.is_following ) {

                    //todo show/hide follow back btn
                    $('span.js_allready_follow',this).show().attr("");
                    $('.js_follow',this).hide();

                } else {

                    //todo show/hide follow back btn
                    $('.js_allready_follow',this).hide();
                    $('.js_follow',this).show();

                    if ($('.request_status',this).length )  {
                        $('.request_status',this).attr("href", 
                            $('.request_status',this).attr("href").replace( "-1", data.user_id_from )
                        );
                    }

                }

				//set notification time
				checkTimeElement.apply( $(this).find('[data-timestamp]') );
			} );

			$( this ).find( 'ul' ).append( $notifications );

		};
	});

	/**
	 * header notifications autoscroll
	 */
	$( document ).on('scroll_bottom', '#scroll_notifications', function() {

		if (this.ajaxList_process instanceof Function) return;

		this.ajaxList_process = function ( data ) {

			//set notification class
			this.find('.notification_icon').removeClass('notification_').addClass('notification_'+data.type);
			
			//add notification text
			var tmpl = $('#tmpl-notification-'+data.type).tmpl(data);
			this.find( '.notification_holder' ).prepend( tmpl );

            if (typeof data._newsfeed != 'undefined' && (data._newsfeed.link_type == 'text' || data._newsfeed.link_type == 'content'))   {
                $('.link-popup',this).hide();
            }

			//set follow button
			if ( data.is_following ) {
				//todo show/hide follow back btn
                $('span.js_allready_follow',this).show();
                $('.js_follow',this).hide();

			} else {

				//todo show/hide follow back btn
                $('.js_allready_follow',this).hide();
                $('.js_follow',this).show();

                if ($('.request_status',this).length) {
                    $('.request_status',this).attr("href", 
                        $('.request_status',this).attr("href").replace( "-1", data.user_id_from )
                    );
                }
			}

			//set notification time
			checkTimeElement.apply( $(this).find('[data-timestamp]') );

		};
	});

	function checkTimeElement()	{

        var timestamp =  $(this).attr("data-timestamp");
        
        if ($(this).attr("type") != 'template/html')  {
            var _timestamp = parseInt(timestamp) + parseInt( timediff * 3600 );
            var date = new Date(_timestamp * 1000);
                date.setHours( date.getHours() - timediff );
            var days_diff = ( (new Date().getTime()) - _timestamp *1000  ) / 86400000;
            if (days_diff < 1)  {
               date = date.format('h:i A');
            }   else {
                date = date.format('M d,Y h:i A');
            }
            $(this).html( date ).css('display','block').addClass("ready");
        }

	}

    function getTimeDiff()    {

        var localtime = new Date();
        var month = localtime.getMonth() + 1;
            localtime = localtime.getFullYear() + " " 
                        + ( month < 10 ? "0" + month : month ) + " " 
                        + ( localtime.getDate() < 10 ?  "0" + localtime.getDate() : localtime.getDate()) + " " 
                        + ( localtime.getHours() < 10 ? "0" + localtime.getHours() : localtime.getHours() );
            localtime = localtime.split(" ").join("");

        var localtime = parseInt(localtime);
        var serverTimeNum = parseInt(php.serverTime);

    return localtime - serverTimeNum;
    }

    var timediff = getTimeDiff();

    // to be discussed
    $(document).on("update_timestamp", '[data-timestamp]', checkTimeElement).trigger("update_timestamp");

});


Date.prototype.format = function(format) {

    var returnStr = '';
    var replace = Date.replaceChars;

    for (var i = 0; i < format.length; i++) {    

        var curChar = format.charAt(i);      

        if (i - 1 >= 0 && format.charAt(i - 1) == "\\") {
            returnStr += curChar;
        }
        else if (replace[curChar]) {
            returnStr += replace[curChar].call(this);
        } else if (curChar != "\\"){
            returnStr += curChar;
        }
    }
    return returnStr;
};

    function isInt(n) {
        return typeof n === 'number' && n % 1 == 0;
    }

    Date.replaceChars = {
        shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        longMonths: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        longDays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],

        // Day
        d: function() { return (this.getDate() < 10 ? '0' : '') + this.getDate(); },
        D: function() { return Date.replaceChars.shortDays[this.getDay()]; },
        j: function() { return this.getDate(); },
        l: function() { return Date.replaceChars.longDays[this.getDay()]; },
        N: function() { return this.getDay() + 1; },
        S: function() { return (this.getDate() % 10 == 1 && this.getDate() != 11 ? 'st' : (this.getDate() % 10 == 2 && this.getDate() != 12 ? 'nd' : (this.getDate() % 10 == 3 && this.getDate() != 13 ? 'rd' : 'th'))); },
        w: function() { return this.getDay(); },
        z: function() { var d = new Date(this.getFullYear(),0,1); return Math.ceil((this - d) / 86400000); }, // Fixed now
        // Week
        W: function() { var d = new Date(this.getFullYear(), 0, 1); return Math.ceil((((this - d) / 86400000) + d.getDay() + 1) / 7); }, // Fixed now
        // Month
        F: function() { return Date.replaceChars.longMonths[this.getMonth()]; },
        m: function() { return (this.getMonth() < 9 ? '0' : '') + (this.getMonth() + 1); },
        M: function() { return Date.replaceChars.shortMonths[this.getMonth()]; },
        n: function() { return this.getMonth() + 1; },
        t: function() { var d = new Date(); return new Date(d.getFullYear(), d.getMonth(), 0).getDate() }, // Fixed now, gets #days of date
        // Year
        L: function() { var year = this.getFullYear(); return (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)); },   // Fixed now
        o: function() { var d  = new Date(this.valueOf());  d.setDate(d.getDate() - ((this.getDay() + 6) % 7) + 3); return d.getFullYear();}, //Fixed now
        Y: function() { return this.getFullYear(); },
        y: function() { return ('' + this.getFullYear()).substr(2); },
        // Time
        a: function() { return this.getHours() < 12 ? 'am' : 'pm'; },
        A: function() { return this.getHours() < 12 ? 'AM' : 'PM'; },
        B: function() { return Math.floor((((this.getUTCHours() + 1) % 24) + this.getUTCMinutes() / 60 + this.getUTCSeconds() / 3600) * 1000 / 24); }, // Fixed now
        g: function() { return this.getHours() % 12 || 12; },
        G: function() { return this.getHours(); },
        h: function() { return ((this.getHours() % 12 || 12) < 10 ? '0' : '') + (this.getHours() % 12 || 12); },
        H: function() { return (this.getHours() < 10 ? '0' : '') + this.getHours(); },
        i: function() { return (this.getMinutes() < 10 ? '0' : '') + this.getMinutes(); },
        s: function() { return (this.getSeconds() < 10 ? '0' : '') + this.getSeconds(); },
        u: function() { var m = this.getMilliseconds(); return (m < 10 ? '00' : (m < 100 ?
    '0' : '')) + m; },
        // Timezone
        e: function() { return "Not Yet Supported"; },
        I: function() {
            var DST = null;
                for (var i = 0; i < 12; ++i) {
                        var d = new Date(this.getFullYear(), i, 1);
                        var offset = d.getTimezoneOffset();

                        if (DST === null) DST = offset;
                        else if (offset < DST) { DST = offset; break; }                     else if (offset > DST) break;
                }
                return (this.getTimezoneOffset() == DST) | 0;
            },
        O: function() { return (-this.getTimezoneOffset() < 0 ? '-' : '+') + (Math.abs(this.getTimezoneOffset() / 60) < 10 ? '0' : '') + (Math.abs(this.getTimezoneOffset() / 60)) + '00'; },
        P: function() { return (-this.getTimezoneOffset() < 0 ? '-' : '+') + (Math.abs(this.getTimezoneOffset() / 60) < 10 ? '0' : '') + (Math.abs(this.getTimezoneOffset() / 60)) + ':00'; }, // Fixed now
        T: function() { var m = this.getMonth(); this.setMonth(0); var result = this.toTimeString().replace(/^.+ \(?([^\)]+)\)?$/, '$1'); this.setMonth(m); return result;},
        Z: function() { return -this.getTimezoneOffset() * 60; },
        // Full Date/Time
        c: function() { return this.format("Y-m-d\\TH:i:sP"); }, // Fixed now
        r: function() { return this.toString(); },
        U: function() { return this.getTime() / 1000; }
    };
