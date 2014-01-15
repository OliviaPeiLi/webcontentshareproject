crossAjax = new easyXDM.Rpc({
    remote:uploadpath + '/js/easyxdm/cors/index.html', // наш провайдер на удаленном сервере
    swf:uploadpath + '/js/easyxdm/easyxdm.swf'
}, {
    remote:{
        request:{}
    }
});

//right click
(function ($)
{
    $.fn.rightClick = function (method)
    {

        $(this).live('contextmenu rightclick', function (e)
        {
            e.preventDefault();
            method();
            return false;
        });

    };
})(jQuery);

//outer html
jQuery.fn.outerHTML = function (s)
{
    return s
        ? this.before(s).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};

load_css_and_controls();
load_selector();

function save()
{
    crossAjax.request({ // шлем кросс доменный запрос, подставляя наши параметры
        url:uploadscript,
        method:"POST",
        data:jsontosend
    }, function (response)
    {
        switch (response.status)
        { // разбираем ответ
            case 200:
                console.log('done');
                clip_close();
                break;
            default:
                console.log("Error: " + response.status);
                break;
        }
    });

    jQuery("#clip_preview").remove();
    return false;
}

function cancelsave()
{
    jQuery("#clip_preview").remove();
    clip_start();
    return false;
}

//DK: Add selected elements to the preview box?
function appendclone(el)
{
    var prefix = '';
    var postfix = '';
    jQuery(el).find('*').each(function (index)
{
    jQuery(this).attr('rel',appendindex);
    appendindex++;
});

    newclone = jQuery(el).clone();
    jQuery(newclone).find('div#clip_overlay,div#clip_controls,div#clip_overlay_warning').remove();
    jQuery(newclone).find('iframe').remove();
    jQuery(newclone).find('script').remove();

    if (jQuery(newclone).get(0).tagName == 'TD')
    {
        var prefix = '<table><tr>';
        var postfix = '</tr></table>';
    }
    if (jQuery(newclone).get(0).tagName == 'TR')
    {
        var prefix = '<table>';
        var postfix = '</table>';
    }

    //css parsing via classes
    jQuery(newclone).find('*').each(function (index)
    {
        donor = jQuery(el).find('[rel="'+jQuery(this).attr('rel')+'"]');
        jQuery(this).copyCSS(donor).removeAttr('class').removeAttr('id');

        jQuery(this).attr('rel', '');

        if (jQuery(this).get(0).tagName == 'IMG')
        {
            jQuery(this).attr('src', jQuery(this).get(0).src);
        }
    });
    //end of parsing

    jQuery(newclone).copyCSS(el).removeAttr('class').removeAttr('id');
    jQuery(newclone).css('position', 'relative');

    jQuery(clipboardresult).append(prefix + jQuery(newclone).outerHTML() + postfix);

    return;
}

function checkchildren(el)
{
    eltop = jQuery(el).offset().top;
    if (parseInt(jQuery(el).css('margin-top')))
    {
        eltop -= parseInt(jQuery(el).css('margin-top'));
    }

    elleft = jQuery(el).offset().left;
    if (parseInt(jQuery(el).css('margin-left')))
    {
        elleft -= parseInt(jQuery(el).css('margin-left'));
    }

    var success = 0;
    if (jQuery(el).attr('id') != 'clip_overlay')
    {
        if (!(eltop < jQuery('#clip_overlay').offset().top))
        {
            if (!(elleft < jQuery('#clip_overlay').offset().left))
            {
                if (!((eltop + jQuery(el).outerHeight(true)) > (jQuery('#clip_overlay').offset().top + jQuery('#clip_overlay').outerHeight(true))))
                {
                    if (!((elleft + jQuery(el).outerWidth(true)) > (jQuery('#clip_overlay').offset().left + jQuery('#clip_overlay').outerWidth(true))))
                    {
                        success = 1;
//                    console.log(el);
                    }
                }
            }
        }
    }

    if (success)
    {
//        console.log(el);
        appendclone(el);
    }

    if (!success && jQuery(el).children().length)
    {
        jQuery(el).children().each(function ()
        {
            checkchildren(jQuery(this));
        });
    }
}

function previewbind(e)
{
    if (jQuery("#clip_preview").size())
    {
        var clickedinside = false;
        el = jQuery(this).elementFromPoint(e.clientX, e.clientY);
        jQuery(el).parents().map(function ()
        {
            if (jQuery(this).attr('id') == 'clip_preview')
                clickedinside = true;
        });

        if (!clickedinside)
        {
            cancelsave();
            return false;
        }
    }
}

