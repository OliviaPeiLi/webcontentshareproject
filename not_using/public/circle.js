// for animation
var mytop, myleft;
var current_circle = 0;
var insidecircle = 0;
var current_element = 0;
var state = 0;
var moving=0;
var draggable;
var newadded = [];
var newadded_circle = 0;
var circle_shift = 0, shifting = false, shifting_left = true;
var circlebox_dragging = false;

var circleNameMax = 38;						// maximum character when click on a list (inside a list)
var heightOfContent = 400;				// height of main content & columns
var isNewFriendBox = true;				// height of main content & columns

var visibility = 0;								// visibility: 0=private; 1=public

$(document).ready(function() {

	// caching image in IE
	if(jQuery.browser.msie) {
	  try {
	    document.execCommand("BackgroundImageCache", false, true);
	  } catch(err) {}
	}
	
	// adjust the location & title of each circle
	adjust_circle_title();
	adjust_circle_position();

	// showing name friend/circle
	showNameOfFriends();
	showNameOfCircles();

	// move circle & friend to initial location
	moveCircleToNewLocation();
	update_resolution();

	moveFriendToNewLocation();

	// set newcirclebox as droppable
	// it could not move -> not draggable
	$('.newcirclebox').each( function() {
		  //set_boundary_droppable ( $(this).find('.circle_boundary') );
		  set_boundary_droppable ( $(this) );
	});

	// enter the circle -> expand its size
	setCircleEvent();

	// out of circle element
	// $(".circle_element").each( function() {
	$(".circle_element").live( 'mouseleave', function(event) {
		//console.log('circle_element leaving.');
			$(this).removeClass('eselected');
			// $('.username_tooltip').hide();
			$('.username_tooltip').css('display','none');
	});

	// click on a icon to select it
	// $(".circle_element").each( function() {
	$(".circle_element").live( 'click', function(event) {
			event.stopPropagation();
			if ($(this).hasClass('eselected_click')) {
				$(this).removeClass('eselected_click');
			} else {
				$(this).addClass('eselected_click');
			}
			//console.log('select ' + $(this).attr('name') );
	});

	// enter a circle element -> draggable
	// $(".circle_element").each( function() {
	$(".circle_element").live( 'mouseenter', function(event) {
		//console.log('circle_element entering.');
				event.stopPropagation();
				if ($(this).hasClass('eselected')) {
				} else {
					$(this).addClass('eselected');
				}

				tooltipat( event.pageX + 50 , event.pageY, $(this).attr('rel') );
				//tooltipat( event.pageX + 50 , event.pageY, $(this).attr('name') );

				$(this).draggable({
						containment: 'circleboxes',
						// cursor: 'move',
						appendTo: 'body',
						helper: function() {
						 	var helperContainer = document.createElement('div');
						 	helperContainer.id = 'element-container';
						 	return helperContainer;
						},
						revert: true,
						cursorAt: {top: 15, left: 85},
				
						start: function(ev, ui) {
								$('#element-container').css('position', 'fixed');
								$('#element-container').topZIndex();
								$(this).clone().appendTo($('#element-container'));
								$('#element-container').find('.circle_element').css('position','absolute').css('top', '0px').css('left', '70px');
								
								// current circle
								current_element = Number($(this).attr('name'));
								if ( $('#newcircle_form').css('display') == 'none' ) {
										current_circle = Number($(this).parent().parent().attr('name'));
								}
								$('#element-container').data('element', true);

								// highligh circle that has that element
								var items = new Array();
								items[0] = current_element;
								user_in_circle( items );
						},
				
						drag: function(ev, ui) {
						},
				
						stop: function(ev, ui) {
								$('#element-container').data('element', false);

								refresh_user_in_circle();
					  }
				});
//		});
	});

	// if dropt to empty space
	$('.circleboxes').droppable( {
      accept: '*',
      drop: function(ev,ui) {
					// if draggable is a circle element, it means delete it
					if (ui.helper.data('element') == true) {
						ui.helper.remove();
						ui.draggable.draggable( 'option', 'revert', false );

						$('#circlebox_' + current_circle).data('under_updating', true);
						handleRemoveElement(ev,ui);
						refresh_user_in_circle();

					} else if (ui.draggable.data('friend') == true) {
					}

					//console.log('element=' + ui.draggable.data('element') + ' friend=' + ui.draggable.data('friend'));

				}
	});

	// set draggable for all friendbox
	$(".friendbox").each ( function() {
		$(this).draggable({
		//containment: 'document',
		cursor: 'move',
		appendTo: 'body',
		helper: function() {
		 	var helperContainer = document.createElement('div');
		 	helperContainer.id = 'helper-container';
		 	return helperContainer;
		},
		appendTo: 'body',
		revert: true,
		// make exact cursor location to avoid mis- expandance or shink after
		// dragging
		cursorAt: {top: 30, left: 170},

		start: function(ev, ui) {
					// disable show/hide scrollbar
					$('.friendboxesfatherdummy').unbind('mouseenter').unbind('mouseleave');
					$('.circleboxes').unbind('mouseleave');

					$(this).data('friend', true);
		
				 	$('#helper-container').topZIndex();

				 	animationInterrupt = false;
					enableReverting = true;

				 	$(this).addClass('selected');
				 	$('.friendbox.selected').addClass('drag-original');
		
					// hack for IE
				 	$('#helper-container').bind({
				 	//$(document).bind({
				 		mouseup: function() {
				 			animationInterrupt = true;
				 			$('#helper-container').append($('.user-multidragging-animation'));
							//console.log('mouseup enableReverting=' + enableReverting);
				 			if (enableReverting) {
								revertMultidrag();
							} else {
								// remove rectangle
								$('.friendbox_rec').each ( function() {
									$(this).remove();
								});
							}
							// alert('enableReverting = ' + enableReverting);
				 		}
				 	});
				 	
				 	multidraggingManager($(this), ev);

				 	//console.log('->FRIEND drag start');
				 },

		drag: function(ev, ui) {
				},

		stop: function(e) {
					// disable show/hide scrollbar
					var ev = e.originalEvent;
					if ( $('.circleboxes').isMouseInsideDiv(ev) ) {
						$('.circleboxes').css('overflow', 'auto');
					} else {
						$('.circleboxes').css('overflow', 'hidden');
					}
					if ( $('.friendboxesfatherdummy').isMouseInsideDiv(ev) ) {
						$('.friendboxesfatherdummy').css('overflow', 'auto');
					} else {
						$('.friendboxesfatherdummy').css('overflow', 'hidden');
					}

					// bind again mouseenter/mouseleave
					$('.circleboxes').bind('mouseleave', function() {
						hideOverflow( $(this) );
					});
					$('.friendboxesfatherdummy').bind('mouseenter', function() {
						expandFriendboxHeight();
						showOverflow( $(this) );
					}).bind('mouseleave', function() {
						hideOverflow( $(this) );
					});


					$(this).css('position', 'absolute');
					$(this).data('friend', false);
					if (enableReverting) {
						$(this).draggable( "option", "revert", true );
					}

					$('.drag-original').removeClass('drag-original');

					refresh_user_in_circle();


				 	//console.log('->FRIEND DRAGGBLE: STOP');
				}
		});
		});

	// when moving over a friendbox -> highligh the circle that contains it
	$('.friendbox').each( function() {
		$(this).mouseenter( function(ev) {
			// highligh circleboundary that has friendbox
			var item = Number($(this).attr('name'));
			var items = new Array(); items[0] = item;
			user_in_circle( items );
		});
	});

	// when moving out a friendbox, remove the highlight
	$('.friendbox').each( function() {
		$(this).mouseleave( function(ev) {
			// $('.username_introduction').hide();
			$('.username_introduction').css('display', 'none');
			refresh_user_in_circle();
		});
	});

	var element_name;
	$(document).mousemove( function(ev) {
		if ( $('.username_tooltip').css('display') == 'block' ) {
			//tooltipat( ev.pageX + 50 , ev.pageY, $(this).attr('name') );
			tooltipat( ev.pageX + 50 , ev.pageY, element_name );
			return true;
		} else if ( $('.username_introduction').css('display') == 'block' ) {
			// introductionat( ev.pageX + 50 , ev.pageY, $(this).attr('name') );
			return true;
		}
		return true;
	});


	// click on friendbox to select it
	$('.friendbox').each( function() {
		$(this).bind({
			click: function(event) {

				event.stopPropagation();
				if ($(this).hasClass('selected')) {
					$(this).toggleClass('selected');
				} else {
					$(this).addClass('selected');
				}

				refresh_user_in_circle();
		}
		});
	});

	// create a new circle click
	$('#newcircle_creation').click( function() {
		var msg = checkNewCircleName( $('#newcircle_circlename').val() );

		if ( msg === true ) {
			//var circle_type = $('#circle_type').text();
				newCircleCreation();
				return false;
		} else {
				alert( msg );
		}

		return false;
	});


	// cancel
	$('#newcircle_cancel').click( function() {
		$('#newcircle_form').fadeOut('slow');
		return false;
	});

		//keydown event for IE
		$("#edit_circlename").live("keydown", function(e) {
			if (e.which == 13) {
				return $('#edit_changename').click();
			}
		});

		$(document).keydown( function (e) {
			// remove element by press "delete" button (avoid 'detele' pressed when
			// changing name)
			if ( e.which == 46 && $('#edit_form').css('display') != 'block' ) {
				if ( $('.eselected_click').length + $('.eselected').length > 0 ) {
					var items = []; var ci;
					$('.eselected, .eselected_click').each( function() {
						$(this).removeClass('eselected').removeClass('eselected_click');
						ci = $(this).parent().parent().attr('name');
						items[items.length] = $(this).attr('name');
					});

					// $(".username_tooltip").hide();
					$(".username_tooltip").css('display', 'none');

					// should not remove friendbox, only remove small icon inside circle
					if ( insidecircle != 0 ) {
						removeElement(ci, items, true);
					} else {
						removeElement(ci, items, false);
					}
				} else if ( insidecircle != 0 ) {
					var items = [];
					$('.friendbox.selected').each( function() {
						$(this).removeClass('selected');
						items[ items.length ] = Number( $(this).attr('name') );
					});
					removeElement(insidecircle, items, true);
				}
			}

			// press enter when creating a new circle
			if ( e.keyCode == 13 && $('#newcircle_form').css('display') == 'block' ) {
				$('#newcircle_creation').click();
			}
		});



		// change the name of circle
		$('#edit_changename').click ( function() {
					// check name
					var msg = checkNewCircleName( $('#edit_circlename').val() );
			
					if ( msg === true ) {
							changeNameCircle();
							return false;
					} else {
							alert( msg );
					}

					return false;
		});

		// cancle
		$('#edit_cancel').click( function() {
			$('#edit_form').fadeOut('slow');
			return false;
		});


		// click on center of a circle
		$('.circle_title').live( 'click', function() {
				if ( Number($(this).parent().attr('name')) == 0  ) {
						// set default type as 'private'
						$('input[name="newcircle_visibility"][value="0"]').attr('checked', 'checked');
						create_newcircle();
				} else {
						loadInsideCircle(Number($(this).parent().attr('name')));
				}
		});

		// allow select multiple friend via selectable
		$( ".friendboxes" ).selectable({
				filter: '.friendbox:visible',
				stop: function() {
								if ( $('.ui-selected').length == 0 ) {
										$('.friendbox').each ( function() {
												$(this).removeClass('selected');
										});
										$('.friendcontained').each( function() {
												$(this).removeClass('friendcontained');
										});
								}
				},
				selecting: function(){
				},
				selected: function(){
						$('.ui-selected').each ( function() {
								$(this).addClass('selected');
								refresh_user_in_circle();
								//console.log( 'selected ' + $(this).attr('name') );
						});

				},
				unselected: function(){
				},
				unselecting: function(){
				}
		});


		// remove friends (when inside a circle)
		$('#remove_friend').live( 'click', function() {
			var items = []; var i=0;
			$('.selected').each ( function() {
					items[i++] = Number( $(this).attr('name') );
			});
			$('#circlebox_' + insidecircle).data('under_updating', true);
	
			// ajax
			if ( items.length > 0 ) {
					removeElement(insidecircle, items, true);
			}
	
			return false;
	  });

		// click on edit button to change circle name
		$('.edit_circle').live( 'click', function(ev) {
			editcircle(ev);
			return false;
	  });

		// delete circle
		$('.delete_circle').live( 'click', function() {
				var r=confirm("Are you sure you want to delete this list ("+circlename[insidecircle]+")?");
	
				if (r==true) {
						var cc = insidecircle;
						insidecircle = 0;
						deleteCircle( cc );
						$('#return_mainpage').click();
				}
				return false;
		});

		// from inside a circle to outside
		$('#return_mainpage').live( 'click', function() {
			loadInsideCircle(0);
			$('#inside_circle_introduction').remove();
	  });

		// when user scroll, set the height to cover all items to make sure all of
		// friendbox could be selectable
		function expandFriendboxHeight()
		{
			// if ( isNewFriendBox == true ) {
			//	isNewFriendBox = false;
				var visible = $('div[class~="friendbox"]:visible').length;
				var height  = visible * 30 + 90;

				$('#friendboxes').css('height', height + 'px');
				// console.log( ' count = ' + visible + 'height = ' + height );
			// }
		}
//		$('.friendboxesfatherdummy').scroll( function() {
//				var last = $('#friendboxes .friendbox').last();
//				var height = last.position().top + last.height();
//
//				$('#friendboxes').css('height', height + 'px');
//		});

		function showOverflow(obj) { obj.css('overflow', 'auto'); }
		function hideOverflow(obj) { obj.css('overflow', 'hidden'); }
		$('.circleboxes').bind('mouseenter', function() {
			showOverflow( $(this) );
		}).bind( 'mouseleave', function() {
			hideOverflow( $(this) );
		});

		$('.friendboxesfatherdummy').bind('mouseenter', function(event) {
			expandFriendboxHeight();
			showOverflow( $(this) );
		}).bind('mouseleave', function(event) {
			if ( $(this).isMouseInsideDiv(event) == false ) {
				hideOverflow( $(this) );
			}
		});

		// set height of columns
		window.setTimeout ( function() {
			setHeight();
			window.clearTimeout();
		}, 200);
		function setHeight()
		{
			$('.friendboxesfatherdummy').css('min-height',heightOfContent).css('height',($(window).height()-100)+'px');
			$('.circleboxes').css('min-height',heightOfContent).css('height',($(window).height()-100)+'px');
			$('.circleboxes').css('left', $('.friendboxesfatherdummy').offset().left + $('.friendboxesfatherdummy').width() );

			// adjust size of elements
			set_list_instruction_position();
			//$('body').css('height', $('#footer').offset().top + $('#footer').height() );
		}


		var first = true;
		$(window).resize(function() {
			set_list_instruction_position();
			if ( first == true ) {
				first = false;
			} else {
				update_resolution();
			}
		});


});
