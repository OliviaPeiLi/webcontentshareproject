/* *********************************************************
 * Interest Page
 *  All JS on interest page
 *   topics, graph init, tabs, share link postbox/fetch buttons
 *
 * ******************************************************* */

//Removed - "postbox/jquery_postbox",
define(["common/tabs","common/tile", 'plugins/jquery.watermarkinput', 'jquery'], function(tb,t) {

	console.log('laoded page.js');

	$(document).ready(function() {
	    //t1 = new Postbox('#page_wrapper', $('#page_wrapper .page_add_post'), false);
		$('#first_visit').click(); //opens the walkthrough if avaliable on 1st visit
	
		$( "#sortable" ).sortable({
			handle : '.ui-state-default',
			update : function () {
			//var order = $('#sortable').sortable('serialize');
			order = [];
			$('#sortable').children('div').each(function(idx, elm) {
			order.push(elm.id.split('_')[1])
			});
			$.post('/sort_components/'+php.segment3+'>/'+php.segment4+'/', { ci_csrf_token: $("input[name=ci_csrf_token]").val(),'order[]': order});
	
			}
		});
	
	});
	
	
/*Similarity Match Bar*/

function get_page_SimilarityMatch() { 
	for (user in php.record_out){
		//alert('similarity match');
		var val=$('#similarityScore_'+php.record_out[user]['user_id']).text(); 
		if(val>=75){
			$('#minisimilaritybar_'+php.record_out[user]['user_id']).removeClass().addClass('q75').animate({width:val+'%'},1000);
		}
		else if(val>=50){
			$('#minisimilaritybar_'+php.record_out[user]['user_id']).removeClass().addClass('q50').animate({width:val+'%'},1000);
		}
		else if(val>=25){
			$('#minisimilaritybar_'+php.record_out[user]['user_id']).removeClass().addClass('q25').animate({width:val+'%'},1000);
		}
		else{
			$('#minisimilaritybar_'+php.record_out[user]['user_id']).removeClass().addClass('q1').animate({width:val+'%'},1000);
		}
	}
}
get_page_SimilarityMatch();


	$(function() {
		//Logic for initializing the interest graph
		var win_w = $(window).width()-100;
		var win_h = $(window).height()-100;
		$('#graph').dialog({
			modal: true, 
			draggable: false,
			resizable: false,
			//width: 900,
			//height: 670,
			width: win_w,
			height: win_h,
			autoOpen: false,
			open: function(event, ui) {
	        	$('.ui-widget-overlay').click(function() {
	        		//console.log($(this).parent().find('.ui-dialog-content#graph'));
	        		$(this).parent().find('.ui-dialog-content#graph').dialog('close');
	        	});
	        },
			close: function() {}
		});
		
		$('#open_graph').click(function() { 
			$('#graph').dialog('open');
			$('#graph').html('<iframe src="/get_page_graph/'+php.page_id+'" width="100%" height="100%" align="center" frameborder="0">Error</iframe>');
		});
	});




/*
	function add_topic(item) {
        $('.postboxtoolbar.postbox_topics').append('<input type="hidden" name="topic_id['+item.id+']" class="ac_topic_id" id="topic_id_'+item.id+'" value="'+item.id+'">');
        $('.postboxtoolbar.postbox_topics').append('<input type="hidden" name="topic_name['+item.id+']" class="ac_topic_name" id="topic_name_'+item.id+'" value="'+item.name+'">');
	}
	function del_topic(item) {
        $('.postboxtoolbar.postbox_topics').find('#topic_id_'+item.id).remove();
        $('.postboxtoolbar.postbox_topics').find('#topic_name_'+item.id).remove();
	}
*/

	//Logic for displaying hidden tabs that become visible by clicking on a 'More…' tab
    function open_tab_menu() {
    	//console.log('open_tab_menu');
        var l_left = $('#more_tabs').offset().left;
        var l_top = $('#more_tabs').position().top+$('#more_tabs').height();
        //var l_top = $('#more_loops').offset().top+$('#more_loops').height()+10;
        //alert(l_left+' '+l_top);
        $('#more_tabs_list').css('left',l_left+'px').css('top',l_top+'px');
        //console.log('opening');
        $('#more_tabs_list').show('fade');
        //view_hide('more_tabs_list');
        return false;
    }
    

	var old_val = ''; //For postbox
	
	//For topics
	/**
	This function recalculates posiiton of topics to determine whether a 'more' button and/or edit buttons should be visible
	and their placement in the list of topics on top of interest page.
	*/
	function recalc_topics(action) {
		//console.log('recalculating topics');
        var excluded_tabs = ['topics_more', 'topics_edit'];
        var tot_width_excluded = 0;
        for (et in excluded_tabs) {
        	tot_width_excluded += $('#'+excluded_tabs[et]).outerWidth(true);
        }
        var new_tab_width = 0;
        var more_tab_width = 0;
        var tabs_width_limit = $('#page_topics').width();
        	var is_less = $('#topics_more').hasClass('topics_less');
        	//console.log($('#topics_more'));
        	if (!is_less) {
		        $('#topics_more').removeClass('topics_more').removeClass('topics_less');
		        var collective_w = 0;
		        var stop = false;
				$('#page_topics').find('li').each(function() {
			    	if (jQuery.inArray($(this).attr('id'), excluded_tabs) < 0) {
			    		var old_collective_w = collective_w;
				        collective_w += ($(this).outerWidth(true));
				        //console.log(collective_w+', '+tabs_width_limit+', '+tot_width_excluded+': '+$(this).text());

				        if (collective_w+tot_width_excluded > tabs_width_limit-20 && !stop) {
				        //if (!stop) {
				        	//console.log('inserting more');
	
				            //if ((old_collective_w + $('#topics_more').outerWidth(true)) > tabs_width_limit) {
				            if ((old_collective_w + tot_width_excluded) > tabs_width_limit-20) {
				            	//console.log($(this).prev());
				            	
						        for (et in excluded_tabs) {
						        	$(this).prev().before($('#'+excluded_tabs[et]));
						        }
						        
				            	//$(this).prev().before($('#topics_more'));
				            } else {
						        for (et in excluded_tabs) {
						        	$(this).before($('#'+excluded_tabs[et]));
						        }
				            	//$(this).before($('#topics_more'));
				            }
					            
				            
				            $('#topics_more').addClass('topics_more').show('fade');
				            stop = true;
				        }
			        }
			    });
		    }

	    //}


	    //tab_container.show();
    }


    $(function() {
    
		/**
		Functions below deal with initializing tabs for calculation of proper placement and delegation to hidden tabs
		*/
        var excluded_tabs = ['more_tabs'];
        var new_tab_width = 0;
        var more_tab_width = 0;
        if ($('#add_new_tab').length > 0 ) {
        	new_tab_width = $('#add_new_tab').width();
        	excluded_tabs.push('add_new_tab')
        }
        var tabs_width_limit = $('#page_tabs_row > ul').width()-new_tab_width-$('#more_tabs').width()-30;
        tb.init_tabs($('#more_tabs'), $('#page_tabs_row > ul'), $('#more_tabs_list'), tabs_width_limit, excluded_tabs);

		/**
		functions below deal with Topics (More/Less buttons, edit button)
		*/
		var topics_width = $('#page_misc_info').width() - $('#page_options_button').outerWidth()-10;
		$('#page_topics').width(topics_width);
	    recalc_topics();
	    $('#topics_more.topics_more').live('click', function() {
	    	$('#page_topics').css('height','auto')
	    	$(this).removeClass('topics_more').addClass('topics_less');
	    	$(this).find('a').text('Less...');
	    	$(this).appendTo('#page_topics');
	    	$('#topics_edit').appendTo('#page_topics');
	    	return false;
	    });
	    $('#topics_more.topics_less').live('click', function() {
	    	$(this).removeClass('topics_less').addClass('topics_more').hide();;
	    	$(this).find('a').text('More...');
	    	recalc_topics();
	    	$('#page_topics').css('height',$('#page_topics').css('min-height'));
	    	return false;
	    });
	    $('#topics_edit').live('click', function() {
	    	//$('#select_page_options').click();
	    	$('#add_page_topic').click();
	    	return false;
	    });


		//Highlight proper tab
		$('.page_tab').removeClass('active_tab');
		var tab = php.segment1;
		var cust_tab = php.segment4;
		//alert(tab);
		switch(tab) {
			case 'events':
				$('#events_tab').addClass('active_tab');
				break;
			case 'page_info':
				$('#info_tab').addClass('active_tab');
				break;
			case 'photo_albums':
				$('#photos_tab').addClass('active_tab');
				break;
			case 'videos':
				$('#videos_tab').addClass('active_tab');
				break;
			case 'pr_page':
				$('#pr_tab').addClass('active_tab');
				break;
			case 'interest_fans':
				//do nothing
				break;
			default:
				//console.log(cust_tab);
				if (cust_tab > 0) {
					$('.page_tab[rel='+cust_tab+']').addClass('active_tab');
				} else {
					$('#wall_tab').addClass('active_tab');
				}
		}

		//Logic for the Add Topic dropdown to reveal other page options (such as alias, merge topics, lock topics)
		$(".page_options_first")
			.button()
			.next()
				.button( {
					text: false,
					icons: {
						primary: "ui-icon-triangle-1-s"
					}
				})
				.click(function() {
					var menu = $(this).parent().next().show().position({
						my: "right top",
						at: "right bottom",
						of: this
					});
					$(document).one("click", function() {
						menu.hide();
					});
					return false;
				})
				.parent()
					.buttonset()
				.next()
					.hide()
					.menu();

/* LINK SCRAPER */

	if (typeof document.createElement('textarea').placeholder == 'undefined') { //browser support check
		$('*[placeholder]').each(function() {
			if (!$(this).val()) $(this).val($(this).attr('placeholder'));
			$(this).focus(function() {
				if ($(this).val() == $(this).attr('placeholder')) $(this).val('');
			}).blur(function() {
				if ($(this).val() == '') $(this).val($(this).attr('placeholder'));
			});
		});
	}

        $('#add_people_to_interest #skip').live('click', function() {
            $('#add_people_to_interest').dialog('close');
            return false;
        });


		$('#interests_main textarea.inlinepostbox').focus(function() {
			$(this).data('firstclick',true);
		});
		
});

//Activates scraper on url paste to postbox, no need to press fetch.
$('#page_postbox_url').live('paste', function() {
	setTimeout(function() {$('#fetch_media').click();},100);
});


/*
        $('#page_options #add_page').live('click',function() {
            $.get('/add_page/<?=$page_id?>', function(data) {
                $('#add_people_to_interest').dialog({
                    width: 550,
                    height: 250,
                    modal: true,
                    close: function () {
                    	location.reload();
                    }
                });
                $('.autocomplete_input .form_field input').blur();
                
            });
            $(this).remove();
            return false;
        });
*/



    $('#add_people_to_interest #skip').live('click', function() {
        $('#add_people_to_interest').dialog('close');
        return false;
    });


	/**
	Logic for handling different Topic actions, such as add new topic, lock topics, 
	*/
    $('.page_topic .remove_icon').live('click',function() {
        var item = $(this).closest('li');
        var url = $(this).attr('href');
        $.get(url, function(data) {
            item.hide('fade').remove();
	    	//$('#topics_more').removeClass('topics_less').removeClass('topics_more').addClass('topics_more').hide();;
	    	//$('#topics_more').find('a').text('More...');
	    	recalc_topics();
	    	//$('#page_topics').css('height',$('#page_topics').css('min-height'));
        });
        return false;
    });

    $('#page_new_topic_submit').live('click', function() {
        var form = $(this).closest('form');
        var url = form.attr('action');
        var topics = $('.autocomplete_input #load_topics').tokenInput('get');
        //prepare_autocomplete('#new_topic','#load_topics','#topic_names');
        var data = {
            topics: JSON.stringify(topics),
            ci_csrf_token: $("input[name=ci_csrf_token]").val()
        };
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(msg) {
                //$('#new_topic').hide('blind');
                msg = $(msg);
                msg.find('li').hide();
                $('#page_topics #topics_more').before(msg);
                msg.find('li').show('fade');
                $('.autocomplete_input #load_topics').tokenInput('clear');
                recalc_topics('add');
            }
        });
        return false;
    });

    // For displaying the Add topics autocomplete field on page
    $('#add_page_topic').live('click', function() {
        if ($('#new_topic').css('display') === 'none') {
        	$('.page_options_container').hide();
        	$('#page_topics').css('height','auto');
        	$('#page_topics').append($('#topics_more'));
        	$('#topics_more').removeClass('topics_more').addClass('topics_less');
        	$('#topics_more').find('a').text('Less...');
        	$('#topics_edit').appendTo('#page_topics');
        	recalc_topics();
            $('#new_topic').show('blind');
        } else {
        	recalc_topics();
            $('#new_topic').hide('blind');
        }
    });
    
    //Lock Topics
    $('#lock_topics').live('click', function() {
    	location.href = $(this).attr('rel');
    	return false;
    });
    
    $('#add_page_thread').live('click', function() {
        if ($('#new_thread').css('display') === 'none')
            $('#new_thread').show('blind');
        else
            $('#new_thread').hide('blind');
    });
    
    //Merge page
    $('#add_merge_page').live('click', function() {
        if ($('#merge_page').css('display') === 'none') {
        	$('.page_options_container').hide();
            $('#merge_page').show('blind');
        } else
            $('#merge_page').hide('blind');
    });
    
	/* For displaying the Add aliases field on page */
	$('#apply_alias').live('click', function() {
		if ($('#new_alias').css('display') === 'none') {
			$('.page_options_container').hide();
			$('#new_alias').show('blind');
		} else
			$('#new_alias').hide('blind');
	});
	
	/* For displaying the apply official interest field on page */
	$('#apply_official').live('click', function() {
		if ($('#new_official_name').css('display') === 'none')
			$('#new_official_name').show('blind');
		else
			$('#new_official_name').hide('blind');
	});

	//Opens a popup upon clicking on a + icon to add a featured interest
	$('#new_feature').dialog({
		modal: true,
		draggable: false,
		resizable: false,
		width: 400,
		height: 150,
		//show: {effect: "fade", duration: 400},
		autoOpen: false,
		open: function(event, ui) {
        	$('.ui-widget-overlay').click(function() {
        		$('.ui-dialog').find('.ui-dialog-content').dialog('close');
        	});
        },
		close: function() {
			$('#new_feature_input').tokenInput('clear');
			//$('#new_feature').dialog('destroy');
		}
	});

    
    $('#add_feature_interest').live('click', function() {
    	$('#new_feature').dialog('open');
    	/*
        if ($('#new_feature').css('display') === 'none') {
        	$('.feat_interest_err').remove();
            $('#new_feature').show('fade');
        } else {
            $('#new_feature').hide('fade');
        }
        */
    });

	//Submit featured interest
	$('#page_new_feature_submit').live('click', function() {
        var form = $(this).closest('form');
        var url = form.attr('action');
        var features = $('#new_feature_input').tokenInput('get');
        var f_url = form.find('input[name=url]').val();
        var type = form.find('select[name=type]').val();
        //prepare_autocomplete('#new_topic','#load_topics','#topic_names');
        var data = {
            page: JSON.stringify(features),
            url: f_url,
            type: type,
            view: 'page',
            ci_csrf_token: $("input[name=ci_csrf_token]").val()
        };
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(msg) {
            	$('#new_feature_input').tokenInput('clear');
            	if (msg.indexOf('ERR:') >= 0) {
            		if ($('.feat_interest_err').length <= 0) {
            			$('#new_feature').prepend('<div class="feat_interest_err">'+msg.substring(4)+'</div>');
            		}
            	} else {
	                $('#new_feature').dialog('close');
	                $('#page_featured_interests_'+type).append(msg);
	                if ($('#page_featured_interests_'+type+':hidden').length > 0) {
	                	$('#page_featured_interests_'+type).show('fade');
	                }
                }
            }
        });
        return false;
	});

	$('.cancel_page_option').live('click', function() {
		if ($(this).closest('#new_topic').length > 0) {
			$('#add_page_topic').click();
		}
		else if ($(this).closest('#merge_page').length > 0) {
			$('#add_merge_page').click();
		}
		else if ($(this).closest('#new_alias').length > 0) {
			$('#apply_alias').click();
		}
		return false;
	});
    
    
	//Enables Adding new Tab to page
	$('#add_new_tab').live('click', function() {
		var new_tab_form = $('#page_add_custom_tab');
		if (new_tab_form.css('display') === 'none')
			new_tab_form.show('blind');
		else
			new_tab_form.hide('blind');
	});

	//$('.newsfeed_entry_options').hide();
	//$('.newsfeed_entry_comments').hide();
	$('.newsfeed_entry_comments').each(function() {
		if ($(this).find('.child_comments').length <= 0) {
			$(this).hide();
			var view_comments_link = $(this).closest('.newsfeed_entry').find('.newsfeed_entry_options .hide');
			var num_comments = view_comments_link.attr('rel');
			view_comments_link.html('View Comments ('+num_comments+')');
		}
	});
	$('.newsfeed_entry_add_comment').hide();
	
	/*
	$(document).click(function(e) { 
 		if ( ! $(e.target).parents('#new_feature').length && ! $(e.target).parents('#add_feature_interest').length && !$(e.target).parents('.placeholder-token').length) $('#new_feature').hide(); 
	});
	*/



	/**
	Not sure what this is used for. Might not be used anymore.
	*/
	// <![CDATA[
	$(document).ready(function(){var key_typing=0; // thats sets variable called typing - it is needed to see how much times a keyboard button is clicked - if it is 1
		$('#url_submit').live('click', function(){
            var foundurl=$('#url').val(); // button clicked - parse the value to a var foundurl
		    if(foundurl!=''){ // make sure that there is foundurl
		        if(!isValidURL(foundurl)) // isValidURL is external function,which is at the bottom of the page. It checks if the found url looks like real url
			    {return false;} // do nothing if the url is bad
			    else
			    {
                    $.post("/application/views/tools/test_url.php?url="+foundurl,function(alive){
                        //if(alive==='works' && $('.url').length==0){ // so here we use test_url.php ! That tests if the foundurl is alive by using ajax.There is also a small rule that checks if previous url has already loaded.
                        if(alive==='works'){ // so here we use test_url.php ! That tests if the foundurl is alive by using ajax.There is also a small rule that checks if previous url has already loaded.
                            $('#user_text').show('blind');
                            $('#load').show(); // show loading image
                            //alert('about to fetch link');
                            $.post("/application/views/tools/fetch_link?url="+foundurl, { // make ajax request to the fetch.php with the foundurl
                            }, function(response){ // ajax have returned a content
                                $('#loader').html($(response).fadeIn('slow')); //show the ajax returned content
                                $('.images img').hide(); //hide all images found
                                $('#load').hide(); //content already loaded - no need of loading bar anymore
                                $('img#1').load(function(){$('img#1').fadeIn().onerror(function(){$('img#1').hide();});}); // when the first image loads see if it doesn't give error or actually exists.If there is a problem just hide the image field
                                var totimg=$('.images img').length; // all images count
                                $('#total').html(totimg); // show the count of images
                                var currentimg=1; //set the first image to 1
                                if(totimg>0){$('#current').html('1');}else{$('#current').html('0');} // some small fix for showing if there is 1 or 0 images available
                                if(totimg==1){$('#navi').hide()} // thats remove the navigation (next and prev images) if the image is only 1

                                $('#next').click(function(){if((currentimg)<totimg){currentimg=currentimg+1; $('img#'+(currentimg-1)).hide(); $('img#'+currentimg).fadeIn(); $('#current').html(currentimg);}}); // just change the id to +1 if the next button is clicked.Also apply fade effects.
                                $('#prev').click(function(){if(currentimg!=1 || currentimg==2){currentimg=currentimg-1; $('img#'+(currentimg+1)).hide(); $('img#'+currentimg).fadeIn(); $('#current').html(currentimg);}}); // just change the id to -1 if the next button is clicked.Also apply fade effects.
                                $('#post_loop_info').show();

                            });
	                    }
		            });
		        }
            }
        });

		// watermark input fields (you know the yellow hover- thats left from the 99 site example)
		jQuery(function($){

		   $("#url").Watermark("http://");
		});
		jQuery(function($){

		    $("#url").Watermark("watermark","#369");

		});
		function UseData(){
		   $.Watermark.HideAll();
		   $.Watermark.ShowAll();
		}

	});

function isValidURL(url){
		var RegExp = /(\.){1}\w/;

		if(RegExp.test(url)){
			return true;
		}else{
			return false;
		}
	}

	// ]]>





});