/**
 * jPicTag - jQuery Image tagger
 *
 * @author Henrique Barroso <info@henriquebarroso.com>
 * @version 1.1
 * @copyright Copyright (c) 2010, Henrique Barroso
 */
(function (a) {
    a.fn.tag_people = function (f) {
        var i = this,
            g = 0,
            e = 0,
            k = 0,
            j = 0,
            h = 0,
            o = false,
            l = 0,
            b = 0,
            m = 0,
            n = 0,
            c = 0;
        var d = {
            tag_by: "",
            photo_role: "",
            tag_time: "",
            tags_array: new Array,
            friends_list: new Array,
            get_url: "",
            post_url: "",
            form: "#form1",
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
            pictag_enable_people: "#tag_people",
            pictag_block: '<div id="pictag_content"><div id="pictag"></div></div>',
            labels_block: "#labels",
            pictag_done: '<div id="pictag_done">Click on peoples faces to tag them, when you finish click here: <input type="button" name="pictag_disable" value="Done Tagging" id="pictag_disable"/></div>',
            pictag_box: '<div id="pictag_box"><div class="square_box"></div></div>',
            pictag_caption: '<div id="pictag_caption">				<div class="pictag_space">					Type any name or tag:<br>					<input type="text" name="pictag_tag_caption" value="" id="pictag_tag_caption"><br>				</div>				<div class="pictag_list"></div>				<div class="pictag_space">					<input type="button" name="pictag_button_tag" value="Tag" id="pictag_button_tag">  					<input type="button" name="pictag_button_cancel" value="Cancel" id="pictag_button_cancel"> 				</div>			</div>',
            pictag_text: '<div class="label" id="pictag_text"><span class="label_text"></span></div>'
        };
        if (f) {
            a.extend(d, f)
        }
        a(window).load(function (G) {
            load_pictag_elements();
            post_tagging_operation();
            a(i).bind("click", function (I) {
                click_img_for_tagging(this, I)
            });
            a("#pictag_box").bind("click", function (I) {
                click_img_for_tagging(d.pictag, I)
            });
            a("#pictag_caption").click(function () {
                return false
            });
            a("#pictag_button_cancel").click(function () {
                close_and_empty_tag_list();
                return false
            });
            a(d.pictag_enable_people).click(function () {
                prepare_img_for_tagging();
                return false
            });
            a("#pictag_disable").click(function () {
                close_and_empty_tag_list();
                done_tagging();
                add_tag_to_labels();
                if (d.auto_save) {
                    send_tags_to_server(d.post_url)
                }
                return false
            });
            a("#pictag_button_tag").click(function () {
                process_selected_tag_item(a("#pictag_tag_caption").val())
            });
            a("#pictag_tag_caption").keyup(function (I) {
                if (I.keyCode == 13 || I.keyCode == 10) {
                    process_selected_tag_item(a("#pictag_tag_caption").val())
                } else {
                    process_typed_tag_text(a("#pictag_tag_caption").val())
                }
            });
            a(".list_item").click(function () {
                process_selected_tag_item(a("."+d.type+" #name_item_" + a(this).val() + " > label").html());
                close_and_empty_tag_list()
            });
            a(".tag_label").live("mouseover", function () {
                var I = a(this).attr("id").split("_");
                c = I[1];
                show_pictag_text(d.tags_array[I[1]].label, d.tags_array[I[1]].x, d.tags_array[I[1]].y + d.tags_array[I[1]].h)
            });
            a(".tag_label").live("mouseleave", function () {
                a("#pictag_text > .label_text").hide()
            });
            a(".tag_label_hover").live("mouseover", function () {
                var I = a(this).attr("id").split("_");
                c = I[2];
                show_pictag_text(d.tags_array[I[2]].label, d.tags_array[I[2]].x, d.tags_array[I[2]].y + d.tags_array[I[2]].h)
            });
            a(".tag_label_hover").live("mouseleave", function () {
                var I = a(this).attr("id").split("_");
                a("#pictag_text > .label_text").hide()
            });
            a(".pictag_remove").live("click", function () {
                var I = a(this).attr("id").split("_");
                remove_tag_from_labels(I[2]);
                add_tag_to_labels();
                if (d.auto_save) {
                    send_tags_to_server(d.post_url)
                }
                return false
            });
            a("#pictag_text").live("mouseenter", function () {
                show_pictag_text(d.tags_array[c].label, d.tags_array[c].x, d.tags_array[c].y + d.tags_array[c].h)
            });
            a("#pictag_text").live("mouseleave", function () {
                a("#label_" + c).removeClass("square_box");
                a("#pictag_text > .label_text").hide();
                c = 0
            });

            function load_pictag_elements() {
				if ($(d.pictag_content).length <= 0) {
					a(i).wrap(d.pictag_block);
					a(d.pictag_content).prepend(d.pictag_done);
					a(d.pictag_content).append(d.pictag_box);
					a(d.pictag).prepend(d.pictag_text);
					if (!d.visual_effects) {
						a.fx.off = !a.fx.off
					}
					if (d.edit_mode == true) {
						a("#pictag_box").append(d.pictag_caption)
					}
					if (d.hide_list == true) {
						a(".pictag_list").remove()
					}
					a(".square_box").css({
						width: d.square_box_size,
						height: d.square_box_size
					});
					set_tag_size();
					k = a(d.pictag + " > img").width();
					j = a(d.pictag + " > img").height();
					a(d.pictag).css({
						width: k,
						height: j
					})
				}
				if (d.auto_load) {
					get_tags_from_server(d.get_url);
				}
            }
            function post_tagging_operation() {
                a(".tag_label").remove();
                done_tagging();
                add_tag_to_labels();
                a(d.labels_block).html(draw_labels());
                if (d.hide_list == false) {
                    a(".pictag_list").html(draw_tag_list())
                }
            }
            function click_img_for_tagging(I, J) {
                if (!o || !d.edit_mode) {
                    return false
                }
                a("#pictag_box").fadeIn();
                populate_tag_list();
                if (d.resizable) {
                    set_tag_size()
                }
                h = a(I).offset();
                g = J.pageX - h.left - (b / 2);
                e = J.pageY - h.top - (l / 2);
                if ((g + n) > k) {
                    g = k - n
                }
                if ((e + m) > j) {
                    e = j - m
                }
                if (g < 0) {
                    g = 0
                }
                if (e < 0) {
                    e = 0
                }
                if (d.resizable) {
                    a(".square_box").resizable({
                        maxWidth: (k - g - (n - b)),
                        maxHeight: (j - e - (m - l))
                    })
                }
                a(d.pictag).append(a("#pictag_box"));
                a("#pictag_box").css({
                    left: g,
                    top: e
                });
                setTimeout(function () {
                    a("#pictag_tag_caption").focus()
                }, 500);
                return true
            }
            function close_and_empty_tag_list() {
                a("#pictag_box").fadeOut();
                a("#pictag_tag_caption").val("")
            }
            function prepare_img_for_tagging() {
                if (!d.edit_mode) {
                    return false
                }
				post_tagging_operation();
                o = true;
                a(".tag_label").remove();
                a(d.pictag).css("cursor", "Crosshair");
                a("#pictag_done").show();
                return true
            }
            function done_tagging() {
                o = false;
                a(d.pictag).css("cursor", "default");
                a("#pictag_done").hide();
                a("#pictag_box").fadeOut()
            }
            function add_tag_to_labels() {
                var J = null,
                    L = null;
                for (var K = 0; K < d.tags_array.length; K++) {
                    size = d.tags_array[K].h + d.tags_array[K].w;
                    for (var I = 0; I < d.tags_array.length; I++) {
                        J = d.tags_array[K];
                        L = d.tags_array[I];
                        if ((d.tags_array[I].h + d.tags_array[I].w) > size) {
                            d.tags_array[I] = J;
                            d.tags_array[K] = L;
                            continue
                        }
                    }
                }
                for (var M = 0; M < d.tags_array.length; M++) {
                    a(d.pictag).append("<div class='tag_label' id='label_" + M + "' style='z-index:" + (d.tags_array.length - M) + ";width:" + (d.tags_array[M].w) + "px;height:" + (d.tags_array[M].h) + "px;left:" + (d.tags_array[M].x) + "px;top: " + (d.tags_array[M].y) + "px;'></div>")
                }
                a(d.form).html(populate_form_with_tagdata_for_submit());
                a(d.labels_block).html(draw_labels());
                return true
            }
            function process_selected_tag_item(I) {
                var J = null;
                string_list = a("#name_item_" + a("."+d.type+" .list_item:visible:checked").val() + " > label").html();
                if (string_list != null) {
                    I = string_list;
                    J = a("."+d.type+" .list_item:visible:checked").val()
                }
                if (I == "") {
                    alert("You have to type a name to create a tag.");
                    return false
                }
                if (J == null) {
                    J = 0
                }
                if (!d.allow_html) {
                    I = I.replace(/(<([^>]+)>)/ig, "")
                }
                b = a(".square_box").innerWidth();
                l = a(".square_box").innerHeight();
                
                d.tags_array.push({
                    label: I,
                    x: g,
                    y: e,
                    h: l,
                    w: b,
                    id: J,
                    tag_by: d.tag_by,
                    tag_time: d.tag_time
                });
                close_and_empty_tag_list();
                return true
            }
            function remove_tag_from_labels(I) {
                d.tags_array.splice(I, 1);
                a("#label_" + I).remove();
                post_tagging_operation();
                return true
            }
            function populate_form_with_tagdata_for_submit() {
                var I = "";
                for (var J = d.tags_array.length - 1; J >= 0; J--) {
                    I += '<input type="hidden" name="pictag[' + J + '][label]" value="' + escape(d.tags_array[J].label) + '" >';
                    I += '<input type="hidden" name="pictag[' + J + '][x]" value="' + d.tags_array[J].x + '" >';
                    I += '<input type="hidden" name="pictag[' + J + '][y]" value="' + d.tags_array[J].y + '" >';
                    I += '<input type="hidden" name="pictag[' + J + '][h]" value="' + d.tags_array[J].h + '" >';
                    I += '<input type="hidden" name="pictag[' + J + '][w]" value="' + d.tags_array[J].w + '" >';
                    I += '<input type="hidden" name="pictag[' + J + '][id]" value="' + d.tags_array[J].id + '" >';
                    I += '<input type="hidden" name="pictag[' + J + '][tag_by]" value="' + d.tags_array[J].tag_by + '" >'
                }
                I += '<div class="hidden"><input type="hidden" value="'+$('input[name=ci_csrf_token]').val()+'" name="ci_csrf_token"></div>';
                return I
            }
            function draw_labels() {
                var I = "",
                    K = "";
                if (d.tags_array.length == 0) {
                    return "none"
                }
                for (var J = d.tags_array.length - 1; J >= 0; J--) {
                    I += '<span class="label" id="tag_' + J + '">';
                    if (d.tags_array[J].id != 0) {
                        I += '<a href="' + d.label_base_url + d.tags_array[J].id + '">'
                    }
                    I += '<span class="tag_label_hover" id="tag_name_' + J + '">' + unescape(d.tags_array[J].label) + "</span>";
                    if (d.tags_array[J].id != 0) {
                        I += "</a>"
                    }
                    if (d.photo_role == 1 || d.tags_array[J].id == d.tag_by || d.tags_array[J].tag_by == d.tag_by) {
                        I += '&nbsp;<a href="#" class="pictag_remove" class="tag_label_remove"  id="tag_remove_' + J + '">' + d.remove + "</a>"
                    }
                    I += "</span>"
                }
                return I
            }
            function draw_tag_list() {
            	//alert(JSON.stringify(d.friends_list, ""));
                var I = "";
                for (var J = d.friends_list.length - 1; J >= 0; J--) {
                    I += '<span id="name_item_' + d.friends_list[J].id + '" class="'+d.type+'"><input type="radio" name="list_item" id="list_item" class="list_item" value="' + d.friends_list[J].id + '"> <label>' + d.friends_list[J].name + "</label></span>"
                }
                return I
            }
            function process_typed_tag_text(I) {
                var K = "",
                    J = 0;
                I = I.toUpperCase();
                jQuery.each(a("."+d.type+" .list_item"), function (L) {
                    K = a("."+d.type+" #name_item_" + a(this).val() + " > label").html();
                    K = K.toUpperCase();
                    if (K.match(I)) {
                        a("."+d.type+" #name_item_" + a(this).val()).show();
                        J++
                    } else {
                        a("."+d.type+" #name_item_" + a(this).val()).hide()
                    }
                    a(this).attr("checked", false)
                });
                if (J == 1) {
                    a("."+d.type+" .list_item:visible").attr("checked", "true")
                }
            }
            function populate_tag_list() {
                var I = null;
                a("#pictag_tag_caption").val("");
                jQuery.each(a("."+d.type+" .list_item"), function (J) {
                    a(this).attr("checked", false);
                    I = a("."+d.type+" #name_item_" + a(this).val()).show()
                })
            }
            function get_tags_from_server(I) {
                a.ajax({
                    type: "GET",
                    url: I,
                    async: false,
                    dataType: d.format,
                    success: function (J) {
                    	//alert(JSON.stringify(J.friends, ""));
                        if (J.tags != null) {
                            d.tags_array = J.tags
                        }
                        if (J.friends != null) {
                            d.friends_list = J.friends
                        }
                        return true
                    }
                })
            }
            function send_tags_to_server(I) {
                var J = null;
                if (d.format == "json") {
                    J = JSON.stringify(d.tags_array, "")
                } else {
                    J = a(d.form).serializeArray()
                }
                a.ajax({
                    type: "POST",
                    url: I,
                    dataType: d.format,
                    data: ({
                        tag_people: J, ci_csrf_token: $("input[name=ci_csrf_token]").val()
                    })
                })
            }
            function set_tag_size() {
                b = a(".square_box").innerWidth();
                l = a(".square_box").innerHeight();
                n = a(".square_box").outerWidth();
                m = a(".square_box").outerHeight()
            }
            function show_pictag_text(K, J, I) {
                a("#pictag_text > .label_text").html(unescape(K));
                a("#pictag_text").css("top", I + "px");
                a("#pictag_text").css("left", J + "px");
                a("#pictag_text > .label_text").show()
            }
        })
    }
})(jQuery);
if (this.JSON) {
    JSON = function () {
        function f(n) {
            return n < 10 ? '0' + n : n;
        }
        Date.prototype.toJSON = function () {
            return this.getUTCFullYear() + '-' + f(this.getUTCMonth() + 1) + '-' + f(this.getUTCDate()) + 'T' + f(this.getUTCHours()) + ':' + f(this.getUTCMinutes()) + ':' + f(this.getUTCSeconds()) + 'Z';
        };
        var m = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"': '\\"',
            '\\': '\\\\'
        };

        function stringify(value, whitelist) {
            var a, i, k, l, r = /["\\\x00-\x1f\x7f-\x9f]/g,
                v;
            switch (typeof value) {
            case 'string':
                return r.test(value) ? '"' + value.replace(r, function (a) {
                    var c = m[a];
                    if (c) {
                        return c;
                    }
                    c = a.charCodeAt();
                    return '\\u00' + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
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
                    return stringify(value.toJSON());
                }
                a = [];
                if (typeof value.length === 'number' && !(value.propertyIsEnumerable('length'))) {
                    l = value.length;
                    for (i = 0; i < l; i += 1) {
                        a.push(stringify(value[i], whitelist) || 'null');
                    }
                    return '[' + a.join(',') + ']';
                }
                if (whitelist) {
                    l = whitelist.length;
                    for (i = 0; i < l; i += 1) {
                        k = whitelist[i];
                        if (typeof k === 'string') {
                            v = stringify(value[k], whitelist);
                            if (v) {
                                a.push(stringify(k) + ':' + v);
                            }
                        }
                    }
                } else {
                    for (k in value) {
                        if (typeof k === 'string') {
                            v = stringify(value[k], whitelist);
                            if (v) {
                                a.push(stringify(k) + ':' + v);
                            }
                        }
                    }
                }
                return '{' + a.join(',') + '}';
            }
        }
        return {
            stringify: stringify,
            parse: function (text, filter) {
                var j;

                function walk(k, v) {
                    var i, n;
                    if (v && typeof v === 'object') {
                        for (i in v) {
                            if (Object.prototype.hasOwnProperty.apply(v, [i])) {
                                n = walk(i, v[i]);
                                if (n !== undefined) {
                                    v[i] = n;
                                }
                            }
                        }
                    }
                    return filter(k, v);
                }
                if (/^[\],:{}\s]*$/.test(text.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                    j = eval('(' + text + ')');
                    return typeof filter === 'function' ? walk('', j) : j;
                }
                throw new SyntaxError('parseJSON');
            }
        };
    }();
}