/**
 * Home page tests - user logged in
 */

QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

var drop = '#list_newsfeed .newsfeed_entry:first';

if ( qunit_module === undefined || qunit_module == 'basic' ) {

	testModule( 'Home page', function () {
		
		testExists("Autoscroll",'#popular_collections_list li.js-folder:eq(16)', function() {
			window.scroll(0, 0);	
		})
		.trigger(function()	{
				window.scroll(0,document.body.offsetHeight);
		});
		
	});
	
	testModule( 'up/down vote drop home', function () {
		var up_button = drop+' .upbox > .up_button';
		var down_button = drop+' .upbox > .undo_up_button';
		var up_count_el = drop+' .upbox .up_count';
		var up_count; //will be populated with the current up count
		
		// Make sure the up_button is visible and define the up count so the tests can continue
		test("Basic state check", function() {
			visible(up_button);
			hidden(down_button);
			up_count = parseInt($(up_count_el).text());
		});

		// --------- test upvote in home -----------
		testEvent('Upvote', up_button, 'success', function(){
			hidden(up_button);
			visible(down_button);
			visible(up_count_el);
			equal(parseInt($(up_count_el).text()), up_count+1, "Up count should be +1");			
		})
		.trigger(up_button, 'click');

		// --------- test downvote in home -----------
		testEvent('Downvote', down_button, 'success', function(){
			visible(up_button);
			hidden(down_button);
			equal(parseInt($(up_count_el).text()), up_count, "Up count should be -1");			
		})
		.trigger(down_button, 'click');
	});
}

if ( qunit_module === undefined || qunit_module == 'avatar_notification' ) {
	var notification_bar = '#systemNotification';
	var avatar_btn = notification_bar+ ' a[href="/profile/edit_picture"]';
	
	var popup = '#upload_profilepic_dlg';
	var popup_img = popup+' #preview img';
	var upload_btn = popup+' #select_file_btn_hidden';
	var save_btn = popup+' [type="submit"]';
	
	var orig_img;
	
	testModule( 'Avatar notification', function () {
		
		test("Basic page state", function() {
			visible(notification_bar);
			visible(avatar_btn);
		});
		
		testEvent("Open popup", avatar_btn, 'before_show', function() {
			visible(popup_img);
			visible(upload_btn)
		})
		.trigger(avatar_btn, 'click');
		
		testCondition("Load popup script", function() {
			return typeof edit_picture_js_loaded != 'undefined';
		}, function() {
			orig_img = $(popup_img).attr('src');
		});
		
		testCondition("Change image", function() {
			return $(popup_img).attr('src') != orig_img;
		})
		.trigger(function() {
			window.callPhantom('uploadFile', ['#select_file_btn_hidden']);
		});
		
		testVisible("Save Button show", save_btn);
		
		testEvent("Save new img", popup+' form', 'success')
		.trigger(save_btn, 'click');
		
		testInvisible("Porfile popup hidden", popup);
		 		
	});
	
}


if ( qunit_module === undefined || qunit_module == 'activity' ) {
	var activity_bar = '.new_activity_feed';
	
	testModule( 'Activity', function () {
		test("Check for missing newsfeed_id", function() {
			var has_error = false;
			$(activity_bar+" [href='#preview_popup']").each(function() {
				if (!$(this).attr('data-newsfeed_id')) {
					has_error = true;
					ok(false, "There is an activity item with missing data-newsfeed_id");
				}
			});
			ok(!has_error);
		})
	});
}

