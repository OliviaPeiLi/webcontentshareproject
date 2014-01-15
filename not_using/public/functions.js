
jQuery.fn.gen_topleft = function (height, width) {
    this.css("position","absolute");

	 mytop  = (  this.parent().height() - height ) / 2 ;
	 myleft = (  this.parent().width() - width   ) / 2 ;

    return this;
}

jQuery.fn.center = function () {
    this.css("position","absolute");

	 var top  = ( this.parent().height() - this.height() ) / 2 + 'px';
	 var left = ( this.parent().width()  - this.width()  ) / 2 + 'px';

	 this.css('top', top );
	 this.css('left', left );

    return this;
}

jQuery.fn.centerfix = function () {
    this.css("position","absolute");

	 var top  = ( this.parent().height() - this.height() ) / 2 + 'px';
	 var left = ( this.parent().width()  - this.width()  ) / 2 + 'px';

	 this.css('top', top);
	 this.css('left', left);

    return this;
}

// for calcualating postion of element inside circle
var size = 34;
//var pos_x = new Array( 2, 2, 2, 2, 2, size + 2, 2*size + 2, 3*size + 2, 4*size+2, 4*size + 2, 4*size+2, 4*size+2, 4*size+2, 3*size + 2, 2*size+2, size+2, 2)
//var pos_y = new Array( 2, size + 2, 2*size + 2, 3*size + 2, 4*size + 2, 4*size + 2, 4*size + 2, 4*size + 2, 4*size + 2, 3*size+2, 2*size+2, size+2, 2, 2, 2, 2, 2 );
var pos_x = new Array( -2, size-2, 2*size-2, 3*size-2, 4*size-2, -2, size-2, 2*size-2, 3*size-2, 4*size-2, -2, size-2, 2*size-2, 3*size-2, 4*size-2 )
var pos_y = new Array( 2*size+2, 2*size+2, 2*size+2, 2*size+2, 2*size+2, 3*size+2, 3*size+2, 3*size+2, 3*size+2, 3*size+2, 4*size+2, 4*size+2, 4*size+2, 4*size+2, 4*size+2 );

function element_show(top, left, bottom, right, userid)
{
	 // alert('element show');

	 if (circle_element[Number(userid)].length > 15) { var max = 15; }
	 else { var max = circle_element[userid].length ; }

	 var str="";
	 for (var i=0; i < max ; i++)
	 {
		  var x = pos_x[i];
		  var y = pos_y[i];

		  var name = circle_element[Number(userid)][i];
		  var rel = circle_element[Number(userid)][i];
		  var stri = '<img class="circle_element" name="' + name + '" rel="' + friendname[Number(name)] + '" style="top:'+y+'px;left:'+x+'px;" src="' + avatar[name] + '" /><br />';
		  str += stri;

	 }

	 return str;
}

function adjust_circle_title()
{	
	$('.circle_boundary').each( function() {
		$(this).center();
	});

	$('.circle_title').each ( function() {
		$(this).centerfix();
	});
}

// dropping a element to outside for deleting
function handleRemoveElement(event, ui)
{
		var removed_item = Number(ui.draggable.attr('name'));
		// set revert of draggable element
		ui.draggable.draggable( 'disable' );
		ui.draggable.draggable( 'option', 'revert', false );
		ui.draggable.hide('slow');

		if (current_circle != 0) {
				var csrf_token = $('input[name=ci_csrf_token]').val();
				var circle_type = $('#circle_type').text();
				$.post(base_url + 'rm_loop_user', {circle:current_circle,item:removed_item,ci_csrf_token:csrf_token, type:circle_type}, function(data) {
						if (data.status == 1) {
							
								// remove the element from circle
								// var removed_item = ui.draggable.attr('name');
								var new_element_array = [];
								var j=0;
								for (var i=0; i<circle_element[current_circle].length; i++)
								{
									 if ( Number(circle_element[current_circle][i]) != removed_item ) {
										  new_element_array[j++] = circle_element[current_circle][i];
									 }
								}
								circle_element[current_circle] = new_element_array.slice();
								$('#circlebox_' + current_circle).data('under_updating', false);

								if ( current_circle == insidecircle && insidecircle != 0) {
										$('#circlebox_' + insidecircle).find('.circle_title').click();
								}
						} else {
								alert('An error occurred during requesting to server');
								$('#circlebox_' + current_circle).data('under_updating', false);
						}
				}, "json");
		} else {
					// remove the element from circle
					var new_element_array = [];
					var j=0;
					for (var i=0; i<circle_element[current_circle].length; i++)
					{
						 if ( Number(circle_element[current_circle][i]) != removed_item ) {
							  new_element_array[j++] = circle_element[current_circle][i];
						 }
					}
					circle_element[current_circle] = new_element_array.slice();
					$('#circlebox_' + current_circle).data('under_updating', false);
		}
}

function circle_element_mouseleave()
{
		if (moving == 1) {
		} else {
			var h = $(this).height();
			var w = $(this).width();
			var top = $(this).position().top;
			var left = $(this).position().left;
	
			$(this).css('height', h - 6 + 'px');
			$(this).css('width', w - 6 + 'px');
	
			$(this).css('top', top + 3 + 'px');
			$(this).css('left', left + 3 + 'px');
		}
}

