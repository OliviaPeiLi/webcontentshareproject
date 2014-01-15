/**
 * Home page tests - user logged in
 */

QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

//bobef: if this so i can test the internal scraper tests separately
if ( qunit_module === undefined || qunit_module == 'home' ) {
	var postcard_comment    = '#list_newsfeed .newsfeed_entry:first .postcard_comments';
	var entry               = postcard_comment+' .newComment';
	var new_entry           = postcard_comment+' .postcard_comment:last';    //the new submitted comment
	var val                 = 'Some qunit test comment';                     //Sample text for the testing comment

	testModule( 'Home page', function () {
		var new_comment_input   = entry + ' textarea.fd_mentions';               //postcard comment form textarea

		testInvisible('New comment field should be hidden by default', entry)
		.trigger(function(){});

		testVisible('New comment field should be show', entry)
		.trigger( $(entry).closest('.newsfeed_entry').find('.newsfeed_comments_lnk'), 'click' );

		var orig_name         = $(new_comment_input).attr('name');
		var orig_count        = parseInt($(entry).find('.comment_char_count').text());

		test("New comment mentions and char count", function() {
			ok(!isNaN(orig_count), "Original charcount");
		});

		testEvent("New comment mentions and char count", new_comment_input, 'keyup', function() {
			visible(entry+' .comment_char_count');
			equal(parseInt($(entry).find('.comment_char_count').text()), orig_count-val.length, "Char count bad");
			equal($(new_comment_input).attr('name'), orig_name+'_orig', "Mentions should be loaded");
		})
		.trigger( function(){
			$(new_comment_input).val(val);
			$(new_comment_input).trigger('keyup');
		});

		testVisible('Submit comment validation', entry+' .error', function(){
		})
		.trigger( function(){
			$(new_comment_input).val('  ').trigger('keyup');
			$(entry).find('form').submit();
		});

		testInvisible('hide error message on keyup', entry+' .error')
		.trigger( function(){
			$(new_comment_input).val(val).trigger('keyup');
		});

		var num_comments     = $(entry).parent().find('.postcard_comment').length;
		var num_comments_txt = parseInt($(entry).parent().find('.num_comments').text());

		testEvent('submit comment success', entry+' form', 'success', function(){
			equal($(entry).parent().find('.postcard_comment').length, num_comments+1, "New entry not added");
			equal(parseInt($(entry).parent().find('.num_comments').text()), num_comments_txt+1, "Num comments text not changed");
			hidden(entry+' .error');
		})
		.trigger(function(){
			$(new_comment_input).val(val).trigger('keyup');
			$(entry+' form').submit();
		});

		testEvent('Upvote comment', new_entry+' .up_button', 'success', function(){
			hidden (new_entry + ' .up_button .upvote_text', "Up button should not be visible");
			visible(new_entry + ' .undo_up_button .upvote_text', "undoUp button should be visible");
			visible(new_entry + ' .undo_up_button .actionButton_text', "Up count should be visible");

			equal($(new_entry + ' .undo_up_button .actionButton_text').text(), '1', "Up count should be 1");
		})
		.trigger( new_entry + ' .up_button', 'click' );

		testEvent('Unup comment', new_entry+' .undo_up_button', 'success', function(){
			visible(new_entry+' .up_button .upvote_text', "Up button should be visible");
			hidden (new_entry+' .undo_up_button .upvote_text', "undoUp button should be visible");
			hidden (new_entry+' .undo_up_button .actionButton_text', "Up count should be hidden");
		})
		.trigger( new_entry+' .undo_up_button', 'click' );

		// --------------- POPUP --------------
		var preview_popup    = '#preview_popup';
		var popup_comments   = '#preview_popup .comments_list';                   //popup comments container
		var new_entry_popup  = popup_comments + ' .newsfeed_entry_comment:last';  //new submitted comment from the popup
		var comment_up_button     = new_entry_popup + ' .up_button';
		var comment_down_button   = new_entry_popup + ' .undo_up_button';
		var comment_content       = new_entry_popup + ' .comment_content';
		var comment_id            = $(new_entry).attr('data-comment_id');

		// After popup show, it takes few time to prepare comment list
		// so that testVisible is done after testEvent(preview_popup show)
		// put this setting here to make testVisible with selector 'new_entry_popup'
		testEvent('test preview popup shown', preview_popup, 'shown', function(){
			console.log('--- POPUP SHOWN ---');
			ok(true);
			wait( new_entry_popup, false, function(){}, function(){
				visible(new_entry_popup);
				visible(comment_up_button);
				visible(comment_up_button + ' .upvote_text');
				hidden (comment_down_button);
				hidden (comment_down_button + ' .upvote_text');
				equal($.trim($(comment_content).text()), val, "New comment text check");
			}, 20);
		})
		.trigger(function(){
			$(new_entry).closest('.postcard_entry').find('.postcard_contents').click();
		});

		// TODO: this is a bug
		//testExists('wait up_button of comment visible', comment_up_button);
		//testEvent('up button test', comment_up_button, 'success', function(){
		//  ok(true);
		//  wait( new_entry_popup, false, function(){}, function(){
		//    console.log('--- elements were set ---');
		//    //popup updated
		//    hidden (comment_up_button);
		//    hidden (comment_up_button   + ' .upvote_text');
		//    visible(comment_down_button + ' .upvote_text');
		//    visible(comment_down_button + ' .actionButton_text');
		//    equal($(comment_down_button + ' .actionButton_text').text(), '1', "Up count should be 1");
		//    //newsfeed updated
		//    hidden (new_entry + ' .up_button .upvote_text');
		//    visible(new_entry + ' .undo_up_button .upvote_text');
		//    equal($(new_entry + ' .undo_up_button .actionButton_text').text(), '1', "Newsfeed Up count should be 1");
		//  }, 20);
		//})
		//.trigger( comment_up_button, 'click' );

		testExists('wait undo_up_button of comment visible', comment_down_button);
		testEvent('undo up button test', comment_down_button, 'success', function(){
			ok(true);
			wait( new_entry_popup, false, function(){}, function(){
				console.log('--- elements were set ---');
				//popup updated
				hidden (comment_down_button);
				hidden (comment_down_button   + ' .upvote_text');
				visible(comment_up_button + ' .upvote_text');
				visible(comment_up_button + ' .actionButton_text');
				equal($(comment_up_button + ' .actionButton_text').text(), '0', "Up count should be 0");
				//newsfeed updated
				hidden (new_entry + ' .undo_up_button .upvote_text');
				visible(new_entry + ' .up_button .upvote_text');
				equal($(new_entry + ' .up_button .actionButton_text').text(), '0', "Newsfeed Up count should be 0");
			}, 20);
		})
		.trigger( comment_down_button, 'click' );

		// --- remove comment
		var last_entry;
		var	num_comments;
		var	num_comments_txt;

		testEvent('remove comment', new_entry+' .delete_comment', 'success', function(){
			hidden(last_entry);
			equal (parseInt($(new_entry).parent().find('.num_comments').text()), num_comments_txt-1, "Num comments text should be updated" );
		})
		.trigger( function(){
			last_entry = $(new_entry);
			num_comments_txt = parseInt(last_entry.parent().find('.num_comments:visible').text());
			$(new_entry+ ' .delete_comment').click();
		});

		var popup_comment_form       = '#preview_popup .comments_form';
		var val1                     = "Qunit comment 1";
		var popup_comments_container; // = popup_comment_form.closest('.comments_list');

		// popup form validation
		// submit a new comment (bad) to test validation
		testVisible('test validation', popup_comment_form+' .error')
		.trigger(function(){
			$(popup_comment_form + ' textarea.fd_mentions').val('  ').trigger('keyup');
			$(popup_comment_form).submit();
		});

		// submit a new comment in popup
		var newsfeed_entry;
		testEvent('submit popup comment', popup_comment_form, 'success', function(){
			ok($(new_entry).attr('data-comment_id'), "New comment not processed");
			equal($.trim($(new_entry + ' .comment_body').text()), val1, "New comment text check")

			// TODO: bug ?
			//visible(new_entry + ' .up_button .upvote_text');
			//hidden (new_entry + ' .undo_up_button .upvote_text');
					
			//Check if the comment is added to the newsfeed
			ok(newsfeed_entry.length, "new comment not added to the newsfeed");
			equal($.trim(newsfeed_entry.find('.comment_body, .comment_content').text()), val1, "synced comment text check")

			// TODO: bug ?
			//visible(newsfeed_entry.find('.up_button .upvote_text'), "synced Up button should be visible");
			//hidden (newsfeed_entry.find('.undo_up_button .upvote_text'), "synced undoUp button should not be visible");
		})
		.trigger(function(){
			newsfeed_entry = $(entry).closest('.postcard_comments').find('[data-comment_id='+$(new_entry).attr('data-comment_id')+']');
			num_comments = $(popup_comment_form).closest('.comments_list').find('.newsfeed_entry_comment').length;

			$(popup_comment_form + ' textarea.fd_mentions').val(val1).trigger('keyup');
			$(popup_comment_form).submit();
		});

		// remove comment in popup
		var num_popup_comments;
		testEvent('popup remove comment', new_entry+' .delete_comment', 'success', function(){
			hidden(last_entry, 'Comment should not be visible');            // popup
			hidden(newsfeed_entry, 'Comment should not be visible');        // entry
		})
		.trigger( function(){
			newsfeed_entry = $(entry).closest('.postcard_comments').find('[data-comment_id='+$(new_entry).attr('data-comment_id')+']');
			last_entry = $(new_entry);

			$(new_entry+' .delete_comment').click();
		});

	});
		
}

