<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/photos/photos.php ) --> ' . "\n";
} ?>
<? $this->lang->load('photos/photos_views', LANGUAGE); ?>
<? //print_r($album); ?>
<? //print_r($photos); ?>
<? if ($ajax !== '1') {
    echo '<div id="container" class="container_24">';
} else {
    echo '<div id="container">';
}
?>
	<div id="photo_album" class="grid_24 alpha omega">
		<div class="album_inside_top">
		    <h2><?=$this->lang->line('photos_views_photos_lexicon');?> <?=$album[0]['album_name']?></h2>
		</div>
		<div id="photos" class="album_inside_container">
			<ul>
				<? foreach ($photos as $key => $value)
				{
					if($value['photo_id'] != $pid)
					{
						if($value['user_id'] != 0)
						{
							$user_type = 'user';
							$uid = $value['user_id'];
						}
						else
						{
							$user_type = 'page';
							$uid = $value['page_id'];
						}
						?>
						<div class="photo" value="<?=$value['photo_id']?>">
							<a target="_blank" href="/show_photo/<?=$value['photo_id'].'/'.$user_type.'/'.$uid.'/'.$value['loop_id'].'/'.$value['album_id'].'/'.$this->uri->segment(1).'/'.$this->session->userdata('id')?>?hideheader=1">
							<div class="photo_container">
                                <? if($album[0]['album_name'] == 'Profile')
                                {?>
                                    <img src='<?=s3_url().$user_type.'s/'.$uid?>/pics/profile/thumbs/<?=$value['photo_name']?>' title='<?=$value['photo_caption']?>'>
                                <?	}
                                else
                                {?>
                                    <img src='<?=s3_url().$user_type.'s/'.$uid?>/pics/<?=$value['album_id']?>/thumbs/<?=$value['photo_name']?>' title='<?=$value['photo_caption']?>'>
                                <?	}
                                ?>
                                <div class="remove_tag" rel="<?=$value['photo_id']?>" style="display:none">X</div>
                            </div>
							</a>
						</div>
				<? }
					$pid = $value['photo_id'];
				}?>
				<div class="clear"></div>
			</ul>
		</div>
		<div class="album_inside_bot"></div>
	</div>
</div>
<script type="text/javascript">
$(function() {
    $('#photos .photo_container').hover(function() {
        $(this).find('.remove_tag').show();
    }, function() {
        $(this).find('.remove_tag').hide();
    });

    $('#photos .photo_container .remove_tag').live('click', function() {
        var entry = $(this).closest('.photo');
        var photo_id = $(this).attr('rel');
        var url = '/del_photo/'+photo_id+'/album';
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                entry.hide('fade').remove();
            }
        });
        return false;
    });
});
</script> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/photos/photos.php ) -->' . "\n";
} ?>