if ( qunit_module === undefined || qunit_module == 'popup' ) {
	
	testModule( 'Home page popup', function () {
		// --------------- POPUP --------------
		var preview_popup		= '#preview_popup';
		var popup_comment_form   = preview_popup+' .comments_form';
		var popup_comments	   = preview_popup+' .comments_list .newsfeed_entry_comments';				 //popup comments container
		var new_entry_popup	  = popup_comments+' .newsfeed_entry_comment:last';  //new submitted comment from the popup
		var comment_up_button	= new_entry_popup + ' .up_button';
		var comment_down_button  = new_entry_popup + ' .undo_up_button';
		var comment_content	  = new_entry_popup + ' .comment_content';
		
		var val1				 = "Qunit comment 1";
		var num_comments;
		var $newsfeed_comment; //The new comment in the homepage
		var $new_entry; //The new comment in the popup

		//testEvent('test preview popup shown', preview_popup, 'shown')
		//.trigger(drop+' [rel=popup]:first', 'click');
		
		testVisible('test preview popup shown', preview_popup)
		.trigger(drop+" [href='#preview_popup']", 'click');
		
		testVisible("popup comments load", popup_comment_form, function() {
			num_comments = $(popup_comment_form).closest('.comments_list').find('.newsfeed_entry_comment').length;
		});
		
		// popup form validation
		// submit a new comment (bad) to test validation
		testVisible('Submit popup comment validation', popup_comment_form+' .error', function() {
			console.info('>>> Error shown');
		})
		.trigger(function(){
			$(popup_comment_form + ' textarea.fd_mentions').val('  ').trigger('keyup');
			$(popup_comment_form).submit();
		});
		
		testInvisible("Hide error on keyup", popup_comment_form+' .error', function() {
			console.info('>>> Error hidden');
		})
		.trigger(function() {
			$(popup_comment_form + ' textarea.fd_mentions').val(val1);
			$(popup_comment_form + ' textarea.fd_mentions').trigger('keyup');			
		});
		
		// submit a new comment in popup
		testEvent('submit popup comment', popup_comment_form, 'success', function() {
			ok($(new_entry_popup).attr('data-comment_id'), "New comment not processed");
			visible(new_entry_popup);
			equal($.trim($(comment_content).text()), val1);
			//new comment upvoted by default
			visible (comment_down_button+' .undo_up_wrapper');
			hidden(comment_up_button+' .up_wrapper');
					
			//Check if the comment is added to the newsfeed
			//Homepage comments was disabled
			//$newsfeed_comment = $(drop+' [data-comment_id='+$(new_entry_popup).attr('data-comment_id')+']'); 
			//ok($newsfeed_comment.length, "new comment not added to the newsfeed");
			//equal($newsfeed_comment.find('.comment_body, .comment_content').text(), val1, "synced comment text check")
			//visible ($newsfeed_comment.find('.undo_up_button .upvote_text'));
			//hidden($newsfeed_comment.find('.up_button .upvote_text'));
		})
		.trigger(popup_comment_form, 'submit');
		
		testEvent('undo up button test', comment_down_button, 'success', function() {
			//popup updated
			hidden (comment_down_button+' .undo_up_wrapper');
			visible(comment_up_button+' .up_wrapper');
			equal($(comment_up_button + ' .actionButton_text').text(), '0', "Up count should be 0");
			//newsfeed updated
			//Newsfeed comments was disabled
			//hidden ($newsfeed_comment.find(' .undo_up_button .upvote_text'));
			//visible($newsfeed_comment.find(' .up_button .upvote_text'));
			//equal($newsfeed_comment.find(' .up_button .actionButton_text').text(), '0', "Newsfeed Up count should be 0");
		})
		.trigger( comment_down_button, 'click' );
		
		testEvent('up button test', comment_up_button, 'success', function() {
			//popup updated
			hidden (comment_up_button+' .up_wrapper');
			visible(comment_down_button+' .undo_up_wrapper');
			equal($(comment_down_button + ' .actionButton_text').text(), '1', "Up count should be 1");
			//newsfeed updated
			//Newsfeed comments was disabled
			//hidden ($newsfeed_comment.find(' .up_button .upvote_text'));
			//visible($newsfeed_comment.find(' .undo_up_button .upvote_text'));
			//equal($newsfeed_comment.find(' .undo_up_button .actionButton_text').text(), '1', "Newsfeed Up count should be 1");
		})
		.trigger( comment_up_button, 'click' );

		//remove comment in popup
		testEvent('popup remove comment', new_entry_popup+' .delete_comment', 'success', function() {
			hidden($new_entry, 'Popup Comment should not be visible');			// popup
			//newsfeed comments was disabled
			//hidden($newsfeed_comment, 'Page Comment should not be visible');	// entry
		})
		.trigger( function(){
			$new_entry = $(new_entry_popup);
			$(new_entry_popup+' .delete_comment').trigger('click');
		});
		 
	});
	
	testModule( 'up/down vote (in POPUP) drop home', function () {
		var drop = '#list_newsfeed .newsfeed_entry:first';
		var popup = '#preview_popup';
		
		var up_button = ' .upbox > .up_button';
		var down_button = ' .upbox > .undo_up_button';
		var up_count_el = ' .upbox .up_count';
		var up_count; //will be populated with the current up count
		
		// Make sure the up_button is visible and define the up count so the tests can continue
		test("Basic state check", function() {
			visible(popup+up_button);
			hidden(popup+down_button);
			up_count = parseInt($(popup+up_count_el).text());
		});

		testEvent('up btn in popup click', popup+up_button, 'success', function(e, d) {
			console.log('---- upvote click SUCCESS -----');
			hidden(popup+up_button);
			visible(popup+down_button);
			visible(popup+up_count_el);
			equal(parseInt($(popup+up_count_el).text()), up_count+1, "Up count should be +1");			
			equal(parseInt($(drop+up_count_el).text()), up_count+1, "Up count not synced to newsfeed");			
		})
		.trigger(popup+up_button, 'click');

		testEvent('down btn in popup click', popup+down_button, 'success', function(e, d) {
			console.log('---- downvote click SUCCESS -----');
			visible(popup+up_button);
			hidden(popup+down_button);
			equal(parseInt($(popup+up_count_el).text()), up_count, "Up count should be -1");			
			equal(parseInt($(drop+up_count_el).text()), up_count, "Up count not synced to newsfeed");			
		})
		.trigger(popup+down_button, 'click');
	});
}

