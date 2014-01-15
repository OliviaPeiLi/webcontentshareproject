QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

var len;
var $first_item;
var orig_img;
//Selectors
add_form = 'form.temp';
add_form_preview = add_form+' .addList_body img';

test("basic page state", function() {
	ok($('.listManager_createList_button').length);
	ok($('.listManager_listList li').length);
	ok($('.editList_upper li').length)
	len = $('.editList_upper li').length;
	orig_img = $(add_form_preview).attr('src');
	$first_item = $('.editList_upper li .ico.text:first').closest('li');
	$first_item.addClass('for_delete');
});

if ( qunit_module === undefined || qunit_module == 'base' )	{
	testModule( 'Autoscroll', function () {
		
		asyncTest("Autoscroll", 1, function() {
			var interval = window.setInterval(function() {
				if ($('.editList_upper li').length > len) {
					window.clearInterval(interval);
					ok(true); start();
				}
			}, 100);
			
			$('.editList_upper').trigger('scroll_bottom');
		});
			
		testVisible("Delete confirm", '#confirm')
		.trigger(function() {
			console.info('DELETE', $first_item);
			$first_item.find('a.itemDelete').click();
		});
		
		testEvent("Delete success", "#confirm", 'success')
		.trigger('#confirm [rel=ajaxButton]','click');
		
		testInvisible("Deleted item hidden", '.editList_upper li.for_delete');
		
	});
	
}

if ( qunit_module === undefined || qunit_module == 'add_show' )	{
	testModule( 'Add post', function () {
		
		testVisible("add post show", add_form)
		.trigger('.editList_actions .editList_preview', 'click');
		
		test("add post shown", function() {
			ok($('.editList_upper li').length, len, "New item not added");
			ok($('.editList_upper li[id="newsfeed_id_undefined"]').length, "Temp item not found");
			//form fields
			visible(add_form_preview);
			visible(add_form+' .form_row.image');
			visible(add_form+' .form_row.title');
			visible( add_form+' .form_row.link');
			//buttons
			visible('.editList_actions .addList_save');
			visible('.editList_actions .addList_cancel');
			hidden('.editList_actions .editList_preview');
		});
		
		testInvisible("add post hide", add_form)
		.trigger('.editList_actions .addList_cancel', 'click');

		test("add post hidden", function() {
			//autoscroll may execute and more items to 
			//equal($('.editList_upper li').length, len, "New item not removed");
			hidden('.editList_actions .addList_save');
			hidden('.editList_actions .addList_cancel');
			visible('.editList_actions .editList_preview');
		});
		
		testVisible("add post show", add_form)
		.trigger('.editList_actions .editList_preview', 'click');
		
		test("add post shown 1", function() {
			ok($('.editList_upper li[id="newsfeed_id_undefined"]').length, "Temp item not found");			
		});
		
		testInvisible("add post hide", add_form)
		.trigger('.editList_upper li[id="newsfeed_id_undefined"] .itemDelete', 'click');

	});
}

if ( qunit_module === undefined || qunit_module == 'validation' )	{
	testModule( 'Validation', function () {
		
		//var error = add_form+" .addList_body > .error";
		var error = '#notification_bar p';
		
		testVisible("add post show", add_form)
		.trigger('.editList_actions .editList_preview', 'click');
		
		testVisible("Validation image", error)
		.trigger(add_form, 'submit')
		
		testCondition("Change image", function() {
			return $(add_form_preview).attr('src') != orig_img;
		})
		.trigger(function() {
			window.callPhantom('uploadFile', [add_form+' [name=temp_img]']);
		});
		
		testCondition("Switch to URL", function() {
			return $('.addList_mediaButtons [href="#content"]').hasClass('active');
		})
		.trigger('.addList_mediaButtons [href="#content"]','click');
		
		test("URL mode", function() {
			hidden(add_form+' .addList_previewContainer');
			hidden(add_form+' .form_row.image');
			visible(add_form+' .form_row.title');
			visible( add_form+' .form_row.link');			
		});
		
		//testVisible("Validation url", add_form+" .form_row.link .error")
		testVisible("Validation url", error)
		.trigger(add_form, 'submit');
		//testVisible("Validation url", add_form+" .form_row.title .error");
		testVisible("Validation url", error);
		
		//testInvisible("Validation reset url err", add_form+' .form_row.link .error')
		testInvisible("Validation reset url err", error)
		.trigger(function() {
			$(add_form+' .form_row.link textarea').val('asd').trigger('keyup');
		});
		
		//testInvisible("Validation reset desc err", add_form+' .form_row.title .error')
		testInvisible("Validation reset desc err", error)
		.trigger(function() {
			$(add_form+' .form_row.title textarea:visible').val('asd1').trigger('keyup');
		});
		
		//testVisible("Validation invalid url", add_form+" .form_row.link .error")
		testVisible("Validation invalid url", error)
		.trigger(function() {
			$(add_form+' .form_row.link textarea').val('asd').trigger('keypress');
		})
		
		
		testCondition("Switch to Video", function() {
			return $('.addList_mediaButtons [href="#embed"]').hasClass('active');
		})
		.trigger('.addList_mediaButtons [href="#embed"]','click');
		
		test("Video mode", function() {
			visible(add_form+' .addList_previewContainer');
			hidden(add_form+' .form_row.image');
			visible( add_form+' .form_row.link');			
			visible(add_form+' .form_row.title');
		});
		
		testCondition("Load video", function() {
			return $(add_form_preview).attr('src') != orig_img;
		})
		.trigger(function() {
			$(add_form+' .form_row.link textarea').val('http://www.youtube.com/watch?v=QH2-TGUlwu4').trigger('keypress');
		});

		testCondition("Switch to Text", function() {
			return $('.addList_mediaButtons [href="#text"]').hasClass('active');
		})
		.trigger('.addList_mediaButtons [href="#text"]','click');
		
		test("Text mode", function() {
			hidden(add_form+' .addList_previewContainer');
			hidden(add_form+' .form_row.image');
			hidden( add_form+' .form_row.link');			
			visible(add_form+' .form_row.title');
			visible(add_form+' .form_row.text');
		});
		
		//testVisible("Validation story", add_form+" .form_row.text .error")
		testVisible("Validation story", error)
		.trigger(function() {
			$(add_form+' .form_row.text textarea').val('');
			$(add_form).submit();
		})
		
		testEvent("Text submit", add_form, "success")
		.trigger(function() {
			$(add_form+' .form_row.text textarea').val('text test');
			$(add_form).submit();
		})
		
		//Adding another item bc its deleted in collections test and the number needs to be balanced
		testVisible("add post show", add_form)
		.trigger('.editList_actions .editList_preview', 'click');
		testCondition("Switch to Text", function() {
			return $('.addList_mediaButtons [href="#text"]').hasClass('active');
		})
		.trigger('.addList_mediaButtons [href="#text"]','click');
		testEvent("Text submit", add_form, "success")
		.trigger(function() {
			$(add_form+' .form_row.title textarea').val('TEXT test');
			$(add_form+' .form_row.text textarea').val('text test');
			$(add_form).submit();
		})

	});
}

