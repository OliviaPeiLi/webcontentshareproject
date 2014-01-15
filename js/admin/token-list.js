/*
 * jQuery Plugin: Tokenizing Autocomplete Text Entry
 * Version 1.6.0
 *
 * Copyright (c) 2009 James Smith (http://loopj.com)
 * Licensed jointly under the GPL and MIT licenses,
 * choose which one suits your project best!
 *
 */
// Default settings
var DEFAULT_CLASSES = {
	// Default classes to use when theming
    tokenList: "token-input-list",
    token: "token-input-token",
    tokenDelete: "token-input-delete-token",
    selectedToken: "token-input-selected-token",
    highlightedToken: "token-input-highlighted-token",
    dropdown: "token-input-dropdown",
    dropdownItem: "token-input-dropdown-item",
    dropdownItem2: "token-input-dropdown-item2",
    selectedDropdownItem: "token-input-selected-dropdown-item",
    inputToken: "token-input-input-token"
}

var DEFAULT_SETTINGS = {
	// Search settings
    method: "GET",
    contentType: "json",
    queryParam: "q",
    searchDelay: 300,
    minChars: 1,
    propertyToSearch: "name",
    jsonContainer: null,

	// Display settings
    hintText: typeof php != 'undefined' && php.hinttext ? php.hinttext : 'Type in a search term',
    // The search is done in both of topic/people , drop, but it returns "No results" because of no topic/people
    // there are some drop available. Alexi asked to change this case as search are typing
    noResultsText: "No exact results, press Enter to see other results",
    searchingText: "Searching...",
    deleteText: "&times;",
    animateDropdown: false,
    validate: '',
    validate_errors: {},

	// Tokenization settings
    tokenLimit: null,
    tokenDelimiter: ",",
    preventDuplicates: false,
    alphaSort: false,
    allowNullSelect: false,
    create_only : false,

	// Output settings
    tokenValue: "id",

	// Prepopulation settings
    prePopulate: null,
    processPrePopulate: false,

	// Manipulation settings
    idPrefix: "token-input-",

	// Formatters
    resultsFormatter: function(item){ return "<li>" + item[this.propertyToSearch]+ "</li>"; },
    tokenFormatter: function(item) { return "<li><p>" + item[this.propertyToSearch] + "</p><span class='ico'></span></li>"; },

	// Callbacks
    onResult: null,
    onAdd: null,
    onDelete: null,
    onRey: null,
    
    //Keyboard/click operations
    disableEnter: false
};

// Input box position "enum"
var POSITION = {
    BEFORE: 0,
    AFTER: 1,
    END: 2
};

// Keys "enum"
var KEY = {
    BACKSPACE: 8,
    TAB: 9,
    ENTER: 13,
    ESCAPE: 27,
    SPACE: 32,
    PAGE_UP: 33,
    PAGE_DOWN: 34,
    END: 35,
    HOME: 36,
    LEFT: 37,
    UP: 38,
    RIGHT: 39,
    DOWN: 40,
    NUMPAD_ENTER: 108,
    COMMA: 188
};

// Additional public (exposed) methods
var methods = {
    init: function(url_or_data_or_function, options, default_data) {
    //init: function(url_or_data_or_function, options) {
        var settings = jQuery.extend({}, DEFAULT_SETTINGS, options || {});

        return this.each(function () {
        	try {
            	jQuery(this).data("tokenInputObject", new jQuery.TokenList(this, url_or_data_or_function, settings, default_data));
        	} catch (e) {
        		console.info('ERROR', e);
        	}
        });
    },
    destroy: function() {
    	if (typeof this.data("tokenInputObject") != 'undefined') {
        	this.data("tokenInputObject").destroy();
        	return this;
        } else {
        	return false;
        }
    },
    option: function(opt, value) {
    	if (typeof this.data("tokenInputObject") != 'undefined') {
        	this.data("tokenInputObject").option(opt, value);
        	return this;
        } else {
        	return false;
        }
    },
    clear: function() {
    	if (typeof this.data("tokenInputObject") != 'undefined') {
        	this.data("tokenInputObject").clear();
        	return this;
        } else {
        	return false;
        }
    },
    add: function(item) {
    	if (typeof this.data("tokenInputObject") != 'undefined') {
	        this.data("tokenInputObject").add(item);
	        return this;
        } else {
        	return false;
        }
    },
    remove: function(item) {
    	if (typeof this.data("tokenInputObject") != 'undefined') {
        	this.data("tokenInputObject").remove(item);
        	return this;
        } else {
        	return false;
        }
    },
    get: function() {
    	if (typeof this.data("tokenInputObject") != 'undefined') {
    		return this.data("tokenInputObject").getTokens();
    	} else {
    		return false;
    	}
   	},
   	exists: function() {
   		return (typeof this.data("tokenInputObject") != 'undefined');
   	}
}

if (typeof define != 'function' || (window.location.href.indexOf('ft/') < 0 
									&& window.location.href.indexOf('localhost') < 0
									&& window.location.href.indexOf('fantoon.local') < 0
									&& window.location.href.indexOf('fandrop.com') < 0 
									&& window.location.href.indexOf('localhost') < 0 
									&& window.location.href.indexOf('fantoon.com') < 0)
) {
	define = function(name, deps, callback) {
		if (typeof callback == 'function') {
			callback.call(this); 
		} else {
			deps.call(this); 
		}
	}
}