// redrop module
if ( qunit_module === undefined || qunit_module == 'redrop' ) {

	testModule( 'newsfeed redrop home', function () {
		
		var button				= drop+' [rel="popup"][href="#collect_popup"]';
		var redrop_popup		= '#collect_popup';
		var redrop_description  = '#redrop_description';
		var newsfeed_id_input   = ' [name="newsfeed_id"]';
		var desc_input		  = ' #redrop_description';
		var description_testing = ' DESCRIPTION TESTING';
		
		var $drop, newsfeed_id, redrop_stat, description;

		testEvent('redrop click -> popup show', redrop_popup, 'show', function(){
			
			$drop = $(button).closest('[data-newsfeed_id]');
			newsfeed_id = parseInt( $drop.attr('data-newsfeed_id') );
			redrop_stat = parseInt( $drop.find('.stat_redrops .num').text() );
			description	= $.trim($drop.find('.drop_desc_plain').text());
			
			// check newsfeed_id
			equal( parseInt($(redrop_popup+newsfeed_id_input).val()), newsfeed_id, "redrop newsfeed_id is wrong");

			// check description of popup
			equal( $.trim($(redrop_popup+desc_input).val()), description, "redrop description is wrong");
			
		}).trigger(button, 'click');
		
// test changing description
		
		// test validation
		testVisible( 'click on edit btn -> show error', redrop_popup + ' div.error')
		  	.trigger(function()	{
		  		$(redrop_description).val('');
		  		$(redrop_popup + ' form').submit();
		  	});
		
		// test submittion
		testVisible('test if success popup is showed','#collect_success_popup')
		.trigger(function(){
		  		$(redrop_description).val(description_testing);
		  		$(redrop_popup + ' form').submit();				
			});
		
		// test submition popup
		testInvisible('test if collection popup is showing','#collect_popup');
	
	});

}

// edit module

if ( qunit_module === undefined || qunit_module == 'edit' ) {
	
	testModule( 'newsfeed edit home', function () {
	
		var drop			 = '#list_newsfeed .newsfeed_entry:first';
		var button			 = '.newsfeed_edit_lnk[rel="popup"]';
		var newsfeed_id		 = parseInt( $(drop).attr('data-newsfeed_id') );
		var description		 = drop + ' .drop_desc_plain';
		var description_testing = 'TEST DESCRIPTION';
		var	popup			   = '#newsfeed_popup_edit';
		
		var edit_post_form	  = popup + ' .edit_post_form:first';
		
		// -------------- test edit popup information -----------------
		// edit popup should show when click on edit btn
		testVisible( 'click on edit btn -> show popup', popup, function() { })
		.trigger( drop + ' ' + button, 'click');
		
		test("check newsfeed_id & description", function() {
		  equal( parseInt( $(popup+' form').find('[name="id"]').val() ), newsfeed_id, "newsfeed_id is wrong");
		  equal( $.trim($( popup + ' [name="description"]').val()) , $.trim($(description).text()), "title is wrong");
		});
		
		// test title validation
		testVisible('test validation',popup + ' div.error')
		.trigger(function(){
			$( popup + ' [name="description"]').val('');
			$(popup+' form').submit();
		});
		
		// -------------- test edit function -----------------
		// test description updated after submit edit form
		var	hashtag;
		
		testEvent( 'change description', edit_post_form , 'success', function( event, data ) {
		  equal( $(description).text(), description_testing + ' ' + hashtag, "drop's description should be changed");
		})
		.trigger(function(){
		  $(popup+' [name="description"]').val(description_testing);
		  $(popup+' .hashtag:first').click();
		  hashtag = $(popup+' .hashtag:first').text();
		  $(edit_post_form).submit();
		});
	
		// -------------- test delete & cancel function -----------------
		var delete_button = popup + ' .delete_button:first';
		var delete_dialog = '#delete_dialog';
		var delete_no = delete_dialog+' .delete_no';
		var delete_yes = delete_dialog+' .delete_yes';
	
		testVisible( 'click on edit btn -> show popup', popup, function() { })
			.trigger( drop + ' ' + button, 'click');
	
		// delete dialog should show when click 'delete' btn
		testVisible('click delete button -> delete dialog show', delete_dialog, function(){ })
		.trigger(delete_button, 'click');
	
		// delete dialog hide when click cancel
		testInvisible('click cancel button -> delete dialog hide', delete_dialog, function(){ })
		.trigger(delete_no, 'click');
	
		// -------------- test delete function -----------------
		testVisible('click delete button -> delete dialog show', delete_dialog, function(){ })
		.trigger(delete_button, 'click');
	
		testEvent('click YES -> delete the drop', delete_yes, 'success', function(){
			hidden($('.newsfeed_entry[data-newsfeed_id="'+newsfeed_id+'"]'));
		})
		.trigger(delete_yes, 'click');
		
	});

}