if ( qunit_module === undefined || qunit_module == 'updownvote' ) {
	testModule( 'up/down vote drop home', function () {
		var drop = '#list_newsfeed .newsfeed_entry:first';
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
			console.log('---- upvote click SUCCESS -----');
			hidden(up_button);
			visible(down_button);
			visible(up_count_el);
			equal(parseInt($(up_count_el).text()), up_count+1, "Up count should be +1");			
		})
		.trigger(up_button, 'click');

		// --------- test downvote in home -----------
		testEvent('Downvote', down_button, 'success', function(){
			console.log('---- downvote click SUCCESS -----');
			visible(up_button);
			hidden(down_button);
			equal(parseInt($(up_count_el).text()), up_count, "Up count should be -1");			
		})
		.trigger(down_button, 'click');
	});

	testModule( 'up/down vote (in POPUP) drop home', function () {
		var drop = '#list_newsfeed .newsfeed_entry:first';
		var popup = '#preview_popup';
		
		var up_button = ' .upbox > .up_button';
		var down_button = ' .upbox > .undo_up_button';
		var up_count_el = ' .upbox .up_count';
		var up_count; //will be populated with the current up count

		// --------- open the popup to test in it
		testVisible('drop popup should open', popup)
		.trigger( drop+' [rel=popup]:first', 'click');
		
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
		var button              = '#list_newsfeed .newsfeed_entry:first [rel="popup"][data-group="collect_dialog"]';
		var newsfeed_id         = parseInt( $(button).closest('.newsfeed_entry').attr('data-newsfeed_id') );
		var redrop_stat         = parseInt( $(button).closest('.newsfeed_entry').find('.stat_redrops .num').text() );
		var description         = $(button).closest('.newsfeed_entry').find('.drop_desc_plain').text();
		var description_testing = description + " REDROP";
		var popup               = '#collect_popup';
		var redrop_description  = '#redrop_description';

		testEvent('redrop click -> popup show', popup, 'show', function(){
			// check newsfeed_id
			equal( parseInt($(popup+' [name="newsfeed_id"]').attr('value')), newsfeed_id, "redrop newsfeed_id is wrong");

			// check description of popup
			equal( $('#redrop_description').val(), description, "redrop description is wrong");
		})
		.trigger(button, 'click');

		// test changing description
		// TODO: bug, BE return error/warning
		//var hashtag;
		//testEvent('change description -> check updated description', popup + ' form', 'success', function() {
		//  // updated description should = testing description
		//  equal( $(".newsfeed_entry[data-newsfeed_id="+newsfeed_id+"] .drop_desc_plain").text(), description_testing+' '+hashtag, "redropped description should be changed");

		//  // check redrop status number
		//  equal( parseInt( $(".newsfeed_entry[data-newsfeed_id="+newsfeed_id+"] .stat_redrops > .num").text() ), redrop_stat+1, "redrop count must be +1");
		//})
		//.trigger(function(){
		//  $(redrop_description).val(description_testing);
		//  $(popup+' .hashtag:first').click();
		//  hashtag = $(popup+' .hashtag:first').text();
		//  $(popup+' form').submit();
		//});

	});

}

