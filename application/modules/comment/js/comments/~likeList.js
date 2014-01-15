/* *********************************************************
 * List of Likes (for structures where <li> of likes is inside <ul>
 *
 * ******************************************************* */

define(['jquery'], function(){

  	var methods = {
    	init : function( options ) { 
    		/*
			this.bind('like',function(data){
				console.log('ADD LIKE');
				var sample = $(this).find('.sample');
				var new_like = sample.clone();
				//new_like.removeClass('sample').find('.avatar').attr('src',data.thumb).attr('title',data.first_name+' '+data.last_name);
				new_like.removeClass('sample');
				console.log(new_like);
				$(this).prepend(new_like);
				new_like.show('fade');
			});
			this.bind('unlike',function(data,uid){
				console.log('uid='+uid);
				console.log('DELETE LIKE');
				var like = $(this).find('.like[rel='+uid+']');
				like.hide().remove();
			});
			*/

    	},
	    like : function( data ) {
			console.log('ADD LIKE');
			jQuery('#preview_more_info_likes').css('display','block');
			var sample = jQuery(this).find('.sample');
			var new_like = sample.clone();
			//new_like.removeClass('sample').find('.avatar').attr('src',data.thumb).attr('title',data.first_name+' '+data.last_name);
			new_like.removeClass('sample');
			console.log(new_like);
			jQuery(this).prepend(new_like);
			jQuery('.no_likes').hide();
			new_like.show('fade');

	    },
	    unlike : function( data, uid ) { 
			console.log('uid='+uid);
			console.log('DELETE LIKE');
			var like = jQuery(this).find('.like[rel='+uid+']:not(.sample)');
			like.hide().remove();
			if (jQuery('.like').length <= 1) {
				jQuery('.no_likes').show('fade');
			}
	    }
  	};

  	jQuery.fn.likeList = function( method ) {
    	console.log('init likeList');
	    // Method calling logic
	    if ( methods[method] ) {
	      	return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
	    } else if ( typeof method === 'object' || ! method ) {
	      	return methods.init.apply( this, arguments );
	    } else {
	      	jQuery.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
	    }    
	  
	};
/*
	jQuery.fn.likeList = function(method) {
		console.log('init likeList');
		if (method === 'init') {
			this.bind('like',function(data){
				console.log('ADD LIKE');
				var sample = $(this).find('.sample');
				var new_like = sample.clone();
				//new_like.removeClass('sample').find('.avatar').attr('src',data.thumb).attr('title',data.first_name+' '+data.last_name);
				new_like.removeClass('sample');
				console.log(new_like);
				$(this).prepend(new_like);
				new_like.show('fade');
			});
			this.bind('unlike',function(data,uid){
				console.log('uid='+uid);
				console.log('DELETE LIKE');
				var like = $(this).find('.like[rel='+uid+']');
				like.hide().remove();
			});
		} else if (method === 'like') {
			this.trigger('like');
		} else if (method === 'unlike') {
			this.trigger('unlike');
		}
	}
*/
});