//// COVERSHEET IS DISABLE
//// coversheet module
//if ( qunit_module === undefined || qunit_module == 'coversheet' ) {
//  QUnit.asyncTest("COVERSHEET: Click coversheet btn", 3, function() {	
//	var button = $('#list_newsfeed .newsfeed_entry .newsfeed_edit_lnk[rel="popup"]');
//	var newsfeed_id = parseInt(button.closest('.newsfeed_entry').attr('rel'));

//	ok( button.length > 0, "COVERSHEET: there is no coversheet button" );
//	ok( ! button.is(':visible'), "COVERSHEET: coversheet button must be invisible at beginning" );
//	button.click();

//	var popup = $('#newsfeed_edit');
//	equal( parseInt(popup.attr('data-newsfeed_id')), newsfeed_id, "COVERSHEET: newsfeed_id is wrong");

//	start();
//  });
//}



if ( qunit_module === undefined || qunit_module == 'internal_scraper_mode' ) {
	testModule( 'Internal scraper / change mode', function function_name () {

		// the tests are exactly the same so run same tests with different title and selectors
		// except image which has two cases of input - upload/url a so it has a callback instead of selector
		function tests ( title, scraperClass, mainInput ) {

			var qBase = '#internal_scraper.open.' + scraperClass;
			var qForm = qBase + ' form[action="/internal_scraper"] ';

			testVisible( title + ': form visible', qBase )
			.trigger( function () {
				$( '#internal_scraper a[href="#' + scraperClass + '"]' ).click();
			} );

			if ( mainInput instanceof Function ) {
				mainInput( title, qForm );
			}
			else {
				testVisible( title + ': visibility main input', qForm + mainInput );
			}
			testVisible( title + ': visibility description', qForm + 'textarea[name="description"]' );
			testVisible( title + ': visibility cancel', qForm + 'input#photoAndLink_cancel' );
			testVisible( title + ': visibility submit', qForm + 'input#photoAndLink_submit' );
			testVisible( title + ': visibility folders', qForm + 'ul.token-input-list-fd_dropdown' );
		}

		tests( 'Image', 'image', function ( title, qForm ) {
			testVisible( title + ': main input (button)', qForm + '.hidden_upload' );
			testVisible( title + ': main input (url)', qForm + 'textarea[name="img"]' )
			.trigger( function () {
				$( qForm + ' a.use_an_url' ).click();
			} );
		} );
			
		tests( 'Url', 'content', 'textarea[name="link_url"]' );
			
		tests( 'Video', 'embed', 'textarea[name="link_url"]' );
			
		tests( 'Text', 'text', 'textarea[name="activity[link][content]"]' );

		// ///// finalize

		testNotExists( 'Hide scraper', '#internal_scraper.open' )
		.trigger( function () {
			$( '#internal_scraper.open input#photoAndLink_cancel' ).click();
		} );
	} );
}



