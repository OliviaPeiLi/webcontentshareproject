/* *********************************************************
 * List Edit (not used anywhere)
 *	legacy JS, not used anymore.
 *
 * ******************************************************* */

define(['plugins/hoverintent', 'jquery'],function() {

$(function() {
	
	$(window).load(function() {
	/*
		if($('#list_manage_pages').height() < 400) {
			$('#list_manage_pages').css('height', '400px');
		}
	*/
	
		var trash_icon = "<div class='inlinediv trash_icon'><a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a></div>";
		$('#list_manage_pages li').each(function(indx) {
			//alert(indx);
			$(trash_icon).appendTo(this);
		});

		$('#list_followed_pages').css('max-height', $(window).height()-200+'px');
		//var $to = $('.drag_to');
		increase_drop_height($('#list_followed_pages').find('li:first-child'));
		
	});
	
	$('#list_manage_pages li').hoverIntent({over: display_detail_on_hover, timeout: 200, interval: 200, out: hide_detail_on_hover});
	$('#list_followed_pages li').hoverIntent({over: display_detail_on_hover, timeout: 200, interval: 200, out: hide_detail_on_hover});
	$('#page_detail').live('mouseout',function(e) {
		$(this).fadeOut();
		$(this).remove();
		return false;
	});
	
	
	function display_detail_on_hover() {
		var detail = '<div>'+$(this).find('.page_name').text()+'</div>';
		detail = '<div id="page_detail">'+detail+'</div>';
		var detail_left = $(this).offset().left;
		var detail_top = $(this).offset().top;
		$(detail)
			.css('top', detail_top+'px')
			.css('left', detail_left+'px')
			.appendTo('body');
		$('#page_detail').fadeIn(500);
	}
	function hide_detail_on_hover() {
		$('#page_detail').hide();
		$('#page_detail').remove();
	}
	
	$('#list_edit_basic_info_lnk').live('click', function() {
		$('#list_edit_basic_info').toggle('blind');
		return false;
	});
	
	$('#save_list').live('click', function() {
		//alert('saving list data');
		$('#list_pages').val('array(');
		$('#list_manage_pages li').each(function(indx) {
			//alert($(this).find('.page_name').attr('value'));
			var pg_id = $(this).find('.page_id').text();
			if (pg_id !== '') {
				//$('#list_pages').val($('#list_pages').val()+indx+'=>'+pg_id+', ');
				$('form').append('<input type="hidden" name="check_pages['+indx+']" id="list_pages" value="'+pg_id+'"/>');
			}
		});
		$('#list_pages').val($('#list_pages').val()+')');
		return true;
	});

	var $from = $('.drag_from');
	var $to = $('.drag_to');
		
		// let the gallery items be draggable
	$( 'li', $from ).draggable({
		cancel: "a.ui-icon", // clicking an icon won't initiate dragging
		revert: "invalid", // when not dropped, the item will revert back to its initial position
		containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
		helper: "clone",
		cursor: "move"
	});
	$( 'li', $to ).draggable({
		cancel: "a.ui-icon", // clicking an icon won't initiate dragging
		revert: "invalid", // when not dropped, the item will revert back to its initial position
		containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
		helper: "clone",
		cursor: "move"
	});
	$to.droppable({
		accept: ".drag_from > li",
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) {
			addToList( ui.draggable );
		}
	});
	$from.droppable({
		accept: ".drag_to li",
		activeClass: "custom-state-active",
		drop: function( event, ui ) {
			deleteFromList( ui.draggable );
		}
	});
	
	// image deletion function
	var trash_icon = "<div class='inlinediv trash_icon'><a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a></div>";
	function addToList( $item ) {
		increase_drop_height($item);
		$item.fadeOut(function() {
			var $list = $( "ul", $to ).length ?
				$( "ul", $to ) :
				$( "<ul class='drag_from ui-helper-reset'/>" ).appendTo( $to );

			$item.append( trash_icon ).appendTo( $list ).fadeIn(function() {
			
				var pg_name = $item.find('.page_name').text();
				//alert(pg_name);
				$item.find(".page_name_abr").hide()
				$item
					.find( "img" )
						.animate({ height: "60px", width: "60px" });
				//$item.attr('title', pg_name);
			});
		});
	}
	
	function increase_drop_height($item) {
		var len = $to.find('li').length;
		if ($to.height() < ((len/5)+1)*$item.height()) {
			$to.height(((len/5)+1)*$item.height());
		}
		var curr_height = $to.height();
		if (len%5 > 3) {
			$to.animate({height: curr_height+$item.height()+5});
		}		
	}
	function decrease_drop_height($item) {
		var len = $to.find('li').length;
		if ($to.height() < ((len/5)+1)*$item.height()) {
			$to.height(((len/5)+1)*$item.height());
		}
		var curr_height = $to.height();
		if (len%5 === 0) {
			$to.animate({height: curr_height-$item.height()});
		}		
	}

	// image recycle function
	var recycle_icon = "<div class='inlinediv'><a href='link/to/recycle/script/when/we/have/js/off' title='Recycle this image' class='ui-icon ui-icon-refresh'>Recycle image</a></div>";
	function deleteFromList( $item ) {
		$item.fadeOut(function() {
			$item
				.find( "a.ui-icon-trash" )
					.remove()
				.end()
				.appendTo( '#list_followed_pages' )
				.fadeIn(function() {
					$item
						.find('.page_name_abr').show()
						.find( "img" )
							.animate({ height: "30px" })
							.animate({ width: "30px" })
				});		
		});
		decrease_drop_height($item);
	}
	
	// image preview function, demonstrating the ui.dialog used as a modal window
	function viewLargerImage( $link ) {
		var src = $link.attr( "href" ),
			title = $link.siblings( "img" ).attr( "alt" ),
			$modal = $( "img[src$='" + src + "']" );

		if ( $modal.length ) {
			$modal.dialog( "open" );
		} else {
			var img = $( "<img alt='" + title + "' width='384' height='288' style='display: none; padding: 8px;' />" )
				.attr( "src", src ).appendTo( "body" );
			setTimeout(function() {
				img.dialog({
					title: title,
					width: 400,
					modal: true
				});
			}, 1 );
		}
	}

	// resolve the icons behavior with event delegation
	$( "ul.drag_from > li" ).click(function( event ) {
		var $item = $( this ),
			$target = $( event.target );
		if ( $target.is( "a.ui-icon-trash" ) ) {
			deleteFromList( $item );
		} else if ( $target.is( "a.ui-icon-refresh" ) ) {
			addToList( $item );
		}

		return false;
	});
	// resolve the icons behavior with event delegation
	$( "ul.drag_to > li" ).click(function( event ) {
		var $item = $( this ),
			$target = $( event.target );
		if ( $target.is( "a.ui-icon-trash" ) ) {
			deleteFromList( $item );
		} else if ( $target.is( "a.ui-icon-refresh" ) ) {
			addToList( $item );
		}

		return false;
	});
});

});
