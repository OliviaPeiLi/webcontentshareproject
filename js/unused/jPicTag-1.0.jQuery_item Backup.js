/**
 * jPicTag - jQuery Image tagger
 *
 * @author Henrique Barroso <info@henriquebarroso.com>
 * @version 1.1
 * @copyright Copyright (c) 2010, Henrique Barroso
 */
(function (a_item) {
    a_item.fn.tag_item = function (f_item) {
        var i_item = this,
            g_item = 0,
            e_item = 0,
            k_item = 0,
            j_item = 0,
            h_item = 0,
            o_item = false,
            l_item = 0,
            b_item = 0,
            m_item = 0,
            n_item = 0,
            c_item = 0;
        var d_item = {
            tag_by: "",
            photo_role: "",
            tag_time: "",
            tags_array: new Array,
            friends_list: new Array,
            get_url: "",
            post_url: "",
            form: "#form2",
            auto_load: false,
            auto_save: false,
            edit_mode: true,
            hide_list: false,
            visual_effects: true,
            square_box_size: 100,
            label_base_url: "",
            allow_html: false,
            resizable: false,
            remove: "remove",
            pictag: "#pictag",
            format: "json",
            pictag_content: "#pictag_content",
            pictag_enable_item: "#tag_item",
            pictag_block: '<div id="pictag_content"><div id="pictag"></div></div>',
            labels_block: "#labels",
            pictag_done: '<div id="pictag_done">Click on peoples faces to tag them, when you finish click here: <input type="button" name="pictag_disable" value="Done Tagging" id="pictag_disable"/></div>',
            pictag_box: '<div id="pictag_box"><div class="square_box"></div></div>',
            pictag_caption: '<div id="pictag_caption">				<div class="pictag_space">					Type any name or tag:<br>					<input type="text" name="pictag_tag_caption" value="" id="pictag_tag_caption"><br>				</div>				<div class="pictag_list"></div>				<div class="pictag_space">					<input type="button" name="pictag_button_tag" value="Tag" id="pictag_button_tag">  					<input type="button" name="pictag_button_cancel" value="Cancel" id="pictag_button_cancel"> 				</div>			</div>',
            pictag_text: '<div class="label" id="pictag_text"><span class="label_text"></span></div>'
        };
        if (f_item) {
            a_item.extend(d_item, f_item)
        }
        a_item(window).load(function (G_item) {
            E_item();
            t_item();
            a_item(i_item).bind("click", function (I_item) {
                C_item(this, I_item)
            });
            a_item("#pictag_box").bind("click", function (I_item) {
                C_item(d_item.pictag, I_item)
            });
            a_item("#pictag_caption").click(function () {
                return false
            });
            a_item("#pictag_button_cancel").click(function () {
                v_item();
                return false
            });
            a_item(d_item.pictag_enable_item).click(function () {
                y_item();
                return false
            });
            a_item("#pictag_disable").click(function () {
                v_item();
                s_item();
                z_item();
                if (d_item.auto_save) {
                    q_item(d_item.post_url)
                }
                return false
            });
            a_item("#pictag_button_tag").click(function () {
                F(a_item("#pictag_tag_caption").val())
            });
            a_item("#pictag_tag_caption").keyup(function (I_item) {
                if (I_item.keyCode == 13 || I_item.keyCode == 10) {
                    F_item(a_item("#pictag_tag_caption").val())
                } else {
                    x_item(a_item("#pictag_tag_caption").val())
                }
            });
            a_item(".list_item").click(function () {
                F_item(a_item("#name_item_" + a_item(this).val() + " > label").html());
                v_item()
            });
            a_item(".tag_label").live("mouseover", function () {
                var I_item = a_item(this).attr("id").split("_");
                c_item = I_item[1];
                p_item(d_item.tags_array[I_item[1]].label, d_item.tags_array[I_item[1]].x, d_item.tags_array[I_item[1]].y + d_item.tags_array[I_item[1]].h)
            });
            a_item(".tag_label").live("mouseleave", function () {
                a_item("#pictag_text > .label_text").hide()
            });
            a_item(".tag_label_hover").live("mouseover", function () {
                var I_item = a_item(this).attr("id").split("_");
                c_item = I_item[2];
                p_item(d_item.tags_array[I_item[2]].label, d_item.tags_array[I_item[2]].x, d_item.tags_array[I_item[2]].y + d_item.tags_array[I_item[2]].h)
            });
            a_item(".tag_label_hover").live("mouseleave", function () {
                var I_item = a_item(this).attr("id").split("_");
                a_item("#pictag_text > .label_text").hide()
            });
            a_item(".pictag_remove").live("click", function () {
                var I_item = a_item(this).attr("id").split("_");
                B_item(I_item[2]);
                z_item();
                if (d_item.auto_save) {
                    q_item(d_item.post_url)
                }
                return false
            });
            a_item("#pictag_text").live("mouseenter", function () {
                p_item(d_item.tags_array[c_item].label, d_item.tags_array[c_item].x, d_item.tags_array[c_item].y + d_item.tags_array[c_item].h)
            });
            a_item("#pictag_text").live("mouseleave", function () {
                a_item("#label_" + c_item).removeClass("square_box");
                a_item("#pictag_text > .label_text").hide();
                c_item = 0
            });

            function E_item() {
                a_item(i_item).wrap(d_item.pictag_block);
                a_item(d_item.pictag_content).prepend(d_item.pictag_done);
                a_item(d_item.pictag_content).append(d_item.pictag_box);
                a_item(d_item.pictag).prepend(d_item.pictag_text);
                if (!d_item.visual_effects) {
                    a_item.fx.off = !a_item.fx.off
                }
                if (d_item.edit_mode == true) {
                    a_item("#pictag_box").append(d_item.pictag_caption)
                }
                if (d_item.hide_list == true) {
                    a_item(".pictag_list").remove()
                }
                a_item(".square_box").css({
                    width: d_item.square_box_size,
                    height: d_item.square_box_size
                });
                D_item();
                if (d_item.auto_load) {
                    r_item(d_item.get_url)
                }
                k_item = a_item(d_item.pictag + " > img").width();
                j_item = a_item(d_item.pictag + " > img").height();
                a_item(d_item.pictag).css({
                    width: k_item,
                    height: j_item
                })
            }
            function t_item() {
                a_item(".tag_label").remove();
                s_item();
                z_item();
                a_item(d_item.labels_block).html(H_item());
                if (d_item.hide_list == false) {
                    a_item(".pictag_list").html(u_item())
                }
            }
            function C_item(I_item, J_item) {
                if (!o_item || !d_item.edit_mode) {
                    return false
                }
                a_item("#pictag_box").fadeIn();
                A_item();
                if (d_item.resizable) {
                    D_item()
                }
                h_item = a_item(I_item).offset();
                g_item = J_item.pageX - h_item.left - (b_item / 2);
                e_item = J_item.pageY - h_item.top - (l_item / 2);
                if ((g_item + n_item) > k_item) {
                    g_item = k_item - n_item
                }
                if ((e_item + m_item) > j_item) {
                    e_item = j_item - m_item
                }
                if (g_item < 0) {
                    g_item = 0
                }
                if (e_item < 0) {
                    e_item = 0
                }
                if (d_item.resizable) {
                    a_item(".square_box").resizable({
                        maxWidth: (k_item - g_item - (n_item - b_item)),
                        maxHeight: (j_item - e_item - (m_item - l_item))
                    })
                }
                a_item(d_item.pictag).append(a_item("#pictag_box"));
                a_item("#pictag_box").css({
                    left: g_item,
                    top: e_item
                });
                setTimeout(function () {
                    a_item("#pictag_tag_caption").focus()
                }, 500);
                return true
            }
            function v_item() {
                a_item("#pictag_box").fadeOut();
                a_item("#pictag_tag_caption").val("")
            }
            function y_item() {
                if (!d_item.edit_mode) {
                    return false
                }
                o_item = true;
                a_item(".tag_label").remove();
                a_item(d_item.pictag).css("cursor", "Crosshair");
                a_item("#pictag_done").show();
                return true
            }
            function s_item() {
                o_item = false;
                a_item(d_item.pictag).css("cursor", "default");
                a_item("#pictag_done").hide();
                a_item("#pictag_box").fadeOut()
            }
            function z_item() {
                var J_item = null,
                    L_item = null;
                for (var K_item = 0; K_item < d_item.tags_array.length; K_item++) {
                    size = d_item.tags_array[K].h + d_item.tags_array[K_item].w;
                    for (var I_item = 0; I_item < d_item.tags_array.length; I_item++) {
                        J_item = d_item.tags_array[K_item];
                        L_item = d_item.tags_array[I_item];
                        if ((d_item.tags_array[I_item].h + d_item.tags_array[I_item].w) > size) {
                            d_item.tags_array[I_item] = J_item;
                            d_item.tags_array[K_item] = L_item;
                            continue
                        }
                    }
                }
                for (var M_item = 0; M_item < d_item.tags_array.length; M_item++) {
                    a_item(d_item.pictag).append("<div class='tag_label' id='label_" + M_item + "' style='z-index:" + (d_item.tags_array.length - M_item) + ";width:" + (d_item.tags_array[M_item].w) + "px;height:" + (d_item.tags_array[M_item].h) + "px;left:" + (d_item.tags_array[M_item].x) + "px;top: " + (d_item.tags_array[M_item].y) + "px;'></div>")
                }
                a_item(d_item.form).html(w_item());
                a_item(d_item.labels_block).html(H_item());
                return true
            }
            function F_item(I_item) {
                var J_item = null;
                string_list = a_item("#name_item_" + a_item(".list_item:visible:checked").val() + " > label").html();
                if (string_list != null) {
                    I_item = string_list;
                    J_item = a_item(".list_item:visible:checked").val()
                }
                if (I_item == "") {
                    alert("You have to type a name to create a tag.");
                    return false
                }
                if (J_item == null) {
                    J_item = 0
                }
                if (!d_item.allow_html) {
                    I_item = I_item.replace(/(<([^>]+)>)/ig, "")
                }
                b_item = a_item(".square_box").innerWidth();
                l_item = a_item(".square_box").innerHeight();
                
                d_item.tags_array.push({
                    label: I_item,
                    x: g_item,
                    y: e_item,
                    h: l_item,
                    w: b_item,
                    id: J_item,
                    tag_by: d_item.tag_by,
                    tag_time: d_item.tag_time
                });
                v_item();
                return true
            }
            function B_item(I_item) {
                d_item.tags_array.splice(I_item, 1);
                a_item("#label_" + I_item).remove();
                t_item();
                return true
            }
            function w_item() {
                var I_item = "";
                for (var J_item = d_item.tags_array.length - 1; J_item >= 0; J_item--) {
                    I_item += '<input type="hidden" name="pictag[' + J_item + '][label]" value="' + escape(d_item.tags_array[J_item].label) + '" >';
                    I_item += '<input type="hidden" name="pictag[' + J_item + '][x]" value="' + d_item.tags_array[J_item].x + '" >';
                    I_item += '<input type="hidden" name="pictag[' + J_item + '][y]" value="' + d_item.tags_array[J_item].y + '" >';
                    I_item += '<input type="hidden" name="pictag[' + J_item + '][h]" value="' + d_item.tags_array[J_item].h + '" >';
                    I_item += '<input type="hidden" name="pictag[' + J_item + '][w]" value="' + d_item.tags_array[J_item].w + '" >';
                    I_item += '<input type="hidden" name="pictag[' + J_item + '][id]" value="' + d_item.tags_array[J_item].id + '" >';
                    I_item += '<input type="hidden" name="pictag[' + J_item + '][tag_by]" value="' + d_item.tags_array[J_item].tag_by + '" >'
                }
                I_item += '<div class="hidden"><input type="hidden" value="'+$('input[name=ci_csrf_token]').val()+'" name="ci_csrf_token"></div>';
                return I_item
            }
            function H_item() {
                var I_item = "",
                    K_item = "";
                if (d_item.tags_array.length == 0) {
                    return "none"
                }
                for (var J_item = d_item.tags_array.length - 1; J_item >= 0; J_item--) {
                    I_item += '<span class="label" id="tag_' + J_item + '">';
                    if (d_item.tags_array[J_item].id != 0) {
                        I_item += '<a href="' + d_item.label_base_url + d_item.tags_array[J_item].id + '">'
                    }
                    I_item += '<span class="tag_label_hover" id="tag_name_' + J_item + '">' + unescape(d_item.tags_array[J_item].label) + "</span>";
                    if (d_item.tags_array[J_item].id != 0) {
                        I_item += "</a>"
                    }
                    if (d_item.photo_role == 1 || d_item.tags_array[J_item].id == d_item.tag_by || d_item.tags_array[J_item].tag_by == d_item.tag_by) {
                        I_item += '&nbsp;<a href="#" class="pictag_remove" class="tag_label_remove"  id="tag_remove_' + J_item + '">' + d_item.remove + "</a>"
                    }
                    I_item += "</span>"
                }
                return I_item
            }
            function u_item() {
                var I_item = "";
                for (var J_item = d_item.friends_list.length - 1; J_item >= 0; J_item--) {
                    I_item += '<span id="name_item_' + d_item.friends_list[J_item].id + '"><input type="radio" name="list_item" id="list_item" class="list_item" value="' + d_item.friends_list[J_item].id + '"> <label>' + d_item.friends_list[J_item].name + "</label></span>"
                }
                return I_item
            }
            function x_item(I_item) {
                var K_item = "",
                    J_item = 0;
                I_item = I_item.toUpperCase();
                jQuery.each(a_item(".list_item"), function (L_item) {
                    K_item = a_item("#name_item_" + a_item(this).val() + " > label").html();
                    K_item = K_item.toUpperCase();
                    if (K_item.match(I_item)) {
                        a_item("#name_item_" + a_item(this).val()).show();
                        J_item++
                    } else {
                        a_item("#name_item_" + a_item(this).val()).hide()
                    }
                    a_item(this).attr("checked", false)
                });
                if (J_item == 1) {
                    a_item(".list_item:visible").attr("checked", "true")
                }
            }
            function A_item() {
                var I_item = null;
                a_item("#pictag_tag_caption").val("");
                jQuery.each(a_item(".list_item"), function (J_item) {
                    a_item(this).attr("checked", false);
                    I_item = a_item("#name_item_" + a_item(this).val()).show()
                })
            }
            function r_item(I_item) {
                a_item.ajax({
                    type: "GET",
                    url: I_item,
                    async: false,
                    dataType: d_item.format,
                    success: function (J_item) {
                        if (J_item.tags != null) {
                            d_item.tags_array = J_item.tags
                        }
                        if (J_item.friends != null) {
                            d_item.friends_list = J_item.friends
                        }
                        return true
                    }
                })
            }
            function q_item(I_item) {
                var J_item = null;
                if (d_item.format == "json") {
                    J_item = JSON.stringify(d_item.tags_array, "")
                } else {
                    J_item = a_item(d_item.form).serializeArray()
                }
                a_item.ajax({
                    type: "POST",
                    url: I_item,
                    dataType: d_item.format,
                    data: ({
                        tag_item: J_item, ci_csrf_token: $("input[name=ci_csrf_token]").val()
                    })
                })
            }
            function D_item() {
                b_item = a_item(".square_box").innerWidth();
                l_item = a_item(".square_box").innerHeight();
                n_item = a_item(".square_box").outerWidth();
                m_item = a_item(".square_box").outerHeight()
            }
            function p_item(K_item, J_item, I_item) {
                a_item("#pictag_text > .label_text").html(unescape(K_item));
                a_item("#pictag_text").css("top", I_item + "px");
                a_item("#pictag_text").css("left", J_item + "px");
                a_item("#pictag_text > .label_text").show()
            }
        })
    }
})(jQuery);
if (this.JSON) {
    JSON = function () {
        function f_item(n_item) {
            return n_item < 10 ? '0' + n_item : n_item;
        }
        Date.prototype.toJSON = function () {
            return this.getUTCFullYear() + '-' + f_item(this.getUTCMonth() + 1) + '-' + f_item(this.getUTCDate()) + 'T' + f_item(this.getUTCHours()) + ':' + f_item(this.getUTCMinutes()) + ':' + f_item(this.getUTCSeconds()) + 'Z';
        };
        var m_item = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"': '\\"',
            '\\': '\\\\'
        };

        function stringify_item(value, whitelist) {
            var a_item, i_item, k_item, l_item, r_item = /["\\\x00-\x1f\x7f-\x9f]/g,
                v_item;
            switch (typeof value) {
            case 'string':
                return r_item.test(value) ? '"' + value.replace(r_item, function (a_item) {
                    var c_item = m_item[a_item];
                    if (c_item) {
                        return c_item;
                    }
                    c_item = a_item.charCodeAt();
                    return '\\u00' + Math.floor(c_item / 16).toString(16) + (c_item % 16).toString(16);
                }) + '"' : '"' + value + '"';
            case 'number':
                return isFinite(value) ? String(value) : 'null';
            case 'boolean':
            case 'null':
                return String(value);
            case 'object':
                if (!value) {
                    return 'null';
                }
                if (typeof value.toJSON === 'function') {
                    return stringify_item(value.toJSON());
                }
                a_item = [];
                if (typeof value.length === 'number' && !(value.propertyIsEnumerable('length'))) {
                    l = value.length;
                    for (i = 0; i < l; i += 1) {
                        a_item.push(stringify_item(value[i], whitelist) || 'null');
                    }
                    return '[' + a_item.join(',') + ']';
                }
                if (whitelist) {
                    l_item = whitelist.length;
                    for (i = 0; i < l; i += 1) {
                        k_item = whitelist[i];
                        if (typeof k_item === 'string') {
                            v_item = stringify_item(value[k_item], whitelist);
                            if (v_item) {
                                a_item.push(stringify_item(k_item) + ':' + v_item);
                            }
                        }
                    }
                } else {
                    for (k_item in value) {
                        if (typeof k_item === 'string') {
                            v_item = stringify_item(value[k_item], whitelist);
                            if (v_item) {
                                a_item.push(stringify_item(k_item) + ':' + v_item);
                            }
                        }
                    }
                }
                return '{' + a_item.join(',') + '}';
            }
        }
        return {
            stringify_item: stringify_item,
            parse: function (text, filter) {
                var j_item;

                function walk_item(k_item, v_item) {
                    var i_item, n_item;
                    if (v_item && typeof v_item === 'object') {
                        for (i_item in v_item) {
                            if (Object.prototype.hasOwnProperty.apply(v_item, [i_item])) {
                                n_item = walk_item(i_item, v[i_item]);
                                if (n_item !== undefined) {
                                    v[i_item] = n_item;
                                }
                            }
                        }
                    }
                    return filter(k_item, v_item);
                }
                if (/^[\],:{}\s]*$/.test(text.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                    j_item = eval('(' + text + ')');
                    return typeof filter === 'function' ? walk_item('', j_item) : j_item;
                }
                throw new SyntaxError('parseJSON');
            }
        };
    }();
}