if ( qunit_module === undefined || qunit_module == 'internal_scraper_close' ) {

	testModule( 'Internal scraper close', function () {

		//////////////////// image
			
		// the tests are exactly the same for url / video / text, so just run the same tests with different title and selectors
		// except in the case of image where there are two cases for upload/url and we are using callbacks
		function tests ( title, scraperClass, mainInput ) {
				
			var qBase = '#internal_scraper.open.' + scraperClass;
			if ( !(mainInput instanceof Array) ) {
				mainInput = [ qBase + ' textarea[name="' + mainInput + '"]', qBase + ' textarea[name="description"]' ];
			}
			
			testVisible( title + ': open form', qBase )
			.trigger( function () {
				$( '#internal_scraper a[href="#' + scraperClass + '"]' ).click();
			} );

			testNotExists( title + ': Doc click with empty form', qBase )
			.trigger( function () {
				$( 'body' ).click();
			} );

			for ( var i = 0; i < mainInput.length; ++i ) {
				(function ( input ) {

					testVisible( title + ': open form ' + i, qBase )
					.trigger( function () {
						$( '#internal_scraper a[href="#' + scraperClass + '"]' ).click();
					} );

					//test the selector will not go away
					//in other words wait for the test to timeout and mark it as ok( true ) in this case
					testNotExists( title + ': Doc click with non empty form ' + i, qBase )
					.trigger( function () {

						//fill some values
						if ( input instanceof Function ) {
							input( qBase );
						}
						else {
							$( input ).val( 'asd' );
						}

						//click the document, the form shouldn't go away
						$( 'body' ).click();
					} )
					.timeout( 1000 )
					.reverseok(); //this will reverse the ok() call inside the test, i.e. the test succeed if the wait inside the test timeouts

					testNotExists( title + ': Cancel scaper and reset form ' + i, qBase )
					.trigger( function () {
						//click cancel
						$( qBase + ' input#photoAndLink_cancel' ).click();
					} );
	
					testVisible( title + ': open form ' + i, qBase )
					.trigger( function () {
						$( '#internal_scraper a[href="#' + scraperClass + '"]' ).click();
					} );

					testCondition( title + ': Form is reset after cancel ' + i, function () {
						if ( input instanceof Function ) {
							return input( qBase, true );
						}
						else {
							return $( input ).val().length == 0;
						}
					} );

					testNotExists( title + ': Cancel scaper and reset form ' + i, qBase )
					.trigger( function () {
						//click cancel
						$( qBase + ' input#photoAndLink_cancel' ).click();
					} );

				} )( mainInput[i] );
			}
		}

		var imageInputs = [
			function ( qBase, get ) {
				if ( get ) {
					return $( qBase + ' input[type="file"][name="temp_img"]' ).val().length == 0;
				}
				if ( window.callPhantom !== undefined ) {
					window.callPhantom( {fn: 'callExport', params: ['uploadFile',	qBase + ' input[type="file"][name="temp_img"]', 'js/tests/testimg.png']} );
				}
			},
			function ( qBase, get ) {
				if ( get ) {
					$( qBase + ' a.use_an_url' ).click();
					return $( qBase + ' textarea[name="img"]' ).val().length == 0;
				}
				else {
					$( qBase + ' a.use_an_url' ).click();
					$( qBase + ' textarea[name="img"]' ).val( 'asd' );
				}
			}
		];

		if ( window.callPhantom === undefined ) {
			imageInputs = [ imageInputs[1] ];
		}

		tests( 'Image', 'image', imageInputs );

		tests( 'Url', 'content', 'link_url' );
			
		tests( 'Video', 'embed', 'link_url' );
			
		tests( 'Text', 'text', 'activity[link][content]' );

	} );
}



