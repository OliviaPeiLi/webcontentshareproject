
/* *********************************************************
 * Image search in google (for image upload)
 *
 * ******************************************************* */


define(['jquery'], function() {

	console.log('getimages.js');
	
	/*
    $(".loaded_images").live('click', function(){
    	console.log(location.href);
    	//console.log('clicked');
    	var img_options = $($(window.parent.document).find('#imgupload_options')[0]);
    	var album = 'profile';
		var page_id = php.page_id;
		var topic_id = img_options.find('input[name=topic_id]').val();
		
		var is_page = img_options.find('#ispage').val();
		var ajax = '1';
		//var src_img_url = $(this).attr('src');
		var src_img_url = $(this).attr('rel');
		var csrf_token = $('input[name=ci_csrf_token]').val();
		var data = {ci_csrf_token:csrf_token, album:album, page_id:page_id, ispage:is_page, ajax:ajax, src_img_url: src_img_url, avatar: src_img_url};
		$.post('/upload_photo_profile/'+page_id, data, function(back) {
		//$.post('/update_page_avatar/'+page_id, data, function(back) {
			//$(window.parent).closest('#gis_view').dialog('close');
			
			//var preview = $('#imgupload_preview #preview', window.parent.document);
			//var thumb = $('#imgupload_thumb_pane', window.parent.document);
			var preview = $($(window.parent.document).find('#imgupload_preview #preview')[0]);
			var thumb = $($(window.parent.document).find('#imgupload_thumb_pane')[0]);
			var img_options = $($(window.parent.document).find('#imgupload_options')[0]);
			var save_btn = $($(window.parent.document).find('#imgupload_options #save_image_page')[0]);
			console.log(preview);
			preview.show();
			
			var iframeContents = $.trim(back);
			dataobj = $.parseJSON(iframeContents);
			console.log(dataobj);
			if (dataobj.success === true) {
				var img_url = dataobj.thumb;
				var img = new Image();
				img.onload = iup.get_img_size;
				img.src = img_url;
				console.log(img_url);
				d = new Date();
				preview.find('img').attr('src',img_url+'?'+d.getTime());
				img_options.find('#profile_pic input[name=src_img]').val(img_url);
				img_options.find('#profile_pic input[name=avatar]').val(img_url);
				//img_options.find('#save_preview').show('fade');
				save_btn.show();
				console.log('trying to bring up save button');
				//img_options.show();
				
				//console.log($('#gis_view', window.parent.document));
				//$('#gis_view', window.parent.document).dialog('close').dialog('destroy');
				//console.log($(parent.document).find('#gis_view'));
				//$(parent.document).find('#gis_view').dialog('close');
				//$(window.parent.document).find('#gis_view').dialog('close');
				
				//This logic compensates for buggy jquery dialog close/destroy
				//$(parent.document).find('#gis_view').parent().hide().nextAll('.ui-widget-overlay:last').hide().remove();
				//var par = $(parent.document).find('#gis_view').parent();
				//$(parent.document).find('#gis_view').appendTo($(parent.document).find('body'));
				//par.remove();
				
				//console.log($(parent.document).find('#gis_view').parent());
				//$(parent.document).find('#gis_view').parent().find('.ui-dialog-titlebar-close').trigger('click');
				//console.log($(parent.document).find('#gis_view').parent().find('.ui-dialog-titlebar-close'));
				if ($('#search_block .back').attr('href').indexOf('?') > -1) {
					$('#search_block .back').attr('href', $('#search_block .back').attr('href').split('?')[0]); 
				}
				$('#search_block .back').attr('href',$('#search_block .back').attr('href')+'?imgurl='+img_url);
				$('#search_block .back').click();
				
				//$(parent.document).find('#gis_view').parent().remove();
				//$(parent.document).find('#gis_view').parent().hide().nextAll('.ui-widget-overlay:first').hide().remove();
				//$(parent.document).find('#gis_view').parent().remove();
								
				
				//$('#imgupload_newimg_pane').hide();
				//d = new Date();
				//preview.attr('src',img_url+'?'+d.getTime());
				
				//thumb.find('#thumb_form #src_img').val(iframeContents);
				//preview.show();
			}
			
		});
    });*/
		
});