// edit module
// TODO: edit button is now in preview_popup ?
//if ( qunit_module === undefined || qunit_module == 'edit' ) {
//  testModule( 'newsfeed edit home', function () {
//    var drop                = '#list_newsfeed .newsfeed_entry:first';
//    var button              = '.newsfeed_edit_lnk[rel="popup"]';
//    var newsfeed_id         = parseInt( $(drop).attr('data-newsfeed_id') );
//    var description         = drop+' .drop_desc_plain';
//    var description_testing = 'TEST DESCRIPTION';
//    var	popup               = '#newsfeed_popup_edit';

//    var edit_post_form      = popup+' .edit_post_form:first';

//    // -------------- test edit popup information -----------------
//    // edit popup should show when click on edit btn
//    testVisible( 'click on edit btn -> show popup', popup, function() { })
//    .trigger(button, 'click');

//    test("check newsfeed_id & description", function() {
//      equal( parseInt( $(popup+' form').find('[name="id"]:hidden').val() ), newsfeed_id, "newsfeed_id is wrong");
//      equal( $(popup+' [name="description"]').val() , $(description).text(), "title is wrong");
//    });

//    // -------------- test edit function -----------------
//    // test description updated after submit edit form
//    var	hashtag;
//    testEvent( 'change description', edit_post_form , 'success', function(event, data) {
//      equal( $(description).text(), description_testing+' '+hashtag, "drop's description should be changed");
//    })
//    .trigger(function(){
//      $(popup+' [name="description"]').val(description_testing);
//      $(popup+' .hashtag:first').click();
//      hashtag = $(popup+' .hashtag:first').text();
//      $(edit_post_form).submit();
//    });