if ( qunit_module === undefined || qunit_module == 'internal_scraper_validation' ) {

	testModule( 'Internal scraper validation', function () {

		// the tests are exactly the same for url / video / text, so just run the same tests with different title and selectors
		// except for image where there are case of upload and url so we are using callbacks
		function tests( title, scraperClass, mainInput, badValue, goodValue ) {
			
			var qBase = '#internal_scraper.open.' + scraperClass;

			testVisible( title + ': show form', qBase )
			.trigger( function () {
				$( '#internal_scraper a[href="#' + scraperClass + '"]' ).click();
			} );

			testVisible( title + ': validation bad value', qBase + ' .error' )
			.trigger( function () {
				if ( mainInput instanceof Function ) {
					mainInput( qBase, true );
				}
				else {
					$( qBase + ' textarea[name="' + mainInput + '"]' ).val( badValue );
				}
				$( qBase + ' textarea[name="description"]' ).val( 'description #hash' );
				$( qBase + ' input#photoAndLink_submit' ).click();
			} );

			testVisible( title + ': validation no description', qBase + ' .error' )
			.trigger( function () {
				if ( mainInput instanceof Function ) {
					mainInput( qBase );
				}
				else {
					$( qBase + ' textarea[name="' + mainInput + '"]' ).val( goodValue );
				}
				$( qBase + ' textarea[name="description"]' ).val( '' );
				$( qBase + ' input#photoAndLink_submit' ).click();
			} );
			
			/* RR - hashtag validation is disabled by Alexi request
			testVisible( title + ': validation no hash', qBase + ' .error' )
			.trigger( function () {
				$( qBase + ' textarea[name="description"]' ).val( 'description' );
				$( qBase + ' input#photoAndLink_submit' ).click();
			} );
			*/

			testNotExists( 'Hide scraper', '#internal_scraper.open' )
			.trigger( function () {
				$( '#internal_scraper.open input#photoAndLink_cancel' ).click();
			} );
		
		}

		if ( window.callPhantom !== undefined ) {

			tests( 'Image 1', 'image', function ( qBase, bad ) {
				if ( !bad ) {
					window.callPhantom( {fn: 'callExport', params: ['uploadFile',	qBase + ' input[type="file"][name="temp_img"]', 'js/tests/testimg.png']} );
				}
			} );

		}

		tests( 'Image 2', 'image', function ( qBase, bad ) {
			$( qBase + ' a.use_an_url' ).click();
			$( qBase + ' textarea[name="img"]' ).val( bad ? 'badurl' : 'http://upload.wikimedia.org/wikipedia/commons/e/e9/Felis_silvestris_silvestris_small_gradual_decrease_of_quality.png' );
		} );

		//RR - validation is moved to backend
		//tests( 'Url', 'content', 'link_url', 'badurl', 'http://google.com' );
			
		//RR - validation is moved to backend
		//tests( 'Video', 'embed', 'link_url', 'badurl', 'www.youtube.com/watch?v=DeumyOzKqgI' );
			
		tests( 'Text', 'text', 'activity[link][content]', '', 'asdqwe' );

	} );
}





if ( qunit_module === undefined || qunit_module == 'internal_scraper_collections' ) {
	testModule( 'Internal scraper collections', function () {

		var qBase = '#internal_scraper.open.image';
		var qColletions = qBase + ' ul.token-input-list-fd_dropdown';
		var qBaseDropdown = 'div.token-input-dropdown-fd_dropdown';
		var qBaseDropdownUl = 'div.token-input-dropdown-fd_dropdown > ul';
		//var qAdd = qColletions + ' input#token-input-';
		var qAdd = qBaseDropdown + ' input.add-item-input';
		var add_btn = qBaseDropdown + ':visible .add-item-btn'; 
		var qExistingCollection = qBaseDropdown + ' li.token-input-dropdown-item2-fd_dropdown:not(.allow-insert-item):first';
		var qSelectedCollection = qBaseDropdown + ' li.allow-insert-item.token-input-selected-dropdown-item-fd_dropdown';
		var newCollectionName = 'qunit collection ' + (new Date()).valueOf();
		var existingCollectionName = null;
		var hidden_input = qBase + ' input[name="folder_id[0]"]';

		testVisible( 'Form visible', qBase )
		.trigger( function () {
			$( '#internal_scraper a[href="#image"]' ).click();
		} );

		testVisible( 'Collections dropdown visible', qBaseDropdownUl )
		.trigger( function () {
			$( qColletions ).click();
		} );
/*
		testVisible( 'Add new collection button', add_btn)
		.trigger( function () {
			$col = $( qExistingCollection );
			if ( $col.length > 0 ) {
				existingCollectionName = $col.text();
			}
			$( qAdd ).val( newCollectionName ).trigger( 'keydown' );
		} );
		
		testExists( 'Add collection changes folder id', hidden_input+'[value="'+newCollectionName+'"]')
		.trigger( function() {
			$(add_btn).trigger('mousedown');
		});

		testVisible( 'Collections dropdown visible', qBaseDropdownUl )
		.trigger( function () {
			$( qColletions ).click();
		} );*/

		//if there are any other collections try to select the first one and see if folder_id changes
		testCondition( 'Select collection changes folder id', function () {
			$selected = $( qBase + ' input[type="hidden"].tokenInput-hidden' );
			return existingCollectionName === null || ( $selected.attr( 'name' ) != 'folder_id[0]' && $selected.attr( 'value' ) == existingCollectionName );
		} )
		.trigger( function () {
			$( qExistingCollection ).trigger( 'mousedown' ); //.click() doesnt work
		} );
			

		// ///// finalize

		testNotExists( 'Hide scraper', '#internal_scraper.open' )
		.trigger( function () {
			$( '#internal_scraper.open input#photoAndLink_cancel' ).click();
		} );
	} );
}



