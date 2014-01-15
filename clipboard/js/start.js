var path = 'http://dmitry.fantoon.com/clipboard';
var uploadpath = 'http://dmitry.fantoon..com/clipboard';
var uploadscript = '../../../receiver/index.php';
var tries = 0;
var currentclip = undefined;
var currentstack = Array();
var paused = 0;
var jsontosend = {'data':''}
var noconflict = 1;
var maxelements = 1000;
var clipboardresult = undefined;
var scroll_x = -1;
var scroll_y = -1;
var scrollpage = false;
var appendindex = 1;

load_jquery();
wait_for_jquery();

function mainpart()
{
    jQuery('body').append('<div id="clip_preloader"><img src="http://dmitry.fantoon.com/clipboard/i/ajax-loader.gif"/></div>');

    if (noconflict)
        jQuery.noConflict();

    jQuery(document).ajaxError(function (e, jqxhr, settings, exception)
    {
        if (settings.dataType == 'script')
        {
            console.log(exception);
        }
    });

    jQuery.ajaxSetup({
        async:false
    });

    jQuery.getScript(path + '/js/jquery.elementfrompoint.js', function ()
    {
        jQuery.getScript(path + '/js/jquery.mousewheel.js', function ()
        {
            jQuery.getScript(path + "/js/jquery.getStyleObject.js", function ()
            {
                jQuery.getScript(path + "/js/easyxdm/easyXDM.min.js", function ()
                {
                    jQuery.getScript(path + "/js/easyxdm/json2.js");
                    jQuery.getScript(path + "/js/main.js", function() {
                        jQuery("#clip_preloader").remove();
                    });
                });
            });
        });
    });
}

function wait_for_jquery()
{
    if (!window.jQuery)
    {
        console.log('no jq');
        tries++;
        if (tries > 10)
        {
            return;
        }
        setTimeout(wait_for_jquery, 2000);
    }
    else
    {
        if (jQuery.fn.jquery != '1.7.1')
        {
            console.log('ver jQuery.fn.jquery');
            tries++;
            if (tries > 10)
            {
                return;
            }
            setTimeout(wait_for_jquery, 2000);
        }
        else
            mainpart();
    }
}

function load_jquery()
{
    var css = document.createElement('link');
    css.setAttribute('rel', 'stylesheet');
    css.setAttribute('href', path + '/css/style.css');
    css.setAttribute('type', 'text/css');
    document.body.appendChild(css);

    if (window.jQuery)
        noconflict = 0;

    if (!window.jQuery)
    {
        var loadedjquery = document.createElement('script');
        loadedjquery.setAttribute('type', 'text/javascript');
        loadedjquery.setAttribute('charset', 'UTF-8');

        loadedjquery.setAttribute('src', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js');
        document.body.appendChild(loadedjquery);
    }
    else
    {
        if (jQuery.fn.jquery != '1.7.1')
        {
            var loadedjquery = document.createElement('script');
            loadedjquery.setAttribute('type', 'text/javascript');
            loadedjquery.setAttribute('charset', 'UTF-8');

            loadedjquery.setAttribute('src', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js');
            document.body.appendChild(loadedjquery);
        }
    }
}


