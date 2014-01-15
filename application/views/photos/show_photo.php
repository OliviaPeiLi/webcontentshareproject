<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/photos/show_photo.php ) --> ' . "\n";
} ?>
<? $this->lang->load('photos/photos_views', LANGUAGE); ?>
<div class="show_photo_container">
    <div id="show_photo_container">
		<!--<div id="album_title">
			Photos > <?=$album_info[0]['album_name']?> > <?=$photo_info[0][0]['photo_name']?>
		</div>-->

        <div id="image_comments">
            <div id="photo_album_viewer_title"><?=$this->lang->line('photos_views_album_lbl');?>: <?=$album_info[0]['album_name']?></div>
            <button id="tag_items" class="tag_button_item button inlinediv" style="display: none"><?=$this->lang->line('photos_views_tag_photo_lbl');?></button>
            <button id="load_labels" class="inlinediv" style="display: none"><?=$this->lang->line('photos_views_load_labels_lbl');?></button>
            <button id="load_tags" class="inlinediv" style="display: none"><?=$this->lang->line('photos_views_load_tags_lbl');?></button>
            <div id="photo_bottom">
                <?=$this->lang->line('photos_views_loading_comments_msg');?>
            </div>

        </div>
        <? print_r($album_photos); ?>
		<script type="text/javascript">
		<? $album_data = 'var data = ['; ?>
		<? foreach($photos_array as $key => $value) {
			if($value['album_name'] == 'Profile')
			{
				$album_data = $album_data.'{
					image: "'.s3_url().$this->uri->segment(3).'s/'.$this->uri->segment(4).'/pics/profile/'.$value['photo_name'].'",
					thumb: "'.s3_url().$this->uri->segment(3).'s/'.$this->uri->segment(4).'/pics/profile/'.$value['photo_name'].'",
					big: "'.s3_url().$this->uri->segment(3).'s/'.$this->uri->segment(4).'/pics/profile/'.$value['photo_name'].'",
					title: "'.$value['photo_caption'].'",
					description: "'.$value['photo_caption'].'",
					photo_id: "'.$value['photo_id'].'",
					loop_id: "'.$value['loop_id'].'"
				}, ';
			}
			else
			{
				$album_data = $album_data.'{
					image: "'.s3_url().$this->uri->segment(3).'s/'.$this->uri->segment(4).'/pics/'.$value['album_id'].'/'.$value['photo_name'].'",
					thumb: "'.s3_url().$this->uri->segment(3).'s/'.$this->uri->segment(4).'/pics/'.$value['album_id'].'/thumbs/'.$value['photo_name'].'",
					big: "'.s3_url().$this->uri->segment(3).'s/'.$this->uri->segment(4).'/pics/'.$value['album_id'].'/'.$value['photo_name'].'",
					title: "'.$value['photo_caption'].'",
					description: "'.$value['photo_caption'].'",
					photo_id: "'.$value['photo_id'].'",
					loop_id: "'.$value['loop_id'].'"
				}, ';
			}
		}
		$album_data .= '];';
		echo $album_data;
		?>
		//var aaa = <?=$value['album_id']?>;
		//alert(aaa);
		</script>
		<div id="image_container" style="display:none">
			<img src="<?=s3_url().$this->uri->segment(3).'s/'.$this->uri->segment(4).'/pics/'.$this->uri->segment(6).'/'.$photo_info[0][0]['photo_name']?>" id="image1" style="position: relative;">
			<?=$photo_info[0][0]['photo_caption']?>
		</div>
    <div class="content">
	<!--
        <h1>Galleria Classic Theme</h1>
        <p>Demonstrating a basic gallery example.</p>
	-->
		<div style="width: 100%; text-align: right">
		<a class="galleria_closeButton" href="javascript: self.close();" >X</a>       
		</div>
        <div id="galleria">
        </div>
        <!--<p class="cred">Made by <a href="http://galleria.aino.se">Galleria</a>.</p>-->
    </div>
    


		</div>
	<!--</div>-->