// add an items to a circle
function add_item_to_circle( circle, items )
{
		 var str = "";
		 var counter = 0;
		 newadded = [];			// global newadd array
		 for (var i = 0; i < items.length; i++)
		 {
			 var item = items[i];
			 if ( circle_element[circle].in_array(item) == true ) {
			 } else {
				  var index = circle_element[circle].length;
				  circle_element[circle][index] = item;
				  str += item + ' ';
					newadded[counter] = Number(item);		// save to global newadded array
					newadded_circle = circle;						// save to global newadded array
					counter += 1;

					// show
					var mfl = $('#circlebox_' + circle).find('.friendlist');
					var le = circle_element[circle].length - 1;
					mfl.append('<img class="circle_element" name="' + item + '" style="display:none;top:'+pos_y[le]+'px;left:'+pos_x[le]+'px;" src="' + avatar[item] + '" />');
					mfl.find('.circle_element').each( function() {
							if ($(this).attr('name') == item) {
								  var mthis = $(this);
									if ( mthis.parent().css('display') == 'block' ) {
										  mthis.fadeIn('slow', function() {
												// mthis.addClass('eselected');
												if ( mthis.parent().parent().data('status') == 'out' ) {
														mthis.hide();
												}
										  });
									}
							}
					});
			 }
		 }

	balloon_show( circle, counter );

	refresh_user_in_circle();
	$('#circlebox_' + circle).data('under_updating', false);
}

// handle add element 
function handleAddElement(items, circle)
{
		if (circle != 0) {
				var csrf_token = $('input[name=ci_csrf_token]').val();
				var circle_type = $('#circle_type').text();
				$.post(base_url + 'add_loop', {circle:circle,items:items,ci_csrf_token:csrf_token, type:circle_type}, function(data) {
						if (data.status == 1) {
								$('#circlebox_' + circle).data('under_updating', true);
								add_item_to_circle( circle, items );

								if ( circle == insidecircle && insidecircle != 0 ) {
										$('#circlebox_' + insidecircle).find('.circle_title').click();
								}
						} else {
								alert('An error occurred during requesting to server');
								$('#circlebox_' + circle).data('under_updating', false);
						}
				}, "json");

		} else {
								add_item_to_circle( circle, items );
		}

}

// create a new circle
function create_newcircle()
{
	var str = ""; var col=0, row=0;
	if (circle_element[0].length == 0) {
	} else {
		var ci=-1, ri=0;
		for (var i=0; i<circle_element[0].length; i++)
		{
			// show current members
			var name = circle_element[0][i];
			//console.log('c: '+name);

			// calculate location
			ci += 1; if (ci==3) { ri++; ci=0; }
			
			col = ci * 160;
			row = ri * 65;

			str += '<div class="friendbox_newlist" name="'+name+'" uid="'+name+'" style="top:'+row+'px;left:'+col+'px;width:150px;">' +
						'<div class="friendavatar"><img class="friendavatar" src="'+avatar[Number(name)]+'" /></div>' +
						'<div class="friendname" name="1" style="width:80px;right:3px;">' + friendname[Number(name)] + '</div>' +
					'</div>';
		}
	}

	var newform = $('#newcircle_form');

	// show at center of window
	newform.css("top", (($(window).height() - newform.outerHeight()) / 2) + $(window).scrollTop() + "px");
  newform.css("left", (($(window).width() - newform.outerWidth()) / 2) + $(window).scrollLeft() + "px");

	// show current member
	$("#newcircle_member").html(str);
//	moving = 1;
//	newform.show();

	$("#newcircle_creation").show();
	$("#newcircle_cancel").show();

  $('#newcircle_circlename').val('');
	newform_show( 0 );

	$('#newcircle_circlename').focus();

}

// creating new circle
function newCircleCreation()
{
		var index = current_circle;

		newform_hide();

		// AJAX new circle creation
		var circle_name = $('#newcircle_circlename').val();
		var circle_type = $('#circle_type').text();
		var visibility = $('input[name="newcircle_visibility"]:checked').val();

		var csrf_token = $('input[name=ci_csrf_token]').val();
		$.post(base_url + 'createNewLoop', {name:circle_name,element:circle_element[index],type:circle_type,visibility:visibility,ci_csrf_token:csrf_token}, function(data) {
				if (data.status == 1) {
					// show a new circlebox at specific location
					var str = '<!-- Circle ' + data.circle_id + '-->' +
										'	<div class="circlebox" name="' + data.circle_id + '" style="top:300px;left:300px;" id="circlebox_' + data.circle_id + '">' +
										'		<div class="circlebase circle_boundary" name="' + data.circle_id + '">' +
										'			<div class="circlebase circle_title"> </div>'	+
										'			<div class="friendlist"> </div>' +
										'		</div>' +
										'	</div>' ;

					$(".circleboxes").append( str );

					// adjust variable
					circlename[data.circle_id] = data.name;
					circle_newtop[data.circle_id] = data.top;
					circle_newleft[data.circle_id] = data.left;

					var neworder = circle_position_order.length;
					circle_position_order[ neworder ] =  Number(data.circle_id);
					circle_position_left[ neworder ] =  data.left
					circle_position_top[ neworder ] =  data.top

					// save new element to new circle
					circle_element[data.circle_id] = [];
					//debugging
					//for (var i=0; i < circle_element[0].length; i++)
					for (var i=0; i < circle_element[index].length; i++)
					{
							circle_element[data.circle_id][i] = circle_element[index][i];
					}

					// adjust the location
					$('#circlebox_' + data.circle_id).find('.circle_boundary').center();
					$('#circlebox_' + data.circle_id).find('.circle_boundary').find('.circle_title').centerfix();
					$('#circlebox_' + data.circle_id).find('.circle_boundary').find('.circle_title').text(data.name);

					// activate droppable
					//$('#circlebox_' + data.circle_id).find('.circle_boundary').droppable();
					//set_boundary_droppable ( $('#circlebox_' + data.circle_id) );

					// $("#circlebox_" + data.circle_id).show();
					$('#circlebox_' + data.circle_id).animate ( {left: data.left, top: data.top}, {duration: 300} );

					// clean circle[0]
					if ( index == 0 ) {
						circle_element[0] = [];
					}

					// setup event for boundary
					setCircleEvent();

					refresh_user_in_circle();
					
				} else {
					alert('An error occurred during requesting to server');
				}
		}, "json");
}