//    // -------------- test delete & cancel function -----------------
//    var delete_button = popup + ' .delete_button:first';
//    var delete_dialog = '#delete_dialog';
//    var delete_no = delete_dialog+' .delete_no';
//    var delete_yes = delete_dialog+' .delete_yes';

//    testVisible( 'click on edit btn -> show popup', popup, function() { })
//    .trigger(button, 'click');

//    // delete dialog should show when click 'delete' btn
//    testVisible('click delete button -> delete dialog show', delete_dialog, function(){ })
//    .trigger(delete_button, 'click');

//    // delete dialog hide when click cancel
//    testInvisible('click cancel button -> delete dialog hide', delete_dialog, function(){ })
//    .trigger(delete_no, 'click');

//    //// -------------- test delete function -----------------
//    //testVisible('click delete button -> delete dialog show', delete_dialog, function(){ })
//    //.trigger(delete_button, 'click');

//    //testEvent('click YES -> delete the drop', delete_yes, 'success', function(){
//    //  hidden( $('.newsfeed_entry[data-newsfeed_id="'+newsfeed_id+'"]') );
//    //})
//    //.trigger(delete_yes, 'click');

//  });

//}

//// COVERSHEET IS DISABLE
//// coversheet module
//if ( qunit_module === undefined || qunit_module == 'coversheet' ) {
//  QUnit.asyncTest("COVERSHEET: Click coversheet btn", 3, function() {	
//    var button = $('#list_newsfeed .newsfeed_entry .newsfeed_edit_lnk[rel="popup"]');
//    var newsfeed_id = parseInt(button.closest('.newsfeed_entry').attr('rel'));

//    ok( button.length > 0, "COVERSHEET: there is no coversheet button" );
//    ok( ! button.is(':visible'), "COVERSHEET: coversheet button must be invisible at beginning" );
//    button.click();

//    var popup = $('#newsfeed_edit');
//    equal( parseInt(popup.attr('data-newsfeed_id')), newsfeed_id, "COVERSHEET: newsfeed_id is wrong");

//    start();
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
			
		tests( 'Url', 'content', 'textarea[name="activity[link][link]"]' );
			
		tests( 'Video', 'embed', 'textarea[name="activity[link][link]"]' );
			
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

		tests( 'Url', 'content', 'activity[link][link]' );
			
		tests( 'Video', 'embed', 'activity[link][link]' );
			
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

			testVisible( title + ': validation no hash', qBase + ' .error' )
			.trigger( function () {
				$( qBase + ' textarea[name="description"]' ).val( 'description' );
				$( qBase + ' input#photoAndLink_submit' ).click();
			} );

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

		tests( 'Url', 'content', 'activity[link][link]', 'badurl', 'http://google.com' );
			
		tests( 'Video', 'embed', 'activity[link][link]', 'badurl', 'www.youtube.com/watch?v=DeumyOzKqgI' );
			
		tests( 'Text', 'text', 'activity[link][content]', '', 'asdqwe' );

	} );
}





