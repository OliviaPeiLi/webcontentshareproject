define(['jquery'], function(){

  	var methods = {
    	init : function( options ) { 

    	},
	    add_list_comment : function( comm_id, comm_body, del_comm_url, like_comm_url, unlike_comm_url ) {
			var sample = jQuery(this).find('.sample');
			console.log(sample);
			var new_comm = sample.clone();
			new_comm.removeClass('sample');
			new_comm.attr('rel', comm_id);
			new_comm.find('.comment_content').html(comm_body);
			new_comm.find('.delete_comment').attr('href', del_comm_url);
			new_comm.find('.newsfeed_entry_comment_options .up_button').attr('href',like_comm_url);
			new_comm.find('.newsfeed_entry_comment_options .undo_up_button').attr('href',unlike_comm_url);
			new_comm.show();
			console.log(new_comm);
			return new_comm;
	    },
	    add_tile_comment : function( msg_id, msg_body, del_msg_url ) {
			var sample = jQuery(this).find('.sample');
			var new_msg = sample.clone();
			new_msg.removeClass('sample');
			new_msg.find('.body .message_body').text(msg_body);
			new_msg.find('.delete_float > a').attr('href', del_msg_url);
			return new_msg;
	    },
	    add_timeline_comment : function( msg_id, msg_body, del_msg_url ) {
			var sample = jQuery(this).find('.sample');
			var new_msg = sample.clone();
			new_msg.removeClass('sample');
			new_msg.find('.body .message_body').text(msg_body);
			new_msg.find('.delete_float > a').attr('href', del_msg_url);
			return new_msg;
	    },	   
	    add_postcard_comment : function( msg_id, msg_body, del_msg_url, like_url, unlike_url ) {
			console.log('add_postcard_comment');
			var sample = jQuery(this).find('.sample');
			console.log(sample);
			var new_msg = sample.clone();
			new_msg.removeClass('sample');
			new_msg.attr('rel', msg_id);
			new_msg.find('.comment_body').text(msg_body);
			new_msg.find('.delete_comment').attr('href', del_msg_url);
			new_msg.find('.up_button').attr('href', like_url);
			new_msg.find('.undo_up_button').attr('href', unlike_url);
			return new_msg;
	    }	    

  	};

  	jQuery.fn.commentList = function( method ) {
    	console.log('init commentList');
	    // Method calling logic
	    if ( methods[method] ) {
	      	return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
	    } else if ( typeof method === 'object' || ! method ) {
	      	return methods.init.apply( this, arguments );
	    } else {
	      	jQuery.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
	    }    
	  
	};
});