//function rotate(e)
//{
//	// rotate the element (CSS3)
//	e.css('-webkit-transform','rotate(360deg)');
//	e.css('-o-transform','rotate(360deg)');
//	e.css('-moz-transform','rotate(360deg)');
//
//	var wait = setInterval ( function() {
//			clearInterval(wait);
//			e.css('-webkit-transform','');
//			e.css('-o-transform','');
//			e.css('-moz-transform','');
//	}, 900);
//
//}


// show name of a element when moving on
function tooltipat( x, y, name )
{
	 var tooltip = $('.username_tooltip');
	 tooltip.css('top', y + 'px');
	 tooltip.css('left', x + 'px');
	 tooltip.css('display', 'block');

	 tooltip.html( name );
}

// show name of a friend when moving on
// (reserved)
function introductionat( x, y, name )
{
	 var tooltip = $('.username_introduction');
	 tooltip.css('top', y + 'px');
	 tooltip.css('left', x + 'px');
	 tooltip.css('display', 'block');
	
}

// showing name of friendbox
function showNameOfFriends()
{
	 $('.friendname').each( function() {
			var name = $(this).attr('name');

			if ( Number(name) != 0 ) {
				var fn = friendname[Number(name)];
				$(this).text(fn);
			}
	 });
}

// showing name of circlebox
function showNameOfCircles()
{
	 $('.circlebase .circle_title').each( function() {
			var name = $(this).parent().attr('name');

			if ( Number(name) != 0 ) {
				var fn = circlename[Number(name)];
				$(this).text(fn);
			}
	 });
}

// confirm new name of a circle
function checkNewCircleName( name )
{
		// check if name is empty
		if ( name.trim() == "" ) {
				return "List name is empty. Please input another name";
		}
/*	//comment out check list name 
		// check if exist name, do not check circle[0]
		for (var i in circlename)
		{
				if ( isNaN(i) == false ) {
						var cn = String ( circlename[i] );
						if ( name.trim() == cn.trim() ) {
								return "Cirname name is exist. Please input another name";
						}
				}
		}
*/
		return true;
}

// show form
function newform_show(index)
{
		current_circle = index;
		moving = 1;
		$('#newcircle_form').show();
}

// hide form
function newform_hide( circle_hide )
{
		moving = 0;

		// mouseleave
		$('.circle_boundary').each ( function() {
				if ( Number ( $(this).attr('name') ) == current_circle ) {
						$(this).mouseleave();
				}
		});

		current_circle = 0;
		$('#newcircle_form').hide();
}

// delete a circle
function deleteCircle( circle )
{
		// AJAX remove circle
		// to adjust location of circles
		var csrf_token = $('input[name=ci_csrf_token]').val();
		var circle_type = $('#circle_type').text();
		$.post(base_url + 'del_loop', {circle:circle, ci_csrf_token:csrf_token, type:circle_type}, function(data) {
				if ( data.status == 1 ) {
						$('.circle_boundary').each ( function() {
								if ( Number ( $(this).attr('name') ) == circle ) {
										//$(this).parent().hide('slow');
										$(this).parent().remove();

										//remove circlename[circle]
										circlename = circlename.remove(circle);
										circlebox_remove( circle );

										// adjust location
										for (var i=0; i < data.index.length; i++ )
										{
											var index = data.index[i];
											circle_newleft[index] = data.left[index];
											circle_newtop[index] = data.top[index];
										}

										moveCircleToNewLocation()
								}
						});
				} else {
					alert('An error occurred during requesting to server');
				}
		}, "json");


}

