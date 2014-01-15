/**
 *  Used for textareas when the user types @. An autocomplete with search for users will apear
 *  once he chooses someone the text @[{user_id}:{user_name}] will be appended to the textarea
 */
define(['jquery','jquery-ui'], function() {
	function is_mention_active(el, target) {
		return el.value.lastIndexOf('@') > target.value.lastIndexOf(']')
			&& el.value.lastIndexOf('@') > el.value.lastIndexOf(' ')
		    && el.value.lastIndexOf('@') < el.value.length-1;
	}
	
	function alpha_sort(a,b) {
	    var aName = a.value.toLowerCase();
	    var bName = b.value.toLowerCase(); 
	    return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
    }    
	
	var last_val_length;
	$( ".fd_mentions" )
		// don't navigate away from the field on tab when selecting an item
		.on( "keydown", function( e ) {
			if ( e.keyCode === $.ui.keyCode.TAB && $( this ).data( "autocomplete" ) && $( this ).data( "autocomplete" ).menu.active ) {
				e.preventDefault();
			}
		})		
		.on("keyup", function(e){
			var $this = $(this);
			if (!this.matches) this.matches = {};
			var target = $(this).parent().find('.fd_mentions_target');
			if (!target.length) {
				$(this).after('<textarea style="display:none" name="'+$(this).attr('name')+'" class="fd_mentions_target"></textarea>');
				$(this).attr('name', $(this).attr('name')+'_orig');
				console.warn('keyup',this);
				target = $(this).parent().find('.fd_mentions_target');
			}
			var target_val = this.value;
			for (var i in this.matches) target_val = target_val.replace(i, (this.matches[i]).substring(1));
			target.val(target_val);
			if (! $(this).hasClass('ui-autocomplete-input') && is_mention_active(this, target[0]) ) {
				var newsfeed_id = $(this).attr('data-comment_newsfeed_id');
				if (!newsfeed_id) $(this).closest('.newsfeed_entry').attr('data-newsfeed_id');
				var newsfeed_id_param = newsfeed_id ? '&newsfeed_id='+newsfeed_id : ''; 
				$(this).autocomplete({
					autoFocus: "true",
					minLength: 2,
					search: function(event, ui) {
						return is_mention_active(this, target[0]);
					},
					source: '/search/ajax_people?mentions=true'+newsfeed_id_param, 
					focus: function() {
						// prevent value inserted on focus
						return false;
					},
					//RR - doesnt work in this version of jquery-ui
					//response: function( event, ui) {
					//	console.info('response', ui.content);
					//	//ui.content.sort(alpha_sort)
					//},
					select: function( event, ui ) {
						if (!this.matches) this.matches = {};
						this.matches[ui.item.value] = '@['+ui.item.id+':'+ui.item.value+']';
					
						mention_acc = ui.item.id.split(':')[1];
						mention_id  = ui.item.id.split(':')[0];
						label = '<a href="'+ mention_acc +'" target="_blank" class="link_in_comment show_badge" data-user_id="'+mention_id+'">@'+mention_acc+'</a>';

						this.value = this.value.substring(0, this.value.lastIndexOf("@")) + '@' + ui.item.id.split(':')[1]; //Added @ to name inside input box
						this.value_html = this.value.substring(0, this.value.lastIndexOf("@")) + label;
						
						var target = $(this).parent().find('.fd_mentions_target');
						var target_val = this.value;
						for (var i in this.matches) target_val = target_val.replace(i, this.matches[i]);
						target.val(target_val);
						console.log('select destroy');
						$(this).autocomplete('destroy');
						setTimeout( function() { $this.closest('form').removeClass('loading'); }, 200);
						return false;
					}, open: function() {
						//added to avoid form submit while selecting mention
						$(this).closest('form').addClass('loading');
					}, close: function() {
						$(this).autocomplete('destroy');
						 $this.closest('form').removeClass('loading');
					}
				}) // end $(this).autocomplete
			} 
		});

});