if ( qunit_module === undefined || qunit_module == 'internal_scraper_collections' ) {
	testModule( 'Internal scraper collections', function () {

		var qBase = '#internal_scraper.open.image';
		var qColletions = qBase + ' ul.token-input-list-fd_dropdown';
		var qBaseDropdown = 'div.token-input-dropdown-fd_dropdown';
		var qBaseDropdownUl = 'div.token-input-dropdown-fd_dropdown > ul';
		var qAdd = qColletions + ' input#token-input-';
		var qExistingCollection = qBaseDropdown + ' li.token-input-dropdown-item2-fd_dropdown:not(.allow-insert-item):first';
		var qSelectedCollection = qBaseDropdown + ' li.allow-insert-item.token-input-selected-dropdown-item-fd_dropdown';
		var newCollectionName = 'qunit collection ' + (new Date()).valueOf();
		var existingCollectionName = null;

		testVisible( 'Form visible', qBase )
		.trigger( function () {
			$( '#internal_scraper a[href="#image"]' ).click();
		} );

		testVisible( 'Collections dropdown visible', qBaseDropdownUl )
		.trigger( function () {
			$( qColletions ).click();
		} );

		testCondition( 'Add new item is displayed', function () {
			return $( qSelectedCollection ).text() == 'Add: ' + newCollectionName;
		} )
		.trigger( function () {
			
			//now that the dropdown is open check if there is existing collection and save it for later
			$col = $( qExistingCollection );
			if ( $col.length > 0 ) {
				existingCollectionName = $col.text();
			}
			//

			$( qAdd ).val( newCollectionName ).trigger( 'keydown' );
		} );

		testExists( 'Add collection changes folder id', qBase + ' input[type="hidden"][name="folder_id[0]"][value="' + newCollectionName + '"].tokenInput-hidden' )
		.trigger( function () {
			var key = jQuery.Event( 'keydown' );
			key.keyCode = 13;
			$( qAdd ).trigger( key );
		} );

		testVisible( 'Collections dropdown visible', qBaseDropdownUl )
		.trigger( function () {
			$( qColletions ).click();
		} );

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
			$( qBase + ' textarea[name="activity[link][link]"]' )
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
			$( qBase + ' textarea[name="activity[link][link]"]' )
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
			testVisible( 'Loading animation image 1', qBase + ' ul.img_preview_container > li.sample > img[src="/images/loading_icons/bigRoller_32x32.gif"]' )
			.trigger( function () {
				window.callPhantom( {fn: 'callExport', params: ['uploadFile',	qBase + ' input[type="file"][name="temp_img"]', 'js/tests/testimg.png']} );
			} );

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
		function tests( title, scraperClass, mainInput, goodValue ) {
				
			var qBase = '#internal_scraper.open.' + scraperClass;

			testVisible( title + ': show form', qBase )
			.trigger( function () {
				$( '#internal_scraper a[href="#' + scraperClass + '"]' ).click();
			} );

			testEvent( title + ': Submit', $( window ), 'beforeunload' )
			.timeout( 20000 )
			.trigger( function () {
				if ( mainInput instanceof Function ) {
					mainInput( qBase );
				}
				else {
					$( qBase + ' textarea[name="' + mainInput + '"]' ).val( goodValue );
				}
				$( qBase + ' textarea[name="description"]' ).val( 'description #hash' );
				$( qBase + ' input#photoAndLink_submit' ).click();
			} );
			
		}

		if ( qunit_module == 'internal_scraper_submit_image_upload' && window.callPhantom !== undefined ) {
			tests( 'Image upload', 'image', function ( qBase ) {
				window.callPhantom( {fn: 'callExport', params: ['uploadFile',	qBase + ' input[type="file"][name="temp_img"]', 'js/tests/testimg.png']} );
			} );
		}

		if ( qunit_module == 'internal_scraper_submit_image_url' ) {
			tests( 'Image url', 'image', function ( qBase ) {
				$( qBase + ' a.use_an_url' ).click();
				$( qBase + ' textarea[name="img"]' ).val( 'http://upload.wikimedia.org/wikipedia/commons/e/e9/Felis_silvestris_silvestris_small_gradual_decrease_of_quality.png' );
			} );
		}

		if ( qunit_module == 'internal_scraper_submit_url' ) {
			tests( 'Url', 'content', 'activity[link][link]', 'http://google.com' );
		}
		
		if ( qunit_module == 'internal_scraper_submit_video' ) {
			tests( 'Video', 'embed', 'activity[link][link]', 'www.youtube.com/watch?v=DeumyOzKqgI' );
		}
		
		if ( qunit_module == 'internal_scraper_submit_text' ) {
			tests( 'Text', 'text', 'activity[link][content]', 'asdqwe' );
		}

	} );
}