// change a circle name
function changeNameCircle()
{
		var cid = insidecircle; var newname = $('#edit_circlename').val();
		var csrf_token = $('input[name=ci_csrf_token]').val();
		var circle_type = $('#circle_type').text();
		visibility = $('input[name="editcircle_visibility"]:checked').val();
		// AJAX Change name of circle
		$.post(base_url + 'edit_loopname', {id:cid,newname:newname, visibility:visibility, ci_csrf_token:csrf_token, type:circle_type}, function(data) {
				if (data.status == 1) {
					circlename[cid] = $('#edit_circlename').val();
					$('.circle_title').each ( function() {
							if ( Number( $(this).parent().attr('name') ) == Number (cid) ) {
									$(this).text( $('#edit_circlename').val() );
									$('#edit_form').fadeOut('slow');
							}
					});

					// add edit | delete
					var ct = $('#circlebox_' + insidecircle).find('.circle_title');
					ct.append('<br />');
					ct.append('<a href="#" class="edit_circle">Edit </a> | ');
					ct.append('<a href="#" class="delete_circle">Delete</a>');

					// update the new name 
					var ic = $('#inside_circle_introduction');
					ic.html('');
					ic.append('<label><b>'+circlename[insidecircle]+'</b></label> | ');
					ic.append('<a href="#" id="return_mainpage">Return</a> | ');
					ic.append('<a href="#" id="remove_friend">Remove interest(s)</a> | ');
					ic.append('<a href="#" class="edit_circle">Edit</a> | ');
					ic.append('<a href="#" class="delete_circle">Delete</a> ');
					ic.css('zIndex', 10);
					set_list_instruction_position();			// set position of tooltip boxes

				} else {
					alert('An error occurred during requesting to server');
				}
		}, "json");

}

// move circle to another location
function moveCircleToNewLocation()
{
		$('.circlebox, .newcirclebox').each( function() {
				var myleft = circle_newleft[ Number($(this).attr('name')) ];
				var mytop  = circle_newtop [ Number($(this).attr('name')) ];

				$(this).animate( {top: mytop, left: myleft}, {duration: 300} );
		});
}

// moving friend to another location
function moveFriendToNewLocation()
{
		$('.friendbox, .newfriendbox').each( function() {
				var myleft = friend_newleft[ Number($(this).attr('name')) ];
				var mytop  = friend_newtop [ Number($(this).attr('name')) ];

				$(this).animate( {top: mytop, left: myleft}, {duration: 450} );
		});
}

// showing element
function showing_element( mthis )
{
	  var itop = mthis.parent().position().top + mthis.position().top - 10 ;
	  var ileft = mthis.parent().position().left + mthis.position().left - 10 ;
	  var ibottom = itop + 170;
	  var iright = ileft + 170;
	  
	  var str = element_show(itop, ileft, ibottom, iright, Number(mthis.attr('name')) );

	  mthis.find('.friendlist').html(str);
}


/*****************/
//function newcirclebox_mouseenter( ev, mthis )
function circlebox_mouseenter( mthis )
{

	if ( $.browser.msie ) {
		// if IE browser
		var duration = 100;
	} else {
		// if other browser
		var duration = 150;
	}

  mthis.data('status', 'in');

			if ( mthis.data("running") === true ) {
				return false;
			} else {

			 mthis.data('running', true);
			 mthis.gen_topleft(170, 170);
			 //TODO: Check if needed
			 mthis.find('.circle_title').animate( {
			 	width: "168px",
			 	height: "70px",
			 	left: "1px",
			 	top: "1px"
			 }, {duration: 150, step: function(){}, complete: function() {}});
			 mthis.animate( {
										width:"170px",height:"170px",left:myleft,top:mytop
									},
				  					{
										duration: 150,
										step: function() {
									  		//mthis.find('.circle_title').centerfix(); //TODO: chekc if needed
										},
										complete: function () {
												  var circle_id = mthis.attr('name');
	
												  var itop = mthis.parent().position().top + mthis.position().top - 10 ;
												  var ileft = mthis.parent().position().left + mthis.position().left - 10 ;
												  var ibottom = itop + 170;
												  var iright = ileft + 170;
												  
												  var str = element_show(itop, ileft, ibottom, iright, circle_id);
									
												  mthis.find('.friendlist').html(str);
												  mthis.find('.friendlist').show();

													var wait = setInterval ( function() {
															if ( $('#circlebox_' + circle_id).data('under_updating') == true ) {
															} else {
																clearInterval(wait);
																mthis.find('.friendlist').find('.circle_element').each ( function() {
																		if ( circle_element[circle_id].in_array(Number($(this).attr('name'))) == false ) {
																				$(this).fadeOut('slow', function() {
																						var str = element_show(itop, ileft, ibottom, iright, circle_id);
																						mthis.find('.friendlist').html(str);
																				});
																		}
																});
															}
													}, 70);


													mthis.data('running', false);

													if ( mthis.data('status') == 'out' ) {
															mthis.mouseleave();
													} else {
															// show newed add
															if ( Number(mthis.attr('name')) == newadded_circle ) {
																	// clean previous new added
																	$('.newadded').each( function() { $(this).removeClass('newadded'); });
		
																	// highligh new added
																	mthis.find('.circle_element').each ( function() {
																					if ( newadded.in_array(Number($(this).attr('name'))) == true ) {
																						$(this).addClass('newadded');
																					}
																	});
															}
													}
				 						}
									} );
		}
}