function clear_and_preview()
{
    clipboardresult = jQuery('<div/>');

    paused = 1;

    var myclone = jQuery(currentclip);

//    jQuery(myclone).find('script').remove();

    if (myclone.get(0).tagName != 'BODY')
        myclone = myclone.parent();
    if (document.domain == 'techcrunch.com' || document.domain == 'www.techcrunch.com')
    {
        if (myclone.get(0).tagName != 'BODY')
            myclone = myclone.parent();
    }

    var prefix = '';
    var postfix = '';

    checkchildren(myclone);

//    console.log(clipboardresult);

    jsontosend = {'data':jQuery(clipboardresult).html()};

//    jsontosend = {'data':'<b>omg</b>'};

    //preview overlay
    jQuery('body').append('<div id="clip_preview"></div>');

    jQuery("#clip_preview").append('<div id="clip_preview_box">' + jsontosend.data + '</div><a href="#" id="clip_cancel">cancel</a>&nbsp;<a href="#" id="clip_save">save</a>');
    currentclip = undefined;
}

function clip_close()
{
    jQuery("#clip_controls").remove();
    jQuery("#clip_overlay").remove();
    jQuery("#clip_overlay_warning").remove();
    jQuery("#clip_preview").remove();
    jQuery('iframe[id^="easyXDM"]').remove();

    jQuery('div:not(#clip_controls),span:not(#clip_mode),p,a:not(#clip_stop,#clip_close,#clip_start),object,embed').die('mousemove');
    jQuery('*').die('mousewheel');
    return false;
}

function clip_stop()
{
    paused = 1;
    jQuery("#clip_stop").text('start').attr('id', 'clip_start');
    return false;
}

function clip_start()
{
    paused = 0;
    jQuery("#clip_start").text('stop').attr('id', 'clip_stop');
    return false;
}

function load_selector()
{

    if (document.domain == 'youtube.com' || document.domain == 'www.youtube.com')
    {
        maketransparent();
    }

    jQuery(document).live('click', previewbind);

    jQuery("#clip_cancel").live('click', cancelsave);
    jQuery("#clip_save").live('click', save);

    jQuery("#clip_stop").live('click', clip_stop);
    jQuery("#clip_start").live('click', clip_start);

    jQuery("#clip_close").click(clip_close);

    jQuery(document).keydown(function (e)
    {
        if (e.keyCode === 27)
        {
            clip_stop();
        }

    });

    jQuery("#clip_overlay").rightClick(function (e)
    {
        scrollpage = !scrollpage;
        if (scrollpage)
            jQuery('#clip_mode').text('scrolling mode');
        else
            jQuery('#clip_mode').text('clipping mode');
    });

    jQuery('#clip_overlay').live('click', function (e)
    {
        jQuery('#clip_overlay').hide();
        el = jQuery(this).elementFromPoint(e.clientX, e.clientY);
        jQuery('#clip_overlay').show();

        if (!jQuery("#clip_preview").size())
            clear_and_preview();
        return false;
    });

    jQuery('div:not(#clip_controls),span:not(#clip_mode),p,a:not(#clip_stop,#clip_close,#clip_start),object,embed').live('mousemove', function (e)
    {
        if (paused)
            return;

        if (scroll_x > -1 && scroll_y > -1)
        {
            xdiff = e.pageX - scroll_x;
            ydiff = e.pageY - scroll_y;
            var distance = Math.pow((xdiff * xdiff + ydiff * ydiff), 0.5);
//            console.log('distance: '+distance);
            if (distance < 100)
                return;
            else
            {
                scroll_x = -1;
                scroll_y = -1;
            }
        }


        if (jQuery(this).attr('id') == 'clip_overlay')
        {
            jQuery('#clip_overlay').hide();
            el = jQuery(this).elementFromPoint(e.clientX, e.clientY);
            jQuery('#clip_overlay').show();
        }
        else
        {
            el = jQuery(this).elementFromPoint(e.clientX, e.clientY);
        }

        if (e.ctrlKey)
            console.log(jQuery(el));

//        if (jQuery(el).get(0).tagName != 'IFRAME' && currentclip != el)
        if (jQuery(el).get(0).tagName != 'IFRAME' && currentclip != el)
        {
            if (jQuery(el).find('*').size() < maxelements)
            {
                currentstack = Array();
                make_selection(el);
            }
        }
        else
            e.stopPropagation();
    });

    jQuery('*').live('mousewheel', function (e, delta, deltaX, deltaY)
    {
        if (!scrollpage && !paused)
        {
        	console.log(currentstack);
            thisparent = jQuery(currentclip).parent();
            if (deltaY > 0 && thisparent.get(0).tagName != 'HTML')
            {
                //scrolling up
                if (jQuery(currentclip).parent().find('*').size() < maxelements)
                {
                    scroll_x = e.pageX;
                    scroll_y = e.pageY;
                    currentstack.push(currentclip);
                    make_selection(jQuery(currentclip).parent(), true);
                }
                else
                {
                    jQuery('#clip_overlay_warning').text('stop! you are too greedy, try select less stuff man').show().fadeOut(3000);
                }
            }
            else
            {
                if (deltaY < 0 && currentstack.length)
                {
                    make_selection(currentstack.pop(), true);
                    if (currentstack.length)
                    {
                        scroll_x = e.pageX;
                        scroll_y = e.pageY;
                    }
                    else
                    {
                        scroll_x = -1;
                        scroll_y = -1;
                    }

                }
            }
            return false;
        }
    });
}