if ( qunit_module === undefined || qunit_module == 'internal_scraper_preview' ) {
	testModule( 'Internal scraper preview', function () {
			
		var qBase = '#internal_scraper.open';

		///////////////// test video

		testVisible( 'Form visible', qBase + '.embed' )
		.trigger( function () {
			$( '#internal_scraper a[href="#embed"]' ).click();
		} );

		testVisible( 'Loading animation video', qBase + ' ul.img_preview_container > li.sample > img.loading' )
		.trigger( function () {
			$( qBase + ' textarea[name="link_url"]' )
				.val( 'http://www.youtube.com/watch?v=DeumyOzKqgI' )
				.trigger( 'paste' );
		} );

		testVisible( 'Preview video', qBase + ' ul.img_preview_container > li.sample > img:not(.loading)' );

		testCondition( '[link][media] field', function () {
			return $( qBase + ' [name="activity[link][media]"]' ).val().length > 0;
		} );

		// ///// finalize

		testNotExists( 'Hide scraper', '#internal_scraper.open' )
		.trigger( function () {
			$( '#internal_scraper.open input#photoAndLink_cancel' ).click();
		} );

		///////////////// test url

		testVisible( 'Form visible', qBase + '.content' )
		.trigger( function () {
			$( '#internal_scraper a[href="#content"]' ).click();
		} );

		testVisible( 'Loading animation url', qBase + ' ul.img_preview_container > li.sample > img.loading' )
		.trigger( function () {
			$( qBase + ' textarea[name="link_url"]' )
				.val( 'http://www.google.com' )
				.trigger( 'paste' );
		} );

		testVisible( 'Preview url', qBase + ' ul.img_preview_container > li.sample > img:not(.loading)' );

		testCondition( '[link][content] field', function () {
			return $( qBase + ' [name="activity[link][content]"]' ).val().length > 0;
		} );


		///////////////// test image


		if ( window.callPhantom !== undefined ) {
			
			testVisible( 'Form visible', qBase + '.image' )
			.trigger( function () {
				$( '#internal_scraper a[href="#image"]' ).click();
			} );

			//testVisible( 'Loading animation image 1', qBase + ' ul.img_preview_container > li.sample > img.loading' )
			//using the src here because the image doesn't get .loading class as usual
			//testVisible( 'Loading animation image 1', qBase + ' ul.img_preview_container > li.sample > img[src="/images/loading_icons/100x100_transparent.gif"]' )
			//.trigger( function () {
			//	window.callPhantom( {fn: 'callExport', params: ['uploadFile',	qBase + ' input[type="file"][name="temp_img"]', 'js/tests/testimg.png']} );
			//} );

			testVisible( 'Preview image 1', qBase + ' ul.img_preview_container > li.sample > img:not(.loading)' );

			/*//seems this is not used anymore
			testCondition( '[link][img] field', function () {
				return $( qBase + ' [name="activity[link][img]"]' ).val().length > 0;
			} );*/

			testNotExists( 'Hide scraper', '#internal_scraper.open' )
			.trigger( function () {
				$( '#internal_scraper.open input#photoAndLink_cancel' ).click();
			} );

		}

		///////////////// test image

		testVisible( 'Form visible', qBase + '.image' )
		.trigger( function () {
			$( '#internal_scraper a[href="#image"]' ).click();
		} );

		//seems like there is no animation for image upload anymore
		/*testVisible( 'Loading animation image 2', qBase + ' ul.img_preview_container > li.sample > img.loading' )
		.trigger( function () {
			$( qBase + ' a.use_an_url' ).click();
			$( qBase + ' textarea[name="img"]' )
				.val( 'http://upload.wikimedia.org/wikipedia/commons/e/e9/Felis_silvestris_silvestris_small_gradual_decrease_of_quality.png' )
				.trigger( 'paste' );
		} );*/

		testVisible( 'Preview image 2', qBase + ' ul.img_preview_container > li.sample > img:not(.loading)' )
		.trigger( function () {
			$( qBase + ' a.use_an_url' ).click();
			$( qBase + ' textarea[name="img"]' )
				.val( 'http://upload.wikimedia.org/wikipedia/commons/e/e9/Felis_silvestris_silvestris_small_gradual_decrease_of_quality.png' )
				.trigger( 'paste' );
		} );

		/*//seems this is not used anymore
		testCondition( '[link][img] field', function () {
			return $( qBase + ' [name="activity[link][img]"]' ).val().length > 0;
		} );*/

		///////////////// finalize

		testNotExists( 'Hide scraper', '#internal_scraper.open' )
		.trigger( function () {
			$( '#internal_scraper.open input#photoAndLink_cancel' ).click();
		} );

	} );
}