//function newcirclebox_mouseleave( ev, mthis )
function circlebox_mouseleave( mthis )
{
  mthis.data('status', 'out');

			if ( mthis.data("running") === true ) {
				return false;
			} else {
				 mthis.data('running', true);
				 mthis.gen_topleft(120, 120);
				 //TODO: check if this is needed
				 mthis.find('.circle_title').animate( {
				 	width: "98px",
				 	height: "93px",
				 	left: myleft,
				 	top: mytop
				 }, {duration: 150, step: function(){}, complete: function() {}});
				 mthis.animate( {
											width:"120px",height:"120px",left:myleft,top:mytop
										},
					  					{
											duration: 150,
											step: function() {
										  		mthis.find('.circle_title').centerfix();
											},
											complete: function() {

												  mthis.find('.friendlist').find('.circle_element').css('display','none');
													mthis.data('running', false);

													if ( mthis.data('status') == 'in' ) {
														mthis.mouseenter();
													} else {
													}
					 						}
										} );
				  }
}

// show balloon
function balloon_show( circle, counter )
{
		$('#balloon').show();
		$('#balloon').html('<b>+' + counter + '</b>');
		$('#balloon').css('top', $('#circlebox_' + circle).position().top + 50 + 'px');
		$('#balloon').css('left', $('#circlebox_' + circle).position().left + 100 + 'px');
		$('#balloon').css('z-index', '2000');
		var newleft = $('#balloon').position().left ;
		var newtop = $('#balloon').position().top - 60;

		$('#balloon').animate( {left: newleft, top: newtop}, {duration: 700, complete: function() { $(this).hide(); }} );
}

// check user in circle, add a class to found circle
function user_in_circle( items )
{
		for (var item in items)
		{
				if ( isNaN(items[item]) == false ) {
						for (var i in circle_element)
						{
								if ( isNaN(i) == false ) {
										if ( circle_element[i].in_array( items[item] ) == true ) {
												$('#circlebox_' + i).find('.circle_boundary').addClass('friendcontained');
										}
								}
						}
				}
		}
}

function refresh_user_in_circle()
{
		$('.friendcontained').each( function() {
				$(this).removeClass('friendcontained');
		});

		var items = new Array();
		var index = 0;
		$('.selected').each ( function() {
				items[index++] = Number($(this).attr('name'));
		});
		user_in_circle( items );
}

// moving circle
function circle_shift_left ( index )
{
		// get circle_id
		var circle = circle_position_order[index];

		// get new position
		var newleft = circle_position_left[index - 1];
		var newtop = circle_position_top[index - 1];

		// new order (global variables)
		circle_position_order[index-1] = circle;
		circle_newleft[circle] = newleft;
		circle_newtop[circle] = newtop;
}

// moving many circles
function circles_shift_left( from, to )
{
		for (var i=from; i<=to; i++)
		{
				circle_shift_left(i);
		}
}

// moving a circle
function circle_shift_right ( index )
{
		// get circle_id
		var circle = circle_position_order[index];

		// get new position
		var newleft = circle_position_left[index + 1];
		var newtop = circle_position_top[index + 1];

		// new order (global variables)
		circle_position_order[index+1] = circle;
		circle_newleft[circle] = newleft;
		circle_newtop[circle] = newtop;
}

// moving many circles
function circles_shift_right( from, to )
{
		for (var i=to; i>=from; i--)
		{
				//console.log('sfhit right: ' + i);
				circle_shift_right(i);
		}
}

function circlebox_remove ( circle )
{

		var index;
		for (var i=0; i < circle_position_order.length; i++)
		{
				if ( circle_position_order[i] == circle ) {
						index = i;
				}
		}
		circles_shift_left(index+1, circle_position_order.length - 1);

		var newend = circle_position_order.length - 1 ;
		circle_position_order = circle_position_order.remove( newend );
		circle_position_left = circle_position_left.remove( newend );
		circle_position_top = circle_position_top.remove( newend );

}

// set draggable for a circle
function set_boundary_droppable ( mthis )
{
	var div = $('.circleboxes:last');
	mthis.each ( function() {
		$(this).droppable( {
      accept: '*',
      greedy: true,
      // greedy: false,			// testing
      drop: function(event,ui) {
					// if dropping an element onto
					if (ui.helper.data('element') == true) {
						if ( $('#circlebox_' + $(this).attr('name')).find('.circle_title').isBoxVisible(div) == true) {
									if (current_circle == $(this).attr('name') ) {
										var added_item = current_element;
										//console.log('-> DROPPABLE to same circle_boundary (item=' + added_item + ')(' + current_circle + ' -> ' + $(this).attr('name') + ')');
									} else {
										var added_circle = $(this).attr('name');

										$('#circlebox_' + added_circle).data('under_updating', true);
										//console.log('under_updating ' + current_circle + ' '  + added_circle);

										var added_item = current_element;

										ui.helper.hide();
										ui.draggable.draggable('option', 'revert', 'false');
					
										//console.log('-> DROPPABLE to another circle_boundary (item=' + added_item + ')(' + current_circle + ' -> ' + added_circle + ')');
			
										var add_items = [];
										add_items[0] = added_item;
			
										handleAddElement(add_items, added_circle);

									  refresh_user_in_circle();
									}
						} else {
							// hidden boundary, consider as remove element
							ui.helper.remove();
							ui.draggable.draggable( 'option', 'revert', false );
	
							$('#circlebox_' + current_circle).data('under_updating', true);
							handleRemoveElement(event,ui);
							refresh_user_in_circle();
						}
					} else if (ui.draggable.data('friend') == true) {
						if ( $('#circlebox_' + $(this).attr('name')).find('.circle_title').isBoxVisible(div) == true) {
							// if dropping an friendbox onto
							var added_circle = $(this).attr('name');
							$('#circlebox_' + added_circle).data('under_updating', true);
	
							ui.draggable.draggable( 'option', 'revert', false );
	
							var add_items = []; var str = "";
							$('.friendbox.selected').each ( function() {
								add_items[ add_items.length ] = $(this).attr('name');
								str += $(this).attr('name') + ' ' ;
							} );
	
							handleAddElement(add_items, added_circle);
	
							refresh_user_in_circle();
	
							//console.log('has friend('+str+') drop to circlebox('+added_circle+')');
						} else {
							enableReverting = true;
						}
					}
			}
		});
  });
}

