/**
 * ajax active List <li> inside <ul>
 * assumes the vollowing sample structure:
 * <div rel class="item">
 * 	<a href class="item_link">
 *	 <img src title class="item_img">
 *  </a>
 *  <a href class="del_item">x</a>
 * </div>
 * @uses jquery
 * @to-do - this is a very good plugin unfortonately isnt used
 */
define(['jquery'], function() {

	/**
	 * A shortcut for Tmpl.render( $( '#template' ), data, renderer )
	 *
	 * Example:
	 *
	 * <script type="template/html" id="template" data-img="li img->src">
	 * 	<li><img src="" /></li>
	 * </script>
	 * Selectors are in the template to be easier for the designer to update
	 * the html without touching the js 
	 * -> selector - replaces attribute
	 * default selector - replaces html 
	 * @param data - the data with which the template(s) will be populated
	 * @param callback - for more complex operations - when the selectors are not enough. In the callback
	 *                   function you can use $(this) as reference to the newly created object
	 * var data = [{img: 'someimage.png'}, {img: 'otherimage.png'}];
	 * var $items = $( '#template' ).tmpl( data, function(data) {
	 * 												$(this).find('.up_box').show();
	 * 											});
	 * $( 'ul' ).append( $items );
	 */

	function render ( html, data, callback ) {
		var ret = $('<div>'+html+'</div>');

		function attrValue ( attrName ) {
			if (!data) return null; 
			if ( attrName.indexOf( '-' ) >= 0 ) {
				attrName = attrName.split( '-' );
				var obj = data;
				for ( var i = 0; i < attrName.length && obj instanceof Object; ++i ) {
					obj = obj[ attrName[i] ];
				}
				return obj;
			}
			else {
				return data[ attrName ];
			}
		}

		console.info('{ajaxList} - data', data);
		var attrs = this[0].attributes;
		for ( var i = 0; i < attrs.length; ++i ) {
			
			var attrName = attrs[i].name;
			if ( attrName.substr( 0, 5 ) != 'data-' ) {
				continue;
			}
			attrName = attrName.substr( 5, attrName.length );
			var selectors = attrs[i].value;
			selectors = selectors && selectors.length > 0 ? selectors.split( ',' ) : [];
			
			for ( var j = 0; j < selectors.length; ++j ) {
				var selector = selectors[j];
				if (!selector) {
					//console.warn("Attribute data-"+j+" not found in ", this[0]);
					continue;
				}
				var p = selector.indexOf( '@' );
				if ( p > -1 ) {
					var attr = selector.substr( p + 1, p.length );
					selector = selector.substr( 0, p ).trim();
	
					var el = ret.find( selector );

					if ( el.length == 0 ) {
						console.warn(selector, ' element not found in: ', ret);
					}

					// we need each because the attr getter will set all attributes
					// to the value of the one in the first element in the set
					el.each( function () {
						var el = $( this );
						var attr_val = el.attr( attr );

						if ( attr_val && attr_val.indexOf( '-1' ) > -1 ) { //replace links like  /add_like/drop/-1
							el.attr( attr, attr_val.replace( '-1', attrValue( attrName ) ) );
						} else {
							el.attr( attr, attrValue( attrName ) );
						}
					} );

				} else {
					ret.find( selector ).html( attrValue( attrName ) );
				}
			}
		}

		if (callback instanceof Function) {
			callback.call(ret, data);
		}

		return ret.find('> *');
	}

	jQuery.fn.tmpl = function ( data, callback ) {

		if ( this.length == 0 ) {
			return null;
		}

		var html = this.html();
		if ( data instanceof Array ) {
			console.info('{ajaxList} - array', data);
			var ret = [];
			ret.length = data.length;
			for ( var i = 0; i < data.length; ++i ) {
				ret[i] = render.call( this, html, data[i], callback )[0];
			}
			return $(ret);
		}
		else {
			return render.call( this, html, data, callback );
		}
	};

});