if (
	qunit_module == 'internal_scraper_submit_image_upload' ||
	qunit_module == 'internal_scraper_submit_image_url' ||
	qunit_module == 'internal_scraper_submit_url' ||
	qunit_module == 'internal_scraper_submit_video' ||
	qunit_module == 'internal_scraper_submit_text'
) {

	testModule( 'Internal scraper submit', function () {

		// the tests are exactly the same for url / video / text, so just run the same tests with different title and selectors
		// except for image where there are case of upload and url so we are using callbacks
		function tests( title, scraperClass, mainInput, goodValue, submitTimeout ) {
				
			var qBase = '#internal_scraper.open.' + scraperClass;

			testVisible( title + ': show form', qBase )
			.trigger( function () {
				$( '#internal_scraper a[href="#' + scraperClass + '"]' ).click();
			} );

			testEvent( title + ': Submit', $( window ), 'beforeunload' )
			.timeout( 40000 )
			.trigger( function () {
				if ( mainInput instanceof Function ) {
					mainInput( qBase );
				}
				else {
					$( qBase + ' textarea[name="' + mainInput + '"]' ).val( goodValue ).trigger( 'paste' );
				}
				$( qBase + ' textarea[name="description"]' ).val( 'description #hash' );
				if ( submitTimeout > 0 ) {
					setTimeout( function () {
						$( qBase + ' input#photoAndLink_submit' ).click();
					}, submitTimeout );
				}
				else {
					$( qBase + ' input#photoAndLink_submit' ).click();
				}
			} );
			
		}

		if ( qunit_module == 'internal_scraper_submit_image_upload' && window.callPhantom !== undefined ) {
			tests( 'Image upload', 'image', function ( qBase ) {
				window.callPhantom( {fn: 'callExport', params: ['uploadFile',	qBase + ' input[type="file"][name="temp_img"]', 'js/tests/testimg.png']} );
			}, null, 5000 );
		}

		if ( qunit_module == 'internal_scraper_submit_image_url' ) {
			tests( 'Image url', 'image', function ( qBase ) {
				$( qBase + ' a.use_an_url' ).click();
				$( qBase + ' textarea[name="img"]' ).val( 'http://upload.wikimedia.org/wikipedia/commons/e/e9/Felis_silvestris_silvestris_small_gradual_decrease_of_quality.png' );
			} );
		}

		if ( qunit_module == 'internal_scraper_submit_url' ) {
			tests( 'Url', 'content', 'link_url', 'http://google.com', 5000 );
		}
		
		if ( qunit_module == 'internal_scraper_submit_video' ) {
			tests( 'Video', 'embed', 'link_url', 'www.youtube.com/watch?v=DeumyOzKqgI', 5000 );
		}
		
		if ( qunit_module == 'internal_scraper_submit_text' ) {
			tests( 'Text', 'text', 'activity[link][content]', 'asdqwe' );
		}

	} );
}

/* RR - this is failing on deploy - works ok on local
if ( qunit_module === undefined || qunit_module == 'notification' ) {
	testModule( 'Home page notification test ', function () {
		var notification		= '#hdr_notifications';
		var notification_unread = notification+' .unread_notification';
		var notification_dialog = '#notifications';
		var notification_count; 

		testVisible('Notification dialog shown', notification_dialog)
		.trigger(function(){
			notification_count = parseInt( $(notification).text() );
			notification_unread = $(notification_unread).length;
			$(notification).click();
		});

		test('check notification counter', function(){
			equal( parseInt( $(notification).text() ), notification_count - notification_unread, 'notification count must be reduced by unread items' );
		});
	});
}
*/