// setting draggable for a circlebox
function set_circlebox_draggable ( mthis )
{
  mthis.each ( function() {
		$(this).draggable({
						containment: 'circleboxes',
						cursor: 'move',
						appendTo: 'body',
						revert: false,
						zIndex: 6000,
						cursorAt: {top: 100, left: 100},
				
						start: function(ev, ui) {
								circlebox_dragging = true;

								current_circle = Number($(this).attr('name'));

								ev.stopPropagation();
						},
				
						drag: function(ev, ui) {
						},
				
						stop: function(ev, ui) {
								// hide a bar that indicating location of new position of
								// circle
								$('#bar').hide();
								if ( circle_shift == 0 ) {
										moveCircleToNewLocation()
								} else {
										circlebox_shifting ( circle_shift, current_circle, shifting_left );

										// ajax
										send_circle_order_to_server();

										moveCircleToNewLocation()
										circle_shift = 0;
		
										//console.log('new order ' + circle_position_order);
								}

								circlebox_dragging = false;
					  }
			});
			return false;
	});
}

// update location of circle to server
function send_circle_order_to_server()
{
		var circle_order = circle_position_order;
		var csrf_token = $('input[name=ci_csrf_token]').val();
		var circle_type = $('#circle_type').text();
		$.post(base_url + 'update_loop_order', {order:circle_order, ci_csrf_token:csrf_token, type:circle_type}, function(data) {
				if (data.status == 1) {
				} else {
					alert('An error occurred during requesting to server');
				}
		}, "json");
		
}

// adjust circle position
function adjust_circle_position()
{
		for (var i=1; i<circle_position_order.length; i++)
		{
				var cid = circle_position_order[i];
				circle_newleft[cid] = circle_position_left[i];
				circle_newtop[cid] = circle_position_top[i];
		}

		moveCircleToNewLocation()
}

// going inside a circle
function loadInsideCircle( circle )
{
		isNewFriendBox = true;																	// refresh friendbox -> calculate again height
		$('.friendboxes').css('height', '100px');								// dummy height, it was set again in mouseenter

		$('.friendbox.selected').each( function() {
			$(this).removeClass('selected');
		});
		
		var csrf_token = $('input[name=ci_csrf_token]').val();
		var circle_type = $('#circle_type').text();
		$.post(base_url + 'loadLoop', {circle:circle, ci_csrf_token:csrf_token, type:circle_type}, function(data) {
				if (data.status == 1) {
						// hide previous inside circle
						if (circle != insidecircle) {
								$('#circlebox_' + insidecircle).find('.circle_title').text( circlename[insidecircle] );
						}
						insidecircle = circle;
						visibility = data.visibility;

						if ( insidecircle != 0 ) {
							//console.log('insice_circle_intro');
								// show introduction box
								if ($('#inside_circle_introduction').length === 0) {
									$('body').append('<div id="inside_circle_introduction"></div>');
								}
								var ic = $('#inside_circle_introduction');
								ic.html('');
								if ( circlename[insidecircle].length > circleNameMax ) {
									ic.append('<label class="inlinediv"><b>'+circlename[insidecircle].substring(0,circleNameMax-1)+'...</b></label></br >');
								} else {
									ic.append('<label class="inlinediv"><b>'+circlename[insidecircle]+'</label>');
								}
								ic.append('<a href="#" id="return_mainpage">Return</a> | ');
								ic.append('<a href="#" id="remove_friend">Remove interest(s)</a> | ');
								ic.append('<a href="#" class="edit_circle">Edit</a> | ');
								ic.append('<a href="#" class="delete_circle">Delete</a> ');
								ic.css('zIndex', 10);
								set_list_instruction_position();			// set position of tooltip boxes
						}

						if ( insidecircle != 0 ) {
								// hide friend that do not inside circle
								$('.friendbox').each ( function() {
										if ( circle_element[insidecircle].in_array(Number($(this).attr('name'))) == false ) {
												$(this).fadeOut('slow');
										}
										if ( circle_element[insidecircle].in_array(Number($(this).attr('name'))) == true ) {
												$(this).fadeIn('slow');
										}
								});
						} else {
								// show all
								$('.friendbox').each ( function() {
										$(this).fadeIn('slow');
								});
						}

						// arrange position of friendbox again
						for (var i=0; i < data.element.length; i++)
						{
								var uid = data.element[i];
								friend_newleft[uid] = data.left[i];
								friend_newtop[uid] = data.top[i];

								//console.log('uid=' + uid + ' left=' + data.left[i] + ' top=' + data.top[i]);
						}
						moveFriendToNewLocation();

						if ( insidecircle != 0 ) {
								// change circle_title of the circle
								var ct = $('#circlebox_' + insidecircle).find('.circle_title');
								var active = false;
								ct.find('a').each ( function() {
										if ( $(this).hasClass('edit_circle') ) {
												active = true;
										}
												});
								if ( active == true ) {
								} else {
										ct.append('<br />');
										ct.append('<a href="#" class="edit_circle">Edit</a> | ');
										ct.append('<a href="#" class="delete_circle">Delete</a>');
								}
						} else {
								$('.edit_circle').each ( function() {
										$(this).parent().text( circlename[Number($(this).parent().parent().attr('name'))] );
								});
						}
						
				} else {
					alert('An error occurred during requesting to server');
				}
		}, "json");
}

