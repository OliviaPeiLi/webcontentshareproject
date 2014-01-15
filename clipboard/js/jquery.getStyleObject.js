/*
 * getStyleObject Plugin for jQuery JavaScript Library
 * From: http://upshots.org/?p=112
 */

(function ($) {
    $.fn.getStyleObject = function () {
        var dom = this.get(0);
        var style;
        var returns = {};
        if (window.getComputedStyle) {
            var camelize = function (a, b) {
                return b.toUpperCase();
            };
            style = window.getComputedStyle(dom, null);
            for (var i = 0, l = style.length; i < l; i++) {
                var prop = style[i];
                var camel = prop.replace(/\-([a-z])/g, camelize);
                var val = style.getPropertyValue(prop);
                returns[camel] = val;
            }
            ;
            return returns;
        }
        ;
        if (style = dom.currentStyle) {
            for (var prop in style) {
                returns[prop] = style[prop];
            }
            ;
            return returns;
        }
        ;
        return this.css();
    }
})(jQuery);

jQuery.fn.copyCSS = function (source) {
    var attr = ['font-family', 'font-size', 'font-weight', 'font-style', 'color',
        'text-transform', 'text-decoration', 'letter-spacing', 'word-spacing',
        'line-height', 'text-align', 'vertical-align', 'direction', 'background-color',
        'background-image', 'background-repeat', 'background-position',
        'background-attachment', 'opacity', 'width', 'height', 'top', 'right', 'bottom',
        'left', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
        'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
        'border-top-width', 'border-right-width', 'border-bottom-width',
        'border-left-width', 'border-top-color', 'border-right-color',
        'border-bottom-color', 'border-left-color', 'border-top-style',
        'border-right-style', 'border-bottom-style', 'border-left-style', 'position',
        'display', 'visibility', 'z-index', 'overflow-x', 'overflow-y', 'white-space',
        'clip', 'float', 'clear', 'cursor', 'list-style-image', 'list-style-position',
        'list-style-type', 'marker-offset'];

    var dom = jQuery(source).get(0);
    var style;
    var dest = {};
    if (window.getComputedStyle) {
        var camelize = function (a, b) {
            return b.toUpperCase();
        };
        style = window.getComputedStyle(dom, null);
        for (var i = 0, l = style.length; i < l; i++) {
            var prop = style[i];
            if (jQuery.inArray(prop, attr) > -1) {
                var camel = prop.replace(/\-([a-z])/g, camelize);
                var val = style.getPropertyValue(prop);
                dest[camel] = val;
            }
        }
        ;
        return this.css(dest);
    }
    ;
    if (style = dom.currentStyle) {
        for (var prop in style) {
            if (jQuery.inArray(prop, attr) > -1) {
                dest[prop] = style[prop];
            }
        }
        ;
        return this.css(dest);
    }
    ;
    if (style = dom.style) {
        for (var prop in style) {
            if (typeof style[prop] != 'function') {
                if (jQuery.inArray(prop, attr) > -1) {
                    dest[prop] = style[prop];
                }
            }
            ;
        }
        ;
    }
    ;
    return this.css(dest);
};