if ( qunit_module === undefined || qunit_module == 'edit' )	{
	testModule( 'Edit', function () {
		var $item; 
		
		test("Find a text post", function() {
			ok($('.editList_upper li .ico.text').length, "Text post not found");
			$item = $('.editList_upper li .ico.text:first').closest('li');
		});
		
		testCondition("Edit text", function() {
			return $('.addList_mediaButtons [href="#text"]').hasClass('active');
		})
		.trigger(function() { $item.find('.itemEdit').click(); });
		
		test("Edit text form state", function() {
			equal($(add_form+' [name="newsfeed_id"]').val(), $item.attr('id').replace('newsfeed_id_',''));
			equal($(add_form+' [name="link_type"]').val(), 'text');
			equal($(add_form+' [name="description_orig"]').val(), $item.find('.itemDescription span').text());
			equal($(add_form+' [name="activity[link][content]"]').val(), $item.find('.textContent').text());
		});
		
		test("Find a video post", function() {
			ok($('.editList_upper li .ico.embed').length, "Video post not found");
			$item = $('.editList_upper li .ico.embed:first').closest('li');
		});
		
		testCondition("Edit Video", function() {
			return $('.addList_mediaButtons [href="#embed"]').hasClass('active');
		})
		.trigger(function() { $item.find('.itemEdit').click(); });
		
		test("Edit video form state", function() {
			equal($(add_form+' [name="newsfeed_id"]').val(), $item.attr('id').replace('newsfeed_id_',''));
			equal($(add_form+' [name="link_type"]').val(), 'embed');
			equal($(add_form+' .addList_previewContainer img').attr('src'), $item.find('img').attr('src'));
			equal($(add_form+' [name="link_url"]').val(), $item.attr('data-link_url'));
			equal($(add_form+' [name="description_orig"]').val(), $item.find('.itemDescription span').text());
		});
		
		test("Find an URL post", function() {
			ok($('.editList_upper li .ico.content').length, "URL post not found");
			$item = $('.editList_upper li .ico.content').first().closest('li');
		});
		
		testCondition("Edit Content", function() {
			return $('.addList_mediaButtons [href="#content"]').hasClass('active');
		})
		.trigger(function() { $item.find('.itemEdit').click(); });
		
		test("Edit URL form state", function() {
			equal($(add_form+' [name="newsfeed_id"]').val(), $item.attr('id').replace('newsfeed_id_',''));
			equal($(add_form+' [name="link_type"]').val(), 'content');
			equal($(add_form+' [name="link_url"]').val(), $item.attr('data-link_url'));
			equal($(add_form+' [name="description_orig"]').val(), $item.find('.itemDescription span').text());
		});
		
		test("Find an Html post", function() {
			ok($('.editList_upper li .ico.html').length, "html post not found");
			$item = $('.editList_upper li .ico.html').first().closest('li');
		});
		
		testCondition("Edit Html", function() {
			return $('.addList_mediaButtons [href="#content"]').hasClass('active');
		})
		.trigger(function() { $item.find('.itemEdit').click(); });
		
		test("Edit Html form state", function() {
			equal($(add_form+' [name="newsfeed_id"]').val(), $item.attr('id').replace('newsfeed_id_',''));
			equal($(add_form+' [name="link_type"]').val(), 'html');
			equal($(add_form+' [name="link_url"]').val(), $item.attr('data-link_url'));
			equal($(add_form+' [name="description_orig"]').val(), $item.find('.itemDescription span').text());
		});
		
		test("Find an Image post", function() {
			ok($('.editList_upper li .ico.image').length, "Image post not found");
			$item = $('.editList_upper li .ico.image').first().closest('li');
		});
		
		testCondition("Edit Image", function() {
			return $('.addList_mediaButtons [href="#image"]').hasClass('active');
		})
		.trigger(function() { $item.find('.itemEdit').click(); });
		
		test("Edit Image form state", function() {
			ok($('.addList_mediaButtons [href="#image"]').hasClass('active'), "Image button is not set to active");
			equal($(add_form+' [name="newsfeed_id"]').val(), $item.attr('id').replace('newsfeed_id_',''));
			equal($(add_form+' [name="link_type"]').val(), 'image');
			equal($(add_form+' .addList_previewContainer img').attr('src'), $item.find('img').attr('src'));
			equal($(add_form+' [name="description_orig"]').val(), $item.find('.itemDescription span').text());
		});

	});
}