</div>

<script type="text/javascript">

    $(function(){
        // custom code
        //Mouse event for photo square tags
        $('#image_container').css('position','absolute');
		$('#galleria').css('height',$(window).height()+'px');
        $('#galleria .galleria-image').height($(window).height()-60);
        $('#tags .tag_label').live('mouseenter',function(e) {
            $('#tags').data('tag_open','1');
            var label_txt = $(this).find('.tag_label_text');
            if (label_txt.length*6 > $(this).width()) {
                label_txt.width(label.txt.length*4);
            }
            label_txt.show();
            var tag_id = $(this).attr('id').split('_')[1];
            //alert($('#photo_bottom').find('#labels .label#tag_'+tag_id).html());
            //$('#photo_bottom').find('#labels .label#tag_'+tag_id).css('border-color: white !important');
            //console.log($('#photo_bottom').find('#labels .label#tag_'+tag_id).attr('class'));
            $('#photo_bottom').find('#labels .label#tag_'+tag_id).css('border-color', 'white')
                        .css('background-color', '#333333')
                        .css('color', 'white');
            e.stopPropagation();
        });
        $('#tags .tag_label').live('mouseleave',function(e) {
            $('#tags').data('tag_open','0');
            $(this).find('.tag_label_text').hide();
            var tag_id = $(this).attr('id').split('_')[1];
            $('#photo_bottom').find('#labels .label#tag_'+tag_id).css('border-color', '#c0c0c0')
                        .css('background-color', 'black')
                        .css('color', '#c0c0c0');
            e.stopPropagation();
        });
        //Mouse event for text labels outside of the photo
        $('#photo_bottom .label a').live('mouseenter', function(e) {
            var tag_id = $(this).parent().attr('id').split('_')[1];
            //alert($(this).attr('id'));
            $('#tags').find('#label_'+tag_id).show();
            e.stopPropagation();
        });
        $('#photo_bottom .label a').live('mouseleave', function(e) {
            var tag_id = $(this).parent().attr('id').split('_')[1];
            $('#tags').find('#label_'+tag_id).hide();
            e.stopPropagation();
        });

        $('#delete_photo').live('click', function() {
            var photo_id = $(this).attr('rel');
            var url = '/del_photo/'+photo_id+'/album';
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    //alert('deleting photo');
                    var gallery = Galleria.get(0);
                    var indx = gallery.getIndex();
                    //alert(indx);
                    gallery.next();
                    gallery.splice(indx,1);
                }
            });
            return false;
        });

    });
    function init_tagger() {
        var gallery = Galleria.get(0);
        var pic = gallery.getActiveImage();
        var orig_img = $('.galleria-image img');
        $('.galleria-image img').each(function(im) {
            if (pic.src === $(this).attr('src')) {
                orig_img = $(this);
            }
        });
        var pic_data = gallery.getData();
        <?
        $get_url = '/get_all_tags/[$LOOPID]/[$IMGID]';
        $post_url = '/post_all_tags/[$IMGID]';
        ?>
        var get_url = "<?=$get_url?>";
        get_url = get_url.replace('[$IMGID]', pic_data.photo_id);
        get_url = get_url.replace('[$LOOPID]', pic_data.loop_id);
        var post_url = "<?=$post_url?>";
        post_url = post_url.replace('[$IMGID]', pic_data.photo_id);

        //This method is needed to unbind all events attached to jpictag elements in previous instance.
        $("#image1").tag_items().destroy();
        //Create a new instance of jpictag

        $("#image1").tag_items().init({
                                'orig_img': orig_img,
                                'pictag_enable_items': '#tag_items',
                                'square_box_size': 50,
                                'resizable': true,
                                'edit_mode': true,
                                'allow_html': true,
                                'auto_save': true,
                                'get_url': get_url,
                                'auto_load':true,
                                'post_url': post_url,
                                'form': '#form1',
                                'remove': '<img src="/images/deleteIcon_g.png">',
                                'tag_by': <?=$this->session->userdata['id']?>,
                                'photo_role': <?=$role?>,
                                'tag_time': Math.round(new Date().getTime()/1000)
                                });
        
    }



	$(window).load(function() {

	});

    function enableTagging(el) {
        var gallery = Galleria.get(0);
        var pic = gallery.getActiveImage();
        var new_width = pic.width;
        var new_height = pic.height;
        var new_left = 0;
        var new_top = 0;
        $('.galleria-image img').each(function(im) {
            if (pic.src === $(this).attr('src')) {
                new_left = $(this).offset().left;
                new_top = $(this).offset().top;
            }
        });
        $('#image_container img').attr('src',pic.src);
        $('#image_container img').css('width',new_width+'px').css('height',new_height+'px').css('top','0px').css('left','0px');
        $('#image_container').css('width',new_width+'px').css('height',new_height+'px');
        $('#image_container').css('left',new_left+'px').css('top',new_top+'px');
        $('#galleria').hide();
        $('#image_container').show();
        $('#tag_items_2').hide();
        el.trigger('click');
    }
    $('#tag_items_2').live('click', function(){
        enableTagging($('#tag_items'));
        return false;
    });

    function load_photo_info() {
        //get photo id and show the appropriate image from album (not the first image in album)
        $('#photo_bottom').html('Loading comments...');
        var gallery = Galleria.get(0);
        var pic = gallery.getData();
        <?
        $info_url = base_url().'get_photo_info/'
                     .'[$IMGID]/'
                     .$this->uri->segment(3).'/'
                     .$this->uri->segment(4).'/'
                     .$this->uri->segment(5).'/'
                     .$this->uri->segment(6).'/'
                     .$this->uri->segment(7).'/'
                     .$this->uri->segment(8).'/';
        ?>
        var info_url = "<?=$info_url?>";
        info_url = info_url.replace('[$IMGID]', pic.photo_id);
		var req = {
			ci_csrf_token: $("input[name=ci_csrf_token]").val()
		};
		$.ajax({
			url: info_url,
			type: 'POST',
			data: req,
			success: function(msg) {
                //console.log(msg);
                $('#photo_bottom').html(msg);
                $('#labels').html('');
                load_tags();
                $('#load_labels').trigger('click');
                $('#tags').css('left', $('#tags').parent().find('img').position().left+'px');
                $('#tags').css('top', $('#tags').parent().find('img').position().top+'px');
                $('#labels').css('top',$(window).height()-60+'px')
			}
		});
        init_tagger();

        //$('#tags').position($('#tags').parent().find('img').position());
    }
    var on_tag = false;
    var timer;

    function show_tags() {
        $('#tags .tag_label').show();
    }
    function hide_tags() {
        setTimeout(function() {
            if ($('#tags').data('tag_open') === '0') {
                $('#tags .tag_label').hide();
            }
        },50);
    }
    function load_tags() {
        var gallery = Galleria.get(0);
        var pic = gallery.getActiveImage();
        $('.galleria-image img').each(function(im) {
            if (pic.src === $(this).attr('src')) {
                $(this).parent().append('<div id="tags"></div>');
            }
        });
    }

    // Load the classic theme
    Galleria.loadTheme('<?=base_url()?>js/plugins/galleria/galleria.classic.js');
    // Initialize Galleria
            var photo_id = "<?=$this->uri->segment(2)?>";
            var index = 0;
            for (img in data) {
                if (data[img]['photo_id'] === photo_id) {
                    index = img;
                }
            }
    $('#galleria').galleria({
        data_source: data,
        initialTransition: 'fade',
        show: index
    });
    $('body').click(function(e) {
        //exitWindow(e);
    });


</script> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/photos/show_photo.php ) -->' . "\n";
} ?>
