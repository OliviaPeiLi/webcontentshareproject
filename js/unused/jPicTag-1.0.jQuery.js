/**
 * jPicTag - jQuery Image tagger
 *
 * @author Henrique Barroso <info@henriquebarroso.com>
 * @version 1.1
 * @copyright Copyright (c) 2010, Henrique Barroso
 */
(function (a) {
    a.fn.tag_items = function () {
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
            orig_img: a('.galleria-image img'),
            photo_role: "",
            tag_time: "",
            tags_array: new Array,
            friends_list: new Array,
            items_list: new Array,
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
            pictag_enable_items: "#tag_items",
            pictag_block: '<div id="pictag_content"><div id="pictag"></div></div>',
            labels_block: "#labels",
            tags_block: "#tags",
            pictag_done: '<div id="pictag_done">Click on peoples faces or items to tag them, when you finish click here: <input type="button" name="pictag_disable" value="Done Tagging" id="pictag_disable"/></div>',
            pictag_box: '<div id="pictag_box"><div class="square_box"></div></div>',
            pictag_caption: '<div id="pictag_caption">				<div class="pictag_space">					Type any name or tag:<br>					<input type="text" name="pictag_tag_caption" value="" id="pictag_tag_caption"><br>				</div>				<div class="pictag_list"></div>				<div class="pictag_space">					<input type="button" name="pictag_button_tag" value="Tag" id="pictag_button_tag">  					<input type="button" name="pictag_button_cancel" value="Cancel" id="pictag_button_cancel"> 				</div>			</div>',
            pictag_text: '<div class="label" id="pictag_text"><span class="label_text"></span></div>'
        };
        function destroy() {
            a(i).unbind();
            a("#pictag_box").unbind();
            a("#pictag_caption").unbind();
            a("#pictag_button_cancel").unbind();
            a(d.pictag_enable_items).unbind();
            a("#pictag_disable").unbind();
            a("#pictag_button_tag").unbind();
            a("#pictag_tag_caption").unbind();
            a(".list_item").die();
            a(".tag_label").die();
            a(".tag_label_hover").die();
            a(".pictag_remove").die();
            a("#pictag_text").die();
            a("#load_labels").die();

            a("#draw_labels").die();
        }

        function init(f) {
            if (f) {
                a.extend(d, f)
            }

            load_pictag_elements();
            post_tagging_operation();

            a("#load_labels").live('click',function() {
                get_tags_from_server(d.get_url);
                add_tag_to_labels();
                a(d.labels_block).html(draw_labels());
            });

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
            a(d.pictag_enable_items).click(function () {
                k = d.orig_img.width();
                j = d.orig_img.height();
                a(d.pictag).css({
                    width: k,
                    height: j
                })
                prepare_img_for_tagging();
                return false
            });
            a("#pictag_disable").click(function () {
                close_and_empty_tag_list();
                done_tagging();
                add_tag_to_labels();
                close_tagger();
                if (d.auto_save) {
                    send_tags_to_server(d.post_url)
                }
                return false
            });
            a("#pictag_button_tag").click(function () {
                process_selected_tag_item(a("#pictag_tag_caption").val())
            });
            a("#pictag_tag_caption").live('keyup', function (I) {
                if (I.keyCode == 13 || I.keyCode == 10) {
                    process_selected_tag_item(a("#pictag_tag_caption").val())
                } else {
                    process_typed_tag_text(a("#pictag_tag_caption").val())
                }
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
            a('.list_item').click(function () {
                alert('tag clicked');
                process_selected_tag_item(a(this).parent().find('label').html());
                close_and_empty_tag_list();
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
                a("#pictag_tag_caption").val("");
                populate_tag_list();
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
            function close_tagger() {
                $('#container_image').hide();
                $('#galleria').show();
                $('#tag_items_2').show();

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
                    a(d.tags_block).append("<div class='tag_label' id='label_" + M + "' style='display: none; z-index:" + (d.tags_array.length - M) + ";left:" + (d.tags_array[M].x) + "px;top: " + (d.tags_array[M].y) + "px;'><div class='tag_label_square' style='width:" + (d.tags_array[M].w) + "px;height:" + (d.tags_array[M].h) + "px;'></div><div class='tag_label_text' style='display: none'>"+d.tags_array[M].label+"</div></div>")
                }
                a(d.form).html(populate_form_with_tagdata_for_submit());
                return true
            }
            function process_selected_tag_item(I) {
                var J = null;
                var item_type = null;
                var sel_item = a(".taglist_entry .list_item:visible:checked");
                string_list = sel_item.parent().find('label').html();
                item_type = (sel_item.parent().hasClass('user')) ? 'user' : 'page';
                if (string_list != null) {
                    I = string_list;
                    alert('processing selected tag item: '+I);
                    J = sel_item.val()
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
                    type: item_type,
                    tag_by: d.tag_by,
                    tag_time: d.tag_time
                });
                close_and_empty_tag_list();
                sel_item.parent().remove();
                a(d.labels_block).html(draw_labels());
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
                    I += '<div class="label inlinediv" id="tag_' + J + '">';
                    if (d.tags_array[J].id != 0) {
                        I += '<a href="' + d.label_base_url + d.tags_array[J].id + '">'
                    }
                    I += '<div class="tag_label_hover inlinediv" id="tag_name_' + J + '">' + unescape(d.tags_array[J].label) + "</div>";
                    if (d.tags_array[J].id != 0) {
                        I += "</a>"
                    }
                    if (d.photo_role == 1 || d.tags_array[J].id == d.tag_by || d.tags_array[J].tag_by == d.tag_by) {
                        I += '&nbsp;<a href="#" class="pictag_remove" class="tag_label_remove"  id="tag_remove_' + J + '">' + d.remove + "</a>"
                    }
                    I += "</div>"
                }
                //console.log(I);
                return I
            }
            function draw_tag_list() {
                var I = "";
                var first_user = false;
                for (var J = d.items_list.length - 1; J >= 0; J--) {
                    var is_page = (d.items_list[J].type === 'page') ? true : false;
                    var item_type = (is_page) ? 'interest' : '';
                    var addtl_label = (jQuery.trim(item_type) !== '') ? '('+item_type+')' : '';
                    if (!is_page && !first_user) {
                        I += '<span id="pageuser_divider" class="'+d.items_list[J].type+'"><label>Friends</label></span>';
                        first_user = true;
                    }
                    I += '<span id="name_item_' + J + '" class="taglist_entry '+d.items_list[J].type+'"><input type="radio" name="list_item" class="list_item tag_results_list_item" value="' + d.items_list[J].id + '"> <label>' + d.items_list[J].name + " "+addtl_label+"</label></span>"
                }
                return I
            }
            function process_typed_tag_text(I) {
                var K = "",
                    J = 0;
                I = I.toUpperCase();
                jQuery.each(a(".taglist_entry"), function (L) {
                    K = a(this).find('label').html();
                    K = K.toUpperCase();
                    if (K.match(I)) {
                        a(this).show();
                        J++
                    } else {
                        a(this).hide()
                    }
                    a(this).attr("checked", false)
                });
                if (J == 1) {
                    a(".taglist_entry .list_item:visible").attr("checked", "true")
                    a(".taglist_entry .list_item:visible").attr("checked", "true")
                }
            }
            function populate_tag_list() {
                var I = null;
                a("#pictag_tag_caption").val("");
                jQuery.each(a(".taglist_entry .list_item"), function (J) {
                    a(this).attr("checked", false);
                    I = a(this).show();
                })
            }
            function get_tags_from_server(I) {
                a.ajax({
                    type: "GET",
                    url: I,
                    async: false,
                    dataType: d.format,
                    success: function (J) {
                        if (J.tags != null) {
                            d.tags_array = J.tags
                        }
                        if (J.friends != null) {
                            d.friends_list = J.friends
                        }
                        if (J.items != null) {
                            //alert(JSON.stringify(J.items,""));
                            d.items_list = J.items
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
                        tag_items: J, ci_csrf_token: $("input[name=ci_csrf_token]").val()
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

        }
        return {
            destroy: function() {
                destroy();
            },
            init: function(f) {
                init(f);
            }
        }
    }
})(jQuery);
