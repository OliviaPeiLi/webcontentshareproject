// for using in_array() function
Array.prototype.in_array = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return true;
		}
	}
	return false;
}

Array.prototype.remove = function(index) {
  var newarray = new Array();
	for (var i in this) {
		if ( i != index ) {
				newarray[i] = this[i];
		}
	}
	return newarray;
}

// for trim a string
String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

// cancle propagation
function cancel_propagation(event) {
   if (event.stopPropagation){
       event.stopPropagation();
   }
   else if(window.event){
      window.event.cancelBubble=true;
   }
}

// check mouse inside an div or not
(function($){
	$.fn.isMouseInsideDiv=function(e)
	{
		// console.log( this );

		var pos = this.offset();
		var delta_x = e.pageX - pos.left;
		var delta_y = e.pageY - pos.top;
	
	
		if ( 0 <= delta_x && delta_x <= this.width() && 0 <= delta_y && delta_y <= this.height() ) {
			// console.log( 'cursorx = (' + e.pageX + ';' + e.pageY + ') = div=(' + pos.left+';'+pos.top+')'+'('+this.width()+';'+this.height()+') -> ('+delta_x+';'+delta_y+')TRUE' );
			return true;		// cursor is inside a jQuery obj
		}
		// console.log( 'cursorx = (' + e.pageX + ';' + e.pageY + ') = div=(' + pos.left+';'+pos.top+')'+'('+this.width()+';'+this.height()+') -> ('+delta_x+';'+delta_y+')FALSE' );
	
		return false;			// cursor is outside a jQuery obj
	}
})(jQuery);

// check friendbox inside visible area or not
// don't care left/right because the element is already inside this boundary
(function($){
	$.fn.isBoxVisible =function(div)
	{
		var tt = this.offset().top;
		var	tb = tt + this.height();

		var dt = div.offset().top;
		var db = dt + div.height();

		return ( ( dt <= tt && tt <= db ) || ( dt <= tb && tb <= db ) );
	}
})(jQuery);