// remove array of items from a circle
// if removefriendbox = true, hide that friend also
function removeElement(circle, items, removefriendbox)
{
		if (circle != 0) {
				var csrf_token = $('input[name=ci_csrf_token]').val();
				var circle_type = $('#circle_type').text();
				$.post(base_url + 'rm_loop_users', {circle:circle,items:items,ci_csrf_token:csrf_token, type:circle_type}, function(data) {
						if (data.status == 1) {
								// remove the element from circle
								var new_element_array = [];
								var j=0;
								for (var i=0; i<circle_element[circle].length; i++)
								{
									 if ( items.in_array(Number(circle_element[circle][i])) == false ) {
										  new_element_array[j++] = circle_element[circle][i];
									 } else {
									 }
								}
								circle_element[circle] = new_element_array.slice();

//								$('#circlebox_' + circle).find('.friendlist').find('.circle_element').each( function() {
//										if ( items.in_array( Number($(this).attr('name')) ) == true ) {
//											$(this).remove();
//										}
//								});

								if ( $('#circlebox_' + circle).find('.circle_boundary').data('status') == 'in' ) {
									showing_element( $('#circlebox_' + circle).find('.circle_boundary') );
								}
								refresh_user_in_circle();

								if ( removefriendbox == true ) {
										// hide selected friends
										$('.friendbox').each ( function() {
												if ( items.in_array( Number($(this).attr('name')) ) == true ) {
														$(this).fadeOut('slow');
												}
										});

										// arrange position of friendbox again
										for (var i=0; i < data.element.length; i++)
										{
												var uid = data.element[i];
												friend_newleft[uid] = data.left[i];
												friend_newtop[uid] = data.top[i];
						
												//console.log('uid=' + uid + ' left=' + data.left[i] + ' top=' + data.top[i]);
										}
										moveFriendToNewLocation();
								}

								$('#circlebox_' + circle).data('under_updating', false);
						} else {
								alert('An error occurred during requesting to server');
								$('#circlebox_' + circle).data('under_updating', false);
						}
				}, "json");
		}
}

// change name of a circle
function editcircle(ev)
{
		var x = ev.pageX - Math.round ( $('#edit_form').width() / 2 );
		var y = ev.pageY + 30 - Math.round ( $('#edit_form').height() / 2 );

		$('#edit_form').css('left', x + 'px');
		$('#edit_form').css('top', y + 'px');
		$('#edit_form').css('zIndex', 10000);
		$('#edit_form').css('position', 'fixed');

		$('#edit_circlename').val( circlename[insidecircle] );

		// show current setting of visibility
		if ( visibility == 0 ) {
			$('input[name="editcircle_visibility"][value="0"]').attr('checked', 'checked');
		} else {
			$('input[name="editcircle_visibility"][value="1"]').attr('checked', 'checked');
		}

		$('#edit_form').fadeIn('slow');
		$('#edit_circlename').focus();

		$('#edit_form').live('mouseleave', function() {
			$(this).fadeOut('slow');
		});
}

// showing a bar when moving circle
function showing_bar(circle, left)
{
	//
	var cp = $('#circlebox_' + circle).position();
	var bar = $('#bar');

	bar.show();
	bar.css('top', cp.top + 20 + 'px');

	// global variable
	circle_shift = circle;
	shifting_left = left;

	if ( left == true ) {
		// left
		bar.css('left', cp.left + 'px');
	} else {
		// right
		bar.css('left', cp.left + 190 + 'px');
	}
}

// moving circle box
function circlebox_shifting( from_circle, to_circle, shifting_left )
{

		var neworder = [];

		for (var i=0; i < circle_position_order.length; i++)
		{
				if ( circle_position_order[i] == from_circle ) {
					if ( shifting_left == true ) {
						neworder[ neworder.length ] = to_circle;
						neworder[ neworder.length ] = from_circle;
					} else {
						neworder[ neworder.length ] = from_circle;
						neworder[ neworder.length ] = to_circle;
					}
				} else if ( circle_position_order[i] == to_circle ) {
				} else {
					neworder[ neworder.length ] = circle_position_order[i];
				}
		}

		circle_position_order = neworder.slice();

		// adjust the location
		for (var i=0; i < circle_position_order.length; i++ )
		{
			circle_newleft[ circle_position_order[i] ] = circle_position_left[i];
			circle_newtop[ circle_position_order[i] ] = circle_position_top[i];
		}

}

