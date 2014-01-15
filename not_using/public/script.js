									/* CONSTANTS */
		
var animationInterrupt = false, enableReverting = true;

									/* Effects */

	if ( $.browser.msie ) {
		// if IE browser
		var div = 1;
		var duration = 30;
	} else {
		// if other browser
		var div = 20;
		var duration = 30;
	}

function cursorCatcher(elemToMove, ev) {
	if ( elemToMove.data('catcherCounter') == 1 ) {
//			var desX      = ev.pageX;
//			var desY      = ev.pageY;
			var desX      = window.offsetX;
			var desY      = window.offsetY;
	} else {
		if ( $('#helper-container').position().left != null ) {
			var desX      = $('#helper-container').position().left;
			var desY      = $('#helper-container').position().top;
		}
	}

	var currentX  = elemToMove.position().left;
	var currentY  = elemToMove.position().top;

	var newStartX = currentX + 1/div * elemToMove.data('catcherCounter') * ( desX - currentX );
	var newStartY = currentY + 1/div * elemToMove.data('catcherCounter') * ( desY - currentY );

	if ( $.browser.msie ) {
			// IE has problem with animation so that making it simple
			elemToMove.data('catcherCounter', 1);
			elemToMove.hide();
	 		$('#helper-container').append('<div class="user-helper-multidragging" style="left:0px;top:0px;z-index:5;"></div>')
								.append($('.user-multidragging-animation').hide());
	} else {
		elemToMove.animate ( {left: newStartX, top: newStartY},
			 {duration: duration,
			  complete: function() {
				  if (Number(elemToMove.data('catcherCounter')) < div && !animationInterrupt) {
				  		elemToMove.data('catcherCounter', Number(elemToMove.data('catcherCounter')) + 1);
				  		cursorCatcher(elemToMove, ev);
				  } else {
							elemToMove.data('catcherCounter', 1);
				  		if (!animationInterrupt) {
								elemToMove.hide();
								// testing performance
				  			// $('#helper-container').append('<div class="user-helper-multidragging" style="left:0px;top:0px;z-index:5;"></div>').append($('.user-multidragging-animation').hide());
							}
							//console.log( 'captured = ' + Date() );
				  }
			  }
	   });
	}
}

function revertMultidrag() { // revert multi user drag

		$('.user-helper-multidragging').remove();

		$('.friendbox_rec').each(function(){
			var pos = $('#friendboxes').children('.friendbox[name="'+$(this).data('name')+'"]').offset();

			$(this).show().animate(
				{left: pos.left, top: pos.top},
				{duration: 1000,
				 complete: function() {
				 	$(this).remove();
				 }
				}
				);
		});
}

function createFriendboxRectangle( left, top, fid, father )
{
	father.append('<div class="friendbox_rec" style="top:'+top+'px;left:'+left+'px;" fid="'+fid+'"></div>');
}

function multidraggingManager(draggingElem, ev) {

	var selectedUsers = $('.friendbox.selected').not(draggingElem);
	
	// $('#helper-container').append(draggingElem.clone().attr('id','').removeClass('selected'));
//	var tmp = draggingElem.clone();
//	tmp.attr('id','').removeClass('selected');
//	tmp.css('top', '5px');
//	tmp.css('left', '5px');
//	tmp.css('z-index', '10');

	$('#helper-container').append(draggingElem.clone().attr('id','').removeClass('selected').css('top','5px').css('left','5px').css('z-index','10'));
	// $('#helper-container').html( tmp );

	if (selectedUsers.length>0) {
		$('#helper-container').append('<div class="user-helper-multidragging" style="left:0px;top:0px;z-index:5;"></div>');
	
		var div	=	$('.friendboxesfatherdummy:last');
		selectedUsers.each(function() {

			// only cursorCatcher for friendbox that is beeing seen
			if ( $(this).isBoxVisible( div ) ) {

//			cursorCatcher( $(this).clone().addClass('user-multidragging-animation')
//									.removeClass('drag-original')
//									.appendTo('body').data('catcherCounter', 1), ev );
			var obj_o = $('#friendboxes').children('.friendbox[name="'+$(this).attr('name')+'"]').offset();
			cursorCatcher($('<div class="friendbox_rec" style="top:'+obj_o.top+'px;left:'+obj_o.left+'px;" uid="'+$(this).attr('name')+'"></div>').data({name:$(this).attr('name')}).appendTo('body').data('catcherCounter',1), ev);

			}

		});
	}
}