define(['jquery'], function() {
	// Expose the .tokenInput function to jQuery as a plugin
	jQuery.fn.tokenInput = function (method) {
	    // Method calling and initialization logic
	    if(methods[method]) {
	        return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
	    } else {
	        return methods.init.apply(this, arguments);
	    }
	};
	
	// TokenList class for each input
	jQuery.TokenList = function (input, url_or_data, settings, default_data) {

	//jQuery.TokenList = function (input, url_or_data, settings) {
	    //
	    // Initialization
	    //
		if (!settings.width) settings.width = jQuery(input).width() ? jQuery(input).width() : 290;
	
	    // Configure the data source
	    if(typeof url_or_data === "string" || jQuery.isFunction(url_or_data) === "function") {
	        // Set the url to query against
	        settings.url = url_or_data;
	
	        // If the URL is a function, evaluate it here to do our initalization work
	        var url = computeURL();
	
	        // Make a smart guess about cross-domain if it wasn't explicitly specified
	        if(settings.crossDomain === undefined) {
	            if(url.indexOf("://") === -1) {
	                settings.crossDomain = false;
	            } else {
	                settings.crossDomain = (location.href.split(/\/+/g)[1] !== url.split(/\/+/g)[1]);
	            }
	        }
	    } else if(typeof(url_or_data) === "object" || typeof(url_or_data) === "array") {
	        // Set the local data to search through
	        settings.local_data = url_or_data;
	    }
	    
        if (typeof default_data == 'string' && default_data.length > 0)	{
        	default_data = eval(default_data);
        }
	    
	    // Configure the data source for default list data (Optional variable)
		if(typeof(default_data) === "object" || typeof(default_data) === "array") {
	        // Set the local data to search through
	        settings.local_data_default = default_data;
	    }
	    
	    // Build class names
	    if(settings.classes) {
	        // Use custom class names
	        settings.classes = jQuery.extend({}, DEFAULT_CLASSES, settings.classes);
	    } else if(settings.theme) {
	        // Use theme-suffixed default class names
	        settings.classes = {};
	        jQuery.each(DEFAULT_CLASSES, function(key, value) {
	            settings.classes[key] = value + "-" + settings.theme;
	        });
	    } else {
	        settings.classes = DEFAULT_CLASSES;
	    }
	
	    // Save the tokens
	    var saved_tokens = [];
	
	    // Keep track of the number of tokens in the list
	    var token_count = 0;
	
	    // Basic cache to save on db hits
	    var cache = new jQuery.TokenList.Cache();
	
	    // Keep track of the timeout, old vals
	    var timeout;
	    var input_val;
	    
	    jQuery(input).bind('pre_submit', function() {
	    	//update_hidden_input(saved_tokens, input);
	    });
	    jQuery(input).closest('form').submit(function() {
	    	jQuery(input).trigger('pre_submit');
	    });
	
		var dropDownCache;
		var token_input_create_new;
		
	    // Create a new text input an attach keyup events
	    var input_box = jQuery("<input>").attr('type','text').attr('autocomplete','off')
	        .attr("id", settings.idPrefix + input.id)
	        .focus(function () {
	        	console.info('FOCUS');
	        	//settings.local_data_default = [{"id":"5","name":"#test"},{"id":"14","name":"#Tech"},{"id":"18","name":"#text"},{"id":"21","name":"#travel"}];
	        	$('div.token-input-dropdown-google').hide();

	        	if (settings.tokenLimit === null || settings.tokenLimit !== token_count) {
		            if ((!this.value == '' || this.value.length == 0) && settings.local_data_default) {
						window.setTimeout(function() {
							populate_dropdown('', settings.local_data_default);
							show_dropdown();
						}, 200);
		            } else if (!show_popular_list()) {
		            	show_dropdown_hint();
		            }	
	            }

				input_token.show();
				if (settings.placeholder_token && settings.placeholder_token.is(':visible')) { 
					settings.placeholder_token.hide();        
					delete_token(settings.placeholder_token);
					settings.placeholder_token = null;
				}
	        	
				if (show_popular_list() && token_list.children("li."+settings.classes['token']+":first").length) {
					dropDownCache = saved_tokens[0];
					delete_token(token_list.children("li."+settings.classes['token']+":first"));
				}
				
				if (!dropdown.is(':visible') && (settings.showDropdownOnFocus || input_box.val())) {
		            setTimeout(function(){ 
		            	run_search(input_box.val());
		            }, settings.searchDelay);
		        }
	        })
	        .blur(function (e) {
	        	if (input_box.val()) return;
				
				if (dropDownCache) {
					window.setTimeout(function() {
						if (! dropdown.hasClass('mouse-down') && dropDownCache) {
							add_token(dropDownCache);
						}
					},100);
				}
				//if (settings.placeholder_token) return;
				if (settings.tokenLimit !== null) return;
		       	if (settings.tokenLimit == token_count) return;
				
				addPlaceholder(); 
		        
				input_box.closest('ul').parent().find('input').trigger('inputChange'); 
	        })
	        .bind("keyup keydown blur update", resize_input)
	        .keyup(function (event) {
	            var previous_token;
	            var next_token;
	
            	if (settings.addBoxValidate && !settings.addBoxValidate.call(this, event)) {
            		event.preventDefault();
    	    		return false;
    	    	}
            	
	            // populate create text box
	        	token_input_create_new = $('input.add-item-input');
	            
	            if ( token_input_create_new.is(":visible") ) {
	            	token_input_create_new.val($(this).val());
	            }

	            switch(event.keyCode) {
	                case KEY.LEFT:
	                case KEY.RIGHT:
	                case KEY.UP:
	                case KEY.DOWN:
	                    if(!jQuery(this).val()) {

	                        previous_token = input_token.prev();
	                        next_token = input_token.next();
	
	                        if((previous_token.length && previous_token.get(0) === selected_token) || (next_token.length && next_token.get(0) === selected_token)) {
	                            // Check if there is a previous/next token and it is selected
	                            if(event.keyCode === KEY.LEFT || event.keyCode === KEY.UP) {
	                                deselect_token(jQuery(selected_token), POSITION.BEFORE);
	                            } else {
	                                deselect_token(jQuery(selected_token), POSITION.AFTER);
	                            }
	                        } else if((event.keyCode === KEY.LEFT || event.keyCode === KEY.UP) && previous_token.length) {
	                            // We are moving left, select the previous token if it exists
	                            select_token(jQuery(previous_token.get(0)));
	                        } else if((event.keyCode === KEY.RIGHT || event.keyCode === KEY.DOWN) && next_token.length) {
	                            // We are moving right, select the next token if it exists
	                            select_token(jQuery(next_token.get(0)));
	                        }
	                    } else {

	                        var dropdown_item = null;
	                        if(event.keyCode === KEY.DOWN || event.keyCode === KEY.RIGHT) {
	                            dropdown_item = jQuery(selected_dropdown_item).nextAll('li:first');
	                        } else {
	                            dropdown_item = jQuery(selected_dropdown_item).prevAll('li:first');
	                        }
	
	                        if(dropdown_item.length) {
	                            select_dropdown_item(dropdown_item);
		                        return false;
	                        }

	                    }

	                    break;
	
	                case KEY.BACKSPACE:
	                    previous_token = input_token.prev();
	
	                    if(!jQuery(this).val().length) {
	                        if(selected_token) {
	                            delete_token(jQuery(selected_token));
	                            hidden_input.change();
	                        } else if(previous_token.length) {
	                            select_token(jQuery(previous_token.get(0)));
	                        }
	
	                        return false;
	                    } else if(jQuery(this).val().length === 1) {
	                        hide_dropdown();
	                    } else {
	                        // set a timeout just long enough to let this function finish.
	                        setTimeout(function(){do_search();}, 5);
	                    }
	                    break;
	
	                case KEY.TAB:
	                case KEY.ENTER:
	                case KEY.NUMPAD_ENTER:
	                case KEY.COMMA:
	                  if(!settings.disableEnter) {
	                	  if (selected_dropdown_item) {
	                    	  var $target = jQuery(selected_dropdown_item).data("tokeninput");
	                    	  console.info('selected item', selected_dropdown_item);
	                    	  if ($target) { //it creates js error in search bar
	                        	  add_token($target);
	                        	  hidden_input.change();
	                    	  }
	                    	  return false;
	                	  } else {
	                		  hidden_input.val(input_box.val());
	                	  }
	                  }
	                  break;
	
	                case KEY.ESCAPE:
	                  hide_dropdown();
	                  return true;
	
	                default:
	                    if(String.fromCharCode(event.which)) {
							// set a timeout just long enough to let this function finish.
	                        setTimeout(function(){
	                        	if (!settings.create_only) {
	                        		do_search();
	                        	}
	                        }, 5);
	                    }
	                    break;
	            }
	        });

		
	    if (settings.validate) input_box.attr('data-validate', settings.validate);
	    if (settings.validate_errors) for (var error in settings.validate_errors) {
	    	input_box.attr('data-error-'+error, settings.validate_errors[error]);
	    }

	    try { //http://www.weavetexfashion.com/baby-frocks.html
	    	input_box.css({outline: "none"});
	    } catch (e) {
	    	
	    }

	    if (jQuery(input).val() && !settings.prePopulate) {
		    input_box.val(jQuery(input).val());
	    }
	
	    // Keep a reference to the original input box
	    var input_offset = jQuery(input).offset();
	    var hidden_input = jQuery(input)
	                           .hide()
	                           .val("")
	                           .focus(function () {
	                               input_box.focus();
	                           })
	                           .blur(function () {
	                               input_box.blur();
	                           });
	
	    // Keep a reference to the selected token and dropdown item
	    var selected_token = null;
	    var selected_token_index = 0;
	    var selected_dropdown_item = null;
	
	    // The list to store the token items in
	    var token_list = jQuery("<ul />")
	        .addClass(settings.classes.tokenList)
	        .click(function (event) {
	        	// new jquery doesn't trigger hidden elements
	        	//$(input_box).show().focus().hide(); //RR - breaks the search
	        	input_box.triggerHandler('focus');
	            var li = jQuery(event.target).closest("li");
	            if(li && li.get(0) && jQuery.data(li.get(0), "tokeninput")) {
	                toggle_select_token(li);
	            } else {
	                // Deselect selected token
	                if(selected_token) {
	                    deselect_token(jQuery(selected_token), POSITION.END);
	                }

	                var li_data = settings.prePopulate || hidden_input.data("pre");
	                
	                // fix bug with input
	                if (li_data && li_data.length && typeof(li_data[0].id) == 'undefined')	{
	                	input_box.css({'visibility':'hidden'});
	                	setTimeout(function(){
	                		$('input.add-item-input:visible').focus();
	                	},500);
	                } else	{
	                	// Focus input box
	                	input_box.focus();
	                }

	            }
	        })
	        .mouseover(function (event) {
	            var li = jQuery(event.target).closest("li");
	            if(li && selected_token !== this) {
	                li.addClass(settings.classes.highlightedToken);
	            }
	        })
	        .mouseout(function (event) {
	            var li = jQuery(event.target).closest("li");
	            if(li && selected_token !== this) {
	                li.removeClass(settings.classes.highlightedToken);
	            }
	        })
	        .insertBefore(hidden_input);
	    try { //http://www.weavetexfashion.com/baby-frocks.html
		    token_list.css({'max-height': Math.max(24, jQuery(document).height() - input_offset.top) });
	    } catch (e) {}
	    

	    //if (settings.theme === 'fd_dropdown') {
	    //	token_list.css('background','#D0E0F0 url(/images/bm-modearrow_d.png) no-repeat 95% 50%');
	    //}
	    /* http://dev.fantoon.com:8100/browse/FD-3209
	    var add_new_btn = $('<button name="create" class="token-input-create-btn">Create</button>').hide()
	    						.click(function() {
	    							if ( ! $(this).is(':visible')) return; //FF fix
	    							var val = input_box.val();
	    							input_box.val('');
	    							add_token({'id':0, 'name': val, 'class': 'allow-insert-item'});
	    							return false;
	    						})
	    */
	    
	    // The token holding the input box
	    var input_token = jQuery("<li />")
	        .addClass(settings.classes.inputToken)
	        .appendTo(token_list)
	        .append(input_box)
	        //.append(add_new_btn)
	   
	    // The list to store the dropdown items in
	    var doc_body = document.body.tagName != 'body' ? jQuery('html') : jQuery('body');
	    var dropdown = jQuery("<div>")
	        .addClass(settings.classes.dropdown)
	        .addClass('fd-scroll')
	        .appendTo(doc_body)
	        .hide()
	        .mousedown(function() {
	        	var $this = $(this);
	        	$this.addClass('mouse-down');
	        	window.setTimeout(function() {
	        		$this.removeClass('mouse-down');
	        	},1000);
	        })
	    var parents = token_list.parents();
	    // console.warn('parents',parents);
	    for (var i=0;i<parents.length; i++ ) {
	    	if (jQuery(parents[i]).css('position') == 'fixed') {
	    		dropdown.addClass('fixed');
	    		break;
	    	}
	    }
	    //token_list.parent().css('position', 'relative');
	    //token_list.after(dropdown);
	    // Magic element to help us resize the text input
	    var input_resizer = jQuery("<tester/>")
	        .insertAfter(input_box);
	    try {
	    	input_resizer.css({
	            position: "absolute",
	            top: -9999,
	            left: -9999,
	            width: "auto",
	            fontSize: input_box.css("fontSize"),
	            fontFamily: input_box.css("fontFamily"),
	            fontWeight: input_box.css("fontWeight"),
	            letterSpacing: input_box.css("letterSpacing"),
	            whiteSpace: "nowrap"
	        });	    	
	    } catch (e) {}
		input_box.width(input_resizer.width() + 30);
	    // Pre-populate list if items exist
	    hidden_input.val("");
	    var li_data = settings.prePopulate || hidden_input.data("pre");
	    if(settings.processPrePopulate && jQuery.isFunction(settings.onResult)) {
	        li_data = settings.onResult.call(hidden_input, li_data);
	    }

	    if(typeof li_data == 'string' && li_data.indexOf('[{') == 0){
	    	li_data = eval(li_data);
	    	hidden_input.data("pre",'');
	    }

	    if (li_data && li_data.length) {
	    	jQuery.each(li_data, function (index, value) {
	    		if (value && value.name) {
		            insert_token(value);
	    		} else {
	    			settings.placeholder_token = insert_token({id:0, name: settings.placeholder});
	    		}
	            checkTokenLimit();
	        });
	    } else {
	    	//insert_token({id:0, name: settings.placeholder});
	    }
	    
	    jQuery(document).on('click', function(e) {
	    	if (   ! jQuery(e.target).closest("div[class^='token-input-dropdown']").length 
	    	    && ! jQuery(e.target).closest('li[class^="token-input-"]').length
	    	 ) {
	    		console.info('hide on doc.click');
	    		hide_dropdown();
				if (dropDownCache) {
					//add_token(dropDownCache);
				}
	    	}
	    });	    
	    
	    jQuery(document).on('keydown','input.add-item-input', function(e) {
	    	if (settings.addBoxValidate && !settings.addBoxValidate.call(this, e)) {
	    		e.preventDefault();
	    		return false;
	    	}
	    });
		
	    // Initialization is done
	    if(jQuery.isFunction(settings.onReady)) {
	        settings.onReady.call();
	    }
	
	    //
	    // Public functions
	    //
	    this.destroy = function() {
	    	token_list.remove();
	    	jQuery(input).nextAll('input.tokenInput-hidden').remove();
	    	hidden_input.removeClass('initialized');
	    }
	    
	    this.option = function(opt, value) {
	    	settings[opt] = value;
	    }
	
	    this.clear = function() {
	        token_list.children("li:not(.placeholder-token, .linked-text-token)").each(function() {
	            if (jQuery(this).children("input").length === 0) {
	                delete_token(jQuery(this));
	            }
	        });
	        addPlaceholder();
	    }
		
	    this.add = function(item) {
			if (settings.placeholder_token) { 
				settings.placeholder_token.hide();        
				delete_token(settings.placeholder_token);
				settings.placeholder_token = null;
			}

	        add_token(item);
	    }
		
	    this.remove = function(item) {
	        token_list.children("li").each(function() {
	            if (jQuery(this).children("input").length === 0) {
	                var currToken = jQuery(this).data("tokeninput");
	                var match = true;
	                for (var prop in item) {
	                    if (item[prop] !== currToken[prop]) {
	                        match = false;
	                        break;
	                    }
	                }
	                if (match) {
	                    delete_token(jQuery(this));
	                }
	            }
	        });
	    }
	    
	    this.getTokens = function() {
	   		return saved_tokens;
	   	}
	
	    //
	    // Private functions
	    //
	    function addPlaceholder() {
			if (settings.placeholder && !input_box.val() && token_list.find('li.'+settings.classes.token).length == 0) {
				settings.placeholder_token = insert_token({id:-2, name: settings.placeholder, 'class':'placeholder-token'});
				if (!input_box.val())	{
					input_box.attr("placeholder",input_box.attr("placeholder"));
				}
				// bug - when the hashtag is delete by input input box disapear
				// input_token.hide();
			} else if (settings.linkedtext && token_list.find('li.'+settings.classes.token).length > 0 && token_list.find('li.linked-text-token').length > 0 ) {
				settings.placeholder_token = insert_token({id:-2, name: settings.linkedtext, 'class':'linked-text-token'});
				input_token.hide();
			}
	    }
	
	    function checkTokenLimit() {
	    	if(settings.tokenLimit !== null && token_count >= settings.tokenLimit) {
	            input_box.hide();
	            //add_new_btn.hide();
	            console.info('hide on checkTokenLimit');
	            hide_dropdown();
	            return;
	        }
	    }
	
	    function resize_input() {
	        if(input_val === (input_val = input_box.val())) {return;}
	        // Enter new content into resizer and resize input accordingly
	        var escaped = input_val.replace(/&/g, '&amp;').replace(/\s/g,' ').replace(/</g, '&lt;').replace(/>/g, '&gt;'); //'
	        input_resizer.html(escaped);
	        //For some reason <tester>.width() does not give correct width
	        input_box.width(input_resizer.width() + 30);
	    }
	
	    function is_printable_character(keycode) {
	        return ((keycode >= 48 && keycode <= 90) ||     // 0-1a-z
	                (keycode >= 96 && keycode <= 111) ||    // numpad 0-9 + - / * .
	                (keycode >= 186 && keycode <= 192) ||   // ; = , - . / ^
	                (keycode >= 219 && keycode <= 222));    // ( \ ) '
	    }
	
	    // Inner function to a token to the list
	    function insert_token(item) {
	        var this_token = settings.tokenFormatter(item);
	        this_token = jQuery(this_token)
	          .addClass(settings.classes.token)
	          .insertBefore(input_token);
	
	        // The 'delete token' button
	        var del_btn = jQuery("<span>")
	            .addClass(settings.classes.tokenDelete)
	            .insertBefore(this_token.find('*').first())
	            .click(function () {
	                delete_token(jQuery(this).parent());
	                hidden_input.change();
	                input_box.focus();
	                return false;
	            });
	        try {
	        	del_btn.html(settings.deleteText);
	        } catch (e) {
	        	del_btn.html('x');	        	
	        }
	
	        // Store data on the token
	        var token_data = {"id": item.id, "group": item.group};
	        token_data[settings.propertyToSearch] = item[settings.propertyToSearch];
	        jQuery.data(this_token.get(0), "tokeninput", item);
	        
	        // Save this token for duplicate checking
	        if (item.id >= 0) saved_tokens = saved_tokens.slice(0,selected_token_index).concat([token_data]).concat(saved_tokens.slice(selected_token_index)); // UPDATED
	        selected_token_index++;
	
	        // Update the hidden input
	        update_hidden_input(saved_tokens, hidden_input);
	        if (item.id >= 0) token_count += 1; //UPDATED
	
	        // Check the token limit
	        if(settings.tokenLimit !== null && token_count >= settings.tokenLimit) {
	            input_box.hide();
	            //add_new_btn.hide();
	            console.info('hide on insert token');
	            hide_dropdown();
	        }
	
	        return this_token;
	    }
	
	    // Add a token to the token list based on user input
	    function add_token (item) {
	    	console.info('ADD token', item);
			dropDownCache = null;
	        var callback = settings.onAdd;
	        if (input_box.attr('data-validate') && input_box.closest('.form_row').find('.error').is(':visible')) {
	        	console.info('invalid token');
	        	return false;
	        }
	
	        // See if the token already exists and select it if we don't want duplicates
	        if(token_count > 0 && settings.preventDuplicates) {
	            var found_existing_token = null;
	            token_list.children().each(function () {
	                var existing_token = jQuery(this);
	                var existing_data = jQuery.data(existing_token.get(0), "tokeninput");
	                if(existing_data && ((item.id && existing_data.id === item.id) && (item.name && existing_data.name == item.name))) {
	                    found_existing_token = existing_token;
	                    return false;
	                }
	            });
	
	            
	            if(found_existing_token) {
	                //select_token(found_existing_token);
	                //input_token.insertAfter(found_existing_token);
	                //input_box.focus();
	                return;
	            }
	        }
	
	        // Insert the new tokens
	        if(settings.tokenLimit == null || settings.tokenLimit == 1 || token_count < settings.tokenLimit) {
	            if (settings.tokenLimit == 1 && token_count) {
	            	delete_token(token_list.children("li:first"));
	            }
	            insert_token(item);
	            checkTokenLimit();
	        }
	        if (token_count > 0 && input_box.attr('data-validate')) {
	    		input_box.attr('data-validate', input_box.attr('data-validate').replace('|required','').replace('required|','').replace('required',''));
	    	} 
	        	
	        // Clear input box
	        input_box.val("");
	        //add_new_btn.hide();
	        // Don't show the help dropdown, they've got the idea
	        console.info('hide on add token');
	        hide_dropdown();
	
	        // Execute the onAdd callback if defined
	        if(jQuery.isFunction(callback)) {
	        	$('.error_cstm').hide();
	            callback.call(hidden_input,item);
	        }
	
			input_box.closest('ul').parent().find('input').trigger('inputChange'); 
	
	    }
	
	    // Select a token in the token list
	    function select_token (token) {
	        token.addClass(settings.classes.selectedToken);
	        selected_token = token.get(0);
	
	        // Hide input box
	        input_box.val("");
	
	        // Hide dropdown if it is visible (eg if we clicked to select token)
	        console.info('hide on select token');
	        hide_dropdown();
	    }
	
	    // Deselect a token in the token list
	    function deselect_token (token, position) {
	        token.removeClass(settings.classes.selectedToken);
	        selected_token = null;
	
	        if(position === POSITION.BEFORE) {
	            input_token.insertBefore(token);
	            selected_token_index--;
	        } else if(position === POSITION.AFTER) {
	            input_token.insertAfter(token);
	            selected_token_index++;
	        } else {
	            input_token.appendTo(token_list);
	            selected_token_index = token_count;
	        }
	
	        // Show the input box and give it focus again
	        input_box.focus();
	    }
	
	    // Toggle selection of a token in the token list
	    function toggle_select_token(token) {

	        var previous_selected_token = selected_token;

/*
	        if (previous_selected_token)	{
	        	// close
	        	$(previous_selected_token).hide();
	        	$('input', $(previous_selected_token).next()).show();
	        }	else {
	        	$(token.get(0)).show();
	        	$('input', $(token.get(0)).next()).hide();	        	
	        }
*/

	        if(selected_token) {
	            deselect_token(jQuery(selected_token), POSITION.END);
	        }
	
	        if(previous_selected_token === token.get(0)) {
	            deselect_token(token, POSITION.END);
	        } else {
	            select_token(token);
	        }
	    }
	
	    // Delete a token from the token list
	    function delete_token (token) {
	        // Remove the id from the saved list
	        var token_data = jQuery.data(token.get(0), "tokeninput");
	        var callback = settings.onDelete;
	
	        var index = token.prevAll().length;
	        if(index > selected_token_index) index--;
	
	        // Delete the token
	        token.remove();
	        selected_token = null;
	        // Show the input box and give it focus again
	        //input_box.focus();
	
	        // Remove this token from the saved list
	        if (token_data && token_data.id >= 0) saved_tokens = saved_tokens.slice(0,index).concat(saved_tokens.slice(index+1)); //UPDATED
	        if(index < selected_token_index) selected_token_index--;
	
	        // Update the hidden input
	        update_hidden_input(saved_tokens, hidden_input);
	
	        if (token_data && token_data.id >= 0) token_count -= 1;
	        
	    	if (token_count == 0 && settings.validate) {
	    		input_box.attr('data-validate', settings.validate);
	    	}
	
	        if(settings.tokenLimit !== null) {
	            input_box
	                .show()
	                .val("")
	                //.blur(); - disabled because of dropDownCache
	        }
	
	        // Execute the onDelete callback if defined
	        if(jQuery.isFunction(callback)) {
	            callback.call(hidden_input,token_data);
	        }
	    }
	
	    // Update the hidden input box value
	    function update_hidden_input(saved_tokens, hidden_input) {
	    	var group,id;
	    	jQuery(input).nextAll('input.tokenInput-hidden').remove();
	    	for (i=0;i<saved_tokens.length;i++) {
	    		if (saved_tokens[i].name == settings.placeholder) continue;

	    		group = (saved_tokens[i].group ? '['+saved_tokens[i].group+']' : '');
	    		var new_input = jQuery('<input type="hidden" name="'+input.name.replace('[]','')+group+'['+saved_tokens[i].id+']'+(saved_tokens[i].id?'':'[]')+'" class="tokenInput-hidden"/>');

	    			// error on validate
	                 var li_data = settings.prePopulate || hidden_input.data("pre");
	                 // fix bug with input
	                 if (li_data && li_data.length && li_data[0].id == undefined)	{
	    			   var sel_item = jQuery('<option>').addClass("popular").val(0).attr("selected",true).html(saved_tokens[i].name);
	    			   jQuery(input).append(sel_item);
	    			 }
	    		
	    		jQuery(input).after(new_input);
    			new_input.val(saved_tokens[i].name).trigger('change');
	    	}

	    	/*
	        var token_values = jQuery.map(saved_tokens, function (el) {
	            return (el.group ? el.group+'_' : '') + (el[settings.tokenValue] ? el[settings.tokenValue] : el[settings.propertyToSearch]);
	        });
	        hidden_input.val(token_values.join(settings.tokenDelimiter));
			*/

	    }
	
	    // Hide and clear the results dropdown
	    function hide_dropdown () {
	    	if (!dropdown.is(':visible')) return;
	    	//console.info('HIDE dropdown');
	    	//return ;
	        dropdown.hide().empty();
	        selected_dropdown_item = null;
	    }
	
	    function show_dropdown() {

	    	//console.info('show dropdown');
	       	var ptop = jQuery(token_list).offset().top + jQuery(token_list).outerHeight();
	       	if (dropdown.hasClass('fixed')) ptop -= jQuery(window).scrollTop();
	   		var pheight = jQuery(window).height() - ptop + jQuery(window).scrollTop();
	   		try { //http://www.weavetexfashion.com/baby-frocks.html
		    	dropdown.css({
		                top: ptop,
		                left: jQuery(token_list).offset().left,
		                'max-height': pheight,
		                zindex: 999,
		                display: 'block'
		            });
		    	dropdown.find('ul').css('max-height', pheight);
	   		} catch (e) {}
	    	window.setTimeout(function() { position_dropdown() }, 1);
	    	window.setTimeout(function() { position_dropdown() }, 30);
	    	window.setTimeout(function() { position_dropdown() }, 100);

	    }
	    
	    function position_dropdown() {

	    	//the top position of the input_box
	   		var ptop = jQuery(token_list).offset().top + jQuery(token_list).outerHeight();
	       	if (dropdown.hasClass('fixed')) ptop -= jQuery(window).scrollTop();
	       	//the distance between input_box and window.bottom
	   		var pheight = jQuery(window).height() - ptop;
	   		
	   		if (!dropdown.hasClass('fixed')) {
	   			pheight += jQuery(window).scrollTop();
	   		}
	   		
	   		/*console.info('{position dropdown}', {
	   			'widow.height': jQuery(window).height(),
	   			'window.scroll': jQuery(window).scrollTop(),
	   			'top': ptop,
	   			'pheight': pheight
	   		})*/
	       	if (pheight <= 150) {
	       		try { //http://www.weavetexfashion.com/baby-frocks.html
		       		dropdown.css('top', ptop - jQuery(token_list).outerHeight() - dropdown.height());
		       		dropdown.css('max-height', ptop - jQuery(token_list).outerHeight() );
		   		} catch (e) {}
	       	} else {
	       		try { //http://www.weavetexfashion.com/baby-frocks.html
		       		dropdown.css('top', ptop);
		       		dropdown.css('max-height', pheight);
		   		} catch (e) {}
	       	}
	    }
	
	    function show_dropdown_searching () {
	        if(settings.searchingText) {
	            dropdown.html("<p>"+settings.searchingText+"</p>");
	            show_dropdown();
	        }
	    }
	
	    function show_dropdown_hint () {
	    	if(settings.hintText) {
	       		setTimeout(function() { dropdown.html("<p>"+settings.hintText+"</p>"); }, 50);
	       	    setTimeout(function() { show_dropdown(); },200);
	        }
	    }
	
	    // Highlight the query part of the search term
	    function highlight_term(value, term) {
	        return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + term + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
	    }
	    
	    function find_value_and_highlight_term(template, value, term) {
	    	value = value.replace("{","\\{");
	    	value = value.replace("}","\\{");
	        return template.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + value + ")(?![^<>]*>)(?![^&;]+;)", "g"), highlight_term(value, term));
	    }
	
	    // Populate the results dropdown with some results
	    function populate_dropdown (query, results) {

	        if(results && results.length && ( results[0].id >= 0 || results.length > 1) ) {
	            dropdown.empty();
	            var dropdown_ul = jQuery("<ul>")
	                .appendTo(dropdown)
	                .mouseover(function (event) {
	                    select_dropdown_item(jQuery(event.target).closest("li"));
	                })
	                .mousedown(function (event) {
	                	var $target = jQuery(event.target);
	                	if ($target.hasClass('allow-insert-item')) {
		                	return false;
	                	}
	                	if ($target.hasClass('add-item-btn') || $target.hasClass('add-item-input')) {
	                		var input_val = $target.closest('li').find('input.add-item-input').val();
	                		if (jQuery.trim(input_val) !== '') {
	                			for (var i in results) { //check if the collection already exists
	                				if (results[i].name == input_val) {

	        	                        add_token(results[i]);
	        	                        hidden_input.change();
	        	                        return false;
	                				}
	                			}
	                        	add_token({id:0, name: input_val });
	                        	jQuery('#tab_label').hide(); //?!?
	                        	hidden_input.change();
	                            return false;
	                        }
	                		return true;
	                	} else if ($target.closest("li").data("tokeninput").id > 0 || (settings.allowNullSelect && $target.closest("li").data("tokeninput").id == 0) ) {
	                        add_token($target.closest("li").data("tokeninput"));
	                        hidden_input.change();
	                        return false;
	                	} else if (settings.allowInsert && $target.closest("li").data("tokeninput")['class'] == 'allow-insert-item') {
	                        add_token($target.closest("li").data("tokeninput"));
	                        hidden_input.change();
	                        return false;
	                	} else if ($target.closest("li").data("tokeninput")['url']) {
	                		window.location.href = $target.closest("li").data("tokeninput")['url'];
	                	}
	                })
	                .hide();
	            
	            jQuery.each(results, function(index, value) {

	                var this_li = settings.resultsFormatter(value);

	                this_li = find_value_and_highlight_term(this_li ,value[settings.propertyToSearch], query);            
	                
	                this_li = jQuery(this_li).appendTo(dropdown_ul);
	                
	                if(index % 2) {
	                    this_li.addClass(settings.classes.dropdownItem);
	                } else {
	                    this_li.addClass(settings.classes.dropdownItem2);
	                }
	
	                jQuery.data(this_li.get(0), "tokeninput", value);
	            });
	
	            /*dropdown_ul
		            .find('input.add-item-input').bind('blur', function(e) {
		            	input_box.trigger(e);
		            });
	            */
	            select_dropdown_item(dropdown_ul.find('li:first'));
	
	
	            show_dropdown();
	
	            if(settings.animateDropdown) {
	            	dropdown_ul.slideDown("fast");
	            } else {
	                dropdown_ul.show();
	            }
	            //if (dropdown_ul.find('input.add-item-input').length) {
	            //    dropdown_ul.find('input.add-item-input').focus()
	            //}
	        } else {

	            if(settings.noResultsText) {
	                dropdown.html("<p>"+settings.noResultsText+"</p>");
	                show_dropdown();
	            }
	        }
	    }
	
	    // Highlight an item in the results dropdown
	    function select_dropdown_item (item) {
	        if(item) {
	            if(selected_dropdown_item) {
	                deselect_dropdown_item(jQuery(selected_dropdown_item));
	            }
	
	            item.addClass(settings.classes.selectedDropdownItem);
	            selected_dropdown_item = item.get(0);
	        }
	    }
	
	    // Remove highlighting from an item in the results dropdown
	    function deselect_dropdown_item (item) {
	        item.removeClass(settings.classes.selectedDropdownItem);
	        selected_dropdown_item = null;
	    }
	
	    // Do a search and show the "searching" dropdown if the input is longer
	    // than settings.minChars
	    function do_search() {

	        var query = input_box.val().toLowerCase();
	
	        if(query && query.length) {
	            if(selected_token) {
	                //deselect_token(jQuery(selected_token), POSITION.AFTER);
	            }
	
	            if(query.length >= settings.minChars) {
	                if (!dropdown.is(':visible')) show_dropdown_searching();
	                clearTimeout(timeout);
	
	                timeout = setTimeout(function(){
	                    run_search(query);
	                }, settings.searchDelay);
	            } else {
	                hide_dropdown();
	            }
	        }
	    }
	    
	    //Sorts list in alphabetical order
	    function alpha_sort(a,b) {
		    var aName = a.name.toLowerCase();
		    var bName = b.name.toLowerCase(); 
		    return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
	    }
	    
	    //Corrects the order so that rows that start with the query letter are first
	    function correct_order(list,query) {
		    var res = [];
		    for (var i=0; i<list.length; i++) {   //get the ones starting with query and append to res
			    if (list[i].name.toLowerCase().indexOf(query.toLowerCase()) == 0) {
				    res.push(list[i]);
				    delete list[i]; //here we are using delete not splice to make sure we wont break the for loop.
			    }
		    }
		    for (var i=0; i<list.length; i++) {   //append the others
		    	if (list[i]) res.push(list[i]);  //delete set the item to undefined and doesnt reindex the array so we are checking with 'if'
		    }
		    return res;
	    }
	
	    // Do the actual search
	    function run_search(query) {
	    	// alert('run_search');
	        var cache_key = query + computeURL();
	        var cached_results = cache.get(cache_key);
	        if(cached_results) {
	            populate_dropdown(query, cached_results);
	        } else {
	        	//Display default data if the list is empty
		            // Are we doing an ajax search or local data search?
		            if(settings.url) {
		                var url = computeURL();
		                // Extract exisiting get params
		                var ajax_params = {};
		                ajax_params.data = {};
		                if(url.indexOf("?") > -1) {
		                    var parts = url.split("?");
		                    ajax_params.url = parts[0];
		
		                    var param_array = parts[1].split("&");
		                    jQuery.each(param_array, function (index, value) {
		                        var kv = value.split("=");
		                        ajax_params.data[kv[0]] = kv[1];
		                    });
		                } else {
		                    ajax_params.url = url;
		                }

		                // Prepare the request
		                ajax_params.data[settings.queryParam] = query;
		                ajax_params.type = settings.method;
		                ajax_params.dataType = settings.contentType;

		                if(settings.crossDomain) {
		                    ajax_params.dataType = "jsonp";
		                }
		
		                // Attach the success callback
		                ajax_params.success = function(results) {
			              //sort list in alphabetical order
			              if (settings.alphaSort) {
			            	  results.sort(alpha_sort);
			            	  results = correct_order(results,query);
			              }
			              
		                  if(jQuery.isFunction(settings.onResult)) {
		                      results = settings.onResult.call(hidden_input, results);
		                  }
		                  cache.add(cache_key, settings.jsonContainer ? results[settings.jsonContainer] : results);
		
		                  // only populate the dropdown if the results are associated with the active search query
		                  if(input_box.val().toLowerCase() === query) {
		                      populate_dropdown(query, settings.jsonContainer ? results[settings.jsonContainer] : results);
		                  }
		                  
		                };
		
		                // Make the request
		                jQuery.ajax(ajax_params);

		            } else if(settings.local_data) {
		                // Do the search through local data
		            	
		                var results = jQuery.grep(settings.local_data, function (row) {
		                	if (show_popular_list()) {
		                		return row['class'] && row['class'].indexOf('popular') > -1
		                	}
		                    return row[settings.propertyToSearch] && row[settings.propertyToSearch].toLowerCase().indexOf(query.toLowerCase()) > -1;
		                });

		                //sort list in alphabetical order
		                if (results) {
		                	if (settings.alphaSort) {
		                		results.sort(alpha_sort);
		                		results = correct_order(results,query);
		                	}
		                }
		
		                if(jQuery.isFunction(settings.onResult)) {
		                    results = settings.onResult.call(hidden_input, results);
		                }
		                cache.add(cache_key, results);
		                populate_dropdown(query, results);
		            }
	        }
	    }
	
	    // compute the dynamic URL
	    function computeURL() {
	        var url = settings.url;
	        if(typeof settings.url == 'function') {
	            url = settings.url.call();
	        }
	        return url;
	    }
	    
	    /**
		 *    Radil Radenkov Extensions
		 */
		//selectiveColor - can be defined in the css - to discuss
		//singleTokenOnly - can set tokenLimit = 1

		//redirectOnClick - can be ommited will be auto set if result contains 'url' param
		if (settings.onAdd) settings._onAdd = settings.onAdd;
		settings.onAdd = function(data) {
			if (settings._onAdd) settings._onAdd.call(this, data);
			if (data.url) {
				token_list.find('li.'+settings.classes['token']).hide();
				window.location.href = data.url;
			}
		}
		
		//Type format
		settings.resultsFormatter = function(item) {

			var el = jQuery(document.createElement('item'));

			if (item.id == -1) {
				var container = jQuery(document.createElement('h3'))
			} else {
				var container = jQuery(document.createElement('li'))
			}

			if (item['class']) {
				container.addClass(item['class']);
			}
			
			container.html('<span>'+item[this.propertyToSearch]+'</span>')
			
			if (item.img) {
				container.html('<img src="'+item.img+'" alt=""/>'+container.html());
				container.addClass('with-image');
			} 
			
			el.append(container);
			
			return el.html();
		}

		settings.tokenFormatter = function(item) { 
			var el = jQuery(document.createElement('item'));
			var container = jQuery(document.createElement('li'));
			
			if (item['class']) {
				container.addClass(item['class']);
			}
			
			item[this.propertyToSearch] = item[this.propertyToSearch] ? item[this.propertyToSearch].replace('Add: ','') : '';
			
			container.html("<p>" + item[this.propertyToSearch] + "</p>");
			
			if (item.img) {
				container.html('<img src="'+item.img+'" alt=""/>'+container.html());
				container.addClass('with-image');
			} 
			
			el.append(container);
			return el.html(); 
		}
		
		//allowInsert
		//bottomText: 
		settings.onResult  = function(results) {
			if (settings.allowInsert === 'true' || settings.allowInsert === true) {
				var item = {}
				item['id'] = 0;
				if (typeof settings.local_data_default == 'object')	{
					item[settings.propertyToSearch] =  '<input type="text" class="add-item-input" value="' +  input_box.val() + '"/><input type="button" class="add-item-btn" value="Create"/>';
				} else	{
					item[settings.propertyToSearch] =  '<input type="text" class="add-item-input"/><input type="button" class="add-item-btn" value="Create"/>';
				}
				item['class'] = 'allow-insert-item';
				results.unshift(item); //http://dev.fantoon.com:8100/browse/FD-3291
				
				/*
				var has_it = false;
				if (jQuery(input_box).val()) for (var i in results) if (results[i][settings.propertyToSearch] == jQuery(input_box).val()) has_it = true;
				//show add input if there arent any hidden folders @see run_sarch local_data
				var popular_dropdown = !input_box.val() && settings.showDropdownOnFocus && settings.local_data.length >= results.length;
				if (!has_it && !popular_dropdown) {
						if (jQuery(input_box).val()) {
							item[settings.propertyToSearch] =  'Add: '+jQuery(input_box).val();
							//add_new_btn.show();
							selected_dropdown_item = null;
						} else {
							var item = {}
							item['id'] = 0;
							item[settings.propertyToSearch] =  '<input type="text" class="add-item-input"/><input type="button" class="add-item-btn" value="Add"/>';
							item['class'] = 'allow-insert-item';
							results.push(item);
						}
				}
				*/			
			}
			if (settings.bottomText) {
				var item = {'id': -2}
				item[settings.propertyToSearch] = settings.bottomText;
				item['url'] = settings.bottomLink.replace('{val}',input_box.val().toLowerCase());
				results.push(item)
			}
			return results;
		}
				
    	//used for folders dropdown when there are more than 15 folders, no query is typed and there are popular items
    	//use just the popular ones and allow the user to search for the others
		function show_popular_list() {
			return false;
			var has_popular = false;
        	for(var i in settings.local_data) {
        		if(settings.local_data[i]['class'] && settings.local_data[i]['class'].indexOf('popular') > -1) {
        			has_popular = true; break;
        		}  
        	}
        	//FD-2898 (begin)
        	if (typeof settings.local_data != 'undefined' && typeof settings.showDropdownOnFocus != 'undefined') {
        		//return settings.local_data.length > 10 && !input_box.val() && settings.showDropdownOnFocus && has_popular;
        		return !input_box.val() && settings.showDropdownOnFocus && has_popular;
        	} else {
        		return false;
        	}
        	//FD-2898 (end)
		}
		
		addPlaceholder();
		
		//var width = jQuery(input).width() > 0 ? jQuery(input).width() : 400;
		token_list.width(settings.width);
		dropdown.width(settings.width);
	/** END EXTENSSIONS **/
	};
	
	// Really basic cache for the results
	jQuery.TokenList.Cache = function (options) {
	    var settings = jQuery.extend({
	        max_size: 500
	    }, options);
	
	    var data = {};
	    var size = 0;
	
	    var flush = function () {
	        data = {};
	        size = 0;
	    };
	
	    this.add = function (query, results) {
	        if(size > settings.max_size) {
	            flush();
	        }
	
	        if(!data[query]) {
	            size += 1;
	        }
	
	        data[query] = results;
	    };
	
	    this.get = function (query) {
	        return data[query];
	    };
	};
	
	jQuery.fn.initTokenInput = function() {
		
		var data_or_url = '';
		var default_data = '';
	    var opts, is_def, name, attrs;
	    var is_def2, name2, attrs2;
		var default_attrs = ['type','name','data-url','onadd','ondelete','id','class','style','title','value','data-default_data','data-create_only'];

	    jQuery('select.tokenInput:not(.initialized)').each(function() {
	    	var $self = jQuery(this);
	    	var menu_vals = $self.find('option');
	    	var sel_obj = $self.find(':selected');
			jQuery(this).addClass('initialized');
	    	/*
	    	opts = {
	    		width: jQuery(this).width(),
	    		theme: 'fd-dropdown'
	    	}
	    	*/
	    	var opts = {
	    		width: jQuery(this).width(),
	    		//disableEnter: true,
	    		tokenLimit: 1,
	    		placeholder: '',
	    		showDropdownOnFocus: true,
	    		linkedText: '',
	    		prePopulate: [{id: sel_obj.val(), name: sel_obj.text()}],
	    		theme: 'fd_dropdown',
	    		create_only: false
	       	}; 
	       	
			attrs2 = this.attributes;
	    	//jQuery(this).addClass('initialized');
	    	for(var i=0;i<attrs2.length;i++) {
	    		is_def2 = false;
	    		for (j in default_attrs) if (attrs2[i].name == default_attrs[j]) is_def2 = true;
	    		if (!is_def2) {
	    			name2 = attrs2[i].name.replace(/_(.)/gi,function(m){ if (m[1]) {return m[1].toUpperCase(); } });
	    			if (!isNaN(parseInt(attrs2[i].value)))
	    				opts[name2] = parseInt(attrs2[i].value);
	    			else if (attrs2[i].value == 'false')
	    				opts[name2] = false;
	    			else
	    				opts[name2] = attrs2[i].value;	    				
	    		}
	    	}

	    	var sel_data = [];
	    	var item = null;
			menu_vals.each(function() {
				item = {id: jQuery(this).val(), name: jQuery(this).text()};
				if ($(this).attr('class')) item['class'] = $(this).attr('class');
				sel_data.push(item);
			});

			if (jQuery(this).attr("data-create_only"))	{
				opts.create_only = true;
			}

	    	jQuery(this).tokenInput(sel_data, opts);

	    });
	
	    jQuery('input.tokenInput:not(.initialized)').each(function() {
	    	var $self = jQuery(this);
	    	opts = {
	    		width: jQuery(this).width()
	    	};
	    	var attrs = this.attributes;
	    	jQuery(this).addClass('initialized');
	    	for(var i=0;i<attrs.length;i++) {
	    		is_def = false;
	    		for (j in default_attrs) if (attrs[i].name == default_attrs[j]) is_def = true;
	    		if (!is_def) {
	    			if (attrs[i].name == 'data-validate') {
	    				opts['validate'] = attrs[i].value;
	    			} else if (attrs[i].name.indexOf('data-error-') > -1) {
	    				if (!opts['validate_errors']) opts['validate_errors'] = {}
	    				opts['validate_errors'][attrs[i].name.replace('data-error-','')] = attrs[i].value;
	    			} else {
	    				name = attrs[i].name.replace(/_(.)/gi,function(m){ if (m[1]) {return m[1].toUpperCase(); } });
		    			if (!isNaN(parseInt(attrs[i].value)))
		    				opts[name] = parseInt(attrs[i].value);
		    			else
		    				opts[name] = attrs[i].value;
	    			}
	    		}
	    	}
	    	if (opts['validate']) $(this).removeAttr('data-validate');
	    	if (opts['validate_errors']) for (error in opts['validate_errors']) $(this).removeAttr('data-error-'+error); 
	    		
	    	if (jQuery(this).val()) {
	    		if (jQuery(this).val().indexOf('{') != -1 ) {
	        		opts.prePopulate = eval( jQuery(this).val()+';' );
	    		}
	    	}
	    	
	    	if (jQuery(this).attr('data-url') && jQuery(this).attr('data-url').substr(0,1) == '[') {
	    		data_or_url = eval(jQuery(this).attr('data-url'));
	    	} else {
	    		data_or_url = jQuery(this).attr('data-url');
	    	}

	    	if (jQuery(this).attr("data-create_only"))	{
	    		alert('create');
	    		opts.create_only = true;
	    	}
	    	
	    	//For initial default list (if present)
	    	
	    	if (!jQuery(this).attr('data-default_data') || jQuery(this).attr('data-default_data').length <= 0) {
	    		default_data ='';
	    	} else if (jQuery(this).attr('data-default_data').substr(0,1) == '[') {
	    		default_data = eval(jQuery(this).attr('data-default_data'));
	    	} else if (jQuery(this).attr('data-default_data').length > 0) {
	    		default_data = jQuery(this).attr('data-default_data');
	    	}
	    	
	    	if (jQuery(this).attr('onAdd')) {
	    		opts.onAdd = function(data) { eval('"'+jQuery(this).attr('onAdd')+'"'); }
	    	}

	    	if (jQuery(this).attr('onDelete')) {
	    		opts.onDelete = function(data) { eval(jQuery(this).attr('onDelete')) }
	    	}

	    	jQuery(this).tokenInput(data_or_url, opts, default_data);
	    	
	    });
	}
	
	jQuery.fn.initTokenInput();
	jQuery(document).ready(function() {
		jQuery.fn.initTokenInput();
	});
});
