/**
 *  Mentions library. Changed to work with the bookmarklet. Mentions are used in the bookmarklet
 *  preview popup where the user can enter some text about the drop
 *  @see - clipboard_ui.js
 */

jQuery.fn.mentions = function(options) {
	var last_val_length, target;
	
    function alpha_sort(a,b) {
	    var aName = a.value.toLowerCase();
	    var bName = b.value.toLowerCase(); 
	    return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
    }
    
    function show_mention(value) {
    	return value.lastIndexOf('@') > value.lastIndexOf(']')
    	     && value.lastIndexOf('@') > value.lastIndexOf(' ')
    	     && value.lastIndexOf('@') < value.length-1
    }

	jQuery(this)
		// don't navigate away from the field on tab when selecting an item
		.on( "keydown keyup keypress", function( e ) {
			if ( e.keyCode == 9 || e.keyCode == 13) {
				e.preventDefault();
			}
			if (this.search_list && this.search_list.find('li').length && (e.keyCode == 38 || e.keyCode == 40)) {
				console.info('PREVENT');
				e.preventDefault();
			}
		})	
		.on("blur", function() {
			if (this.search_list && this.search_list.find('li').length) { //Select item
				var sel  = this.search_list.find('a.ui-state-hover');
				this.search_list.hide();
				$(this).closest('form').removeClass('loading');
				
				if (!this.matches) this.matches = {};
				this.matches[sel.text()] = '@['+sel.attr('rel')+':'+sel.text()+']';
				
				this.value = this.value.substring(0, this.value.lastIndexOf("@")) + '@' + sel.attr('rel').split(':')[1];
				
				console.info(this, sel, this.value);
				var target_val = this.value;
				for (var i in this.matches) target_val = target_val.replace(i, this.matches[i]);
				this.target.val(target_val);
				$(this).focus();
			}
		})
		.on("keyup", function(e){
			if (!this.matches) this.matches = {};
			var self = this;
			var jQueryself = jQuery(this);
			this.target = jQuery(this).parent().find('.fd_mentions_target');
			if (!this.target.length) {
				jQuery(this).after('<textarea style="display:none" name="'+jQuery(this).attr('name')+'" class="fd_mentions_target"></textarea>');
				jQuery(this).attr('name', jQuery(this).attr('name')+'_orig');
				this.target = jQuery(this).parent().find('.fd_mentions_target');
			}
			var target_val = this.value;
			for (var i in this.matches) target_val = target_val.replace(i, (this.matches[i]).substring(1));
			this.target[0].value = target_val;
			console.info(this.target[0].value);
			
			if (e.keyCode == 13) {
				if (this.search_list && this.search_list.find('li').length) $(this).blur();
				e.preventDefault();
				return;
			} else if (e.keyCode == 38) { //up
				var prev = this.search_list.find('li a.ui-state-hover').parent().prev(); 
				if (prev.length) {
					this.search_list.find('li a.ui-state-hover').removeClass('ui-state-hover');
					prev.find('a').addClass('ui-state-hover');
				}
				e.preventDefault();
				return;				
			} else if (e.keyCode == 40) { //down
				var next = this.search_list.find('li a.ui-state-hover').parent().next(); 
				if (next.length) {
					this.search_list.find('li a.ui-state-hover').removeClass('ui-state-hover');
					next.find('a').addClass('ui-state-hover');
				}
				e.preventDefault();
				return;				
			}
			
			if (show_mention(this.value)) {
				options.search.call(this, this.value, function(result) {
					if (!self.search_list) {
						self.search_list = jQuery('<ul/>').addClass('ui-autocomplete').addClass('ui-menu')
														.addClass('ui-widget').addClass('ui-widget-content').addClass('ui-corner-all')
														.css({
															'zIndex': 2147483647, 'position': 'absolute',
															'width': jQueryself.width()
														})
						self.search_list.appendTo(jQuery('body'));
						self.search_list.css({
							'left': jQueryself.offset().left,
							'top': jQueryself.offset().top+jQueryself.height()
						});
					}
					self.search_list.html('');
					console.info('FORM', $(self).closest('form'));
					if (!result.length) {
						$(self).closest('form').removeClass('loading');
						self.search_list.hide();
						return;
					}
					self.search_list.show();
					$(self).closest('form').addClass('loading');
					
					result.sort(alpha_sort)
					var li;
					for (var i=0;i<result.length;i++) {
						li = jQuery('<li/>').addClass('ui-menu-item');
						li.append('<a class="ui-corner-all" tabindex="-1" rel="'+result[i].id+'">'+result[i].value+'</a>');
						if (i==0) li.find('a').addClass('ui-state-hover');
						self.search_list.append(li);
						li.find('a').hover(function() {
							jQuery(this).closest('ul').find('.ui-state-hover').removeClass('ui-state-hover');
							jQuery(this).addClass('ui-state-hover');
						}, function() {
							jQuery(this).removeClass('ui-state-hover');
						});
					}
					self.search_list.show();
					console.info('Result: ', self.search_list);
				});
			} else if (self.search_list) {
				self.search_list.html('').hide();
			}
			
			
		});
}