function setCircleEvent()
{
	// set current circle as draggable & droppable
	// for friendbox moving on
	$('.circlebox').each( function() {
		  set_circlebox_draggable ( $(this) );
		  //set_boundary_droppable ( $(this).find('.circle_boundary') );
		   set_boundary_droppable ( $(this) );
	});

	// set droppable for all circle
	$('.circle_boundary').each ( function() {
		$(this).droppable({greedy: false});
	});

	// when moving over a circle
	$('.circlebox, .newcirclebox').each( function() {
		$(this).droppable();
	});

	$(".circle_boundary").each( function() {
		var div = $('.circleboxes:last');
		$(this).live('mouseenter', function(event) {
			if ( $('#circlebox_' + $(this).attr('name')).find('.circle_title').isBoxVisible(div) == true) {
				//console.log('entering..');
				circlebox_mouseenter( $(this) );
				//circlebox_mouseenter( event, $(this) );
				if ( Number($(this).attr('name')) != 0 ) {
					  //set_boundary_droppable ( $(this) );
					  set_boundary_droppable ( $(this).parent() );
					  set_circlebox_draggable ( $(this).parent() ) ;
				}
				return false;
			}
		});
	});
	
	// out of the circle -> resize
	$(".circle_boundary").each( function() {
		$(this).live('mouseleave', function(event) {
			//circlebox_mouseleave( event, $(this) );
			circlebox_mouseleave( $(this) );

			// remove eselected when moving out (to avoid deleted)
			$('.eselected_click').each ( function() {
				$(this).removeClass('eselected_click');
			});

			// hide the tooltip
			$('.username_tooltip').css('display','none');

			return false;
		});
	});


	$('.circlebox, .newcirclebox').each ( function() {
		$(this).live( 'dropover', function(event, ui) {
				//console.log('circlebox dropover');

				// if we are moving another circle -> show a bar
				var $boundary = $(this).find('.circle_boundary');

				if (circlebox_dragging == true) {
						if ( $boundary.attr('name') != 0 ) {
								var circle = Number( $boundary.attr('name') );
								if ( $('#circlebox_' + current_circle).position().left > $('#circlebox_' + circle).position().left ) {
									//console.log('right of ' + circlename[circle]);
									showing_bar( circle, false );
								} else {
									//console.log('left of ' + circlename[circle]);
									showing_bar( circle, true );
								}
						}
				} else {
						var div = $('.circleboxes:last');
						if ( $('#circlebox_' + $(this).attr('name')).find('.circle_title').isBoxVisible(div) == true) {
							enableReverting = false;
							$boundary.mouseenter();
							//console.log('revert = false');
						}
				}

				return false;
		});
	});

	$('.circlebox, .newcirclebox').each( function() {
		$(this).live( 'dropout', function(event, ui) {
				//console.log('circlebox dropout');
			
				var $boundary = $(this).find('.circle_boundary');

				if (circlebox_dragging == true) {
								//var circle = Number( $(this).attr('name') );
								var circle = Number( $boundary.attr('name') );

								if ( $('#circlebox_' + current_circle).position().left > $('#circlebox_' + circle).position().left ) {
									//console.log('right of ' + circlename[circle]);
									showing_bar( circle, false );
								} else {
									//console.log('left of ' + circlename[circle]);
									showing_bar( circle, true );
								}
				} else {
						enableReverting = true;
		
						//$(this).mouseleave();
						$boundary.mouseleave();
						//console.log('circlebox circleUserOut');
				}
				return false;
	});
	});

}


/**
 * set_list_instruction_position 
 *		set position of tooltip 
 */
function set_list_instruction_position()
{
	var $ref = $('.circleboxes:last');
	// $ref.css('height', $('body').height() - $ref.offset().top - $('#footer').height() - 30);

	var $ll = $('#loop_list_instructions');
	// align RIGHT border of loop_list_instructions = LEFT border of
	// circleboxes
	$ll.css('left', $ref.offset().left - $ll.width() );

	// adjust the menu bar
	$('#inside_circle_introduction').css('left', $ref.offset().left);

	// adjust footer (after circleboxes box)
	$('#footer').css('top', $ref.offset().top + $ref.height() + 10 ).css('position', 'absolute');

	// after having position of footer, calculate height of circle_ui_content
	var h1 = $('#circle_ui_content').offset().top;
	var h2 = $('#footer').offset().top ;

	$('#circle_ui_content').css('height', h2 - h1);
}

function update_resolution()
{
	// var width = screen.width;
	var width = $(window).width();
	
	// console.log(' width = ' + width);
	var csrf_token = $('input[name=ci_csrf_token]').val();
	var circle_type = $('#circle_type').text();

	$.post(base_url + 'update_resolution', {width:width, ci_csrf_token:csrf_token, type:circle_type}, function(data) {
		for (i=0; i < data.cid.length; i++) {
			circle_newleft[ data.cid[i] ] = data.left[i] ;
			circle_newtop [ data.cid[i] ] = data.top[i] ;
		}
		moveCircleToNewLocation();
	}, "json");
}