function make_selection(el, scrolling)
{
    if (typeof scrolling == "undefined")
        scrolling = false;

    currentclip = el;

    var ntop = jQuery(el).offset().top;
    var nleft = jQuery(el).offset().left;
    var nwidth = jQuery(el).outerWidth(true);
    var nheight = jQuery(el).outerHeight(true);

	//var overlay_border = parseInt(jQuery('#clip_overlay').css('border-width'));
	var overlay_border = 3;
	//console.log('overlay_border='+overlay_border);

    if (parseInt(jQuery(el).css('margin-top')))
    {
        ntop -= parseInt(jQuery(el).css('margin-top'));
    }

    if (parseInt(jQuery(el).css('margin-left')))
    {
        nleft -= parseInt(jQuery(el).css('margin-left'));
    }
    
    //compensate for border
    ntop -= overlay_border;
    nleft -= overlay_border;

    if (jQuery(el).get(0).tagName == 'A')
    {
        jQuery(el).children('img').each(function ()
        {
            if (jQuery(this).offset().top < ntop)
            {
                nheight += ntop - jQuery(this).offset().top;
                ntop = jQuery(this).offset().top;
            }
            if (jQuery(this).offset().left < nleft)
            {
                nwidth += nwidth - jQuery(this).offset().left;
                nleft = jQuery(this).offset().left;
            }

        });
    }

    if (scrolling)
    {
        jQuery("#clip_overlay").animate({
            top:ntop,
            left:nleft,
            width:nwidth,
            height:nheight
        }, 300).show();
    }
    else
    {
    	/*
        jQuery("#clip_overlay")
            .css('top', ntop)
            .css('left', nleft)
            .css('width', nwidth)
            .css('height', nheight)
            .css('z-index', '9999999')
            .show();
        */
    	
        jQuery("#clip_overlay").stop().animate({
            top: ntop,
            left: nleft,
            width: nwidth,
            height: nheight
            }, 300)
            .css('z-index', '9999999')
            .show();
        
    }
}

function load_css_and_controls()
{
//    jQuery('head').append('<link rel="stylesheet" href="' + path + '/css/style.css" type="text/css" />');
    //controls
    jQuery('body').append('<div id="clip_controls"><a id="clip_stop" href="#">stop</a>&nbsp;<a id="clip_close" href="#">close</a><br><span id="clip_mode">clipping mode</span></div>');
    //overlay
    jQuery('body').append('<div id="clip_overlay"></div><div id="clip_overlay_warning">stop! you are too greedy, try select less stuff man</div>');
    //preview
}

function maketransparent()
{
    // For embed
    jQuery("embed").each(function (i)
    {
        var elClone = this.cloneNode(true);
        elClone.setAttribute("WMode", "Transparent");
        jQuery(this).before(elClone);
        jQuery(this).remove();
    });
    // For object and/or embed into objects
    jQuery("object").each(function (i, v)
    {
        var elEmbed = jQuery(this).children("embed");
        if (typeof (elEmbed.get(0)) != "undefined")
        {
            if (typeof (elEmbed.get(0).outerHTML()) != "undefined")
            {
                elEmbed.attr("wmode", "transparent");
                jQuery(this.outerHTML()).insertAfter(this);
                jQuery(this).remove();
            }
            return true;
        }
        var algo = this.attributes;
        var str_tag = '<OBJECT ';
        for (var i = 0; i < algo.length; i++) str_tag += algo[i].name + '="' + algo[i].value + '" ';
        str_tag += '>';
        var flag = false;
        jQuery(this).children().each(function (elem)
        {
            if (this.nodeName == "PARAM")
            {
                if (this.name == "wmode")
                {
                    flag = true;
                    str_tag += '<PARAM NAME="' + this.name + '" VALUE="transparent">';
                }
                else  str_tag += '<PARAM NAME="' + this.name + '" VALUE="' + this.value + '">';
            }
        });
        if (!flag)
            str_tag += '<PARAM NAME="wmode" VALUE="transparent">';
        str_tag += '</OBJECT>';
        jQuery(str_tag).insertAfter(this);
        jQuery(this).remove();
    });
}