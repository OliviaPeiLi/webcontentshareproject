<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/photos/albums.php ) --> ' . "\n";
} ?>
<!--<div class="container_24">-->
<? $this->lang->load('photos/photos_views', LANGUAGE); ?>
        <script type="text/javascript" src="<?=base_url()?>js/jquery.transform-0.9.3.min.js"></script>
	<div class="clear"></div>
	<?
	if ($profile_id === $this->session->userdata['id']) {
		$usertext = $this->lang->line('photos_views_my_lexicon');
	} else {
		$usertext = $my_data['first_name'].' '.$my_data['last_name'].'\'s';
	}
	?>
<? if(!$page_id) { ?>	
	<div class="album_top">
    	<h2><?=$usertext?> <?=$this->lang->line('photos_views_photo_albums_heading');?></h2>
    </div>

	<div class="album_container">
	<? } ?>
		<div id="albums">
			<?
	        //print_r($albums);
			foreach ($albums as $key => $value) { ?>
	            <?//print_r($value)?>
				<div class="album">
					<div class="album_img_container">
						<a href="/view_photos/<?=$value['album_id']?>/<?=$value['album_id']?>/<?=$this->uri->segment(3)?>/<?=$id?>">
	                        <img src="<?=$value['thumb']?>" width="130px" name="<?=$value['album_id']?>">
						</a>
					</div>
					<div class="album_info">
						<div>
							<a href="/view_photos/<?=$value['album_id']?>/<?=$value['album_id']?>/<?=$this->uri->segment(3)?>/<?=$id?>"><?=$value['album_name']?></a>
						</div>
						<div><?=$value['photo_count']?> <?=$this->lang->line('photos_views_photo_lexicon');?><? if($value['photo_count']>1){echo 's';}?></div>
					</div>
				</div>
			<? } ?>
			<!-- separate logic for My Photos -->
			<!-- TODO: add data to $myphotos_thumb and $myphotos_count -->
			<? if($myphotos_count > 0)
			{?>
				<div class="album">
					<div class="album_img_container rotateImages">
						<a href="/my_photos/">
	                        <img src="<?=$myphotos_thumb?>" width="130px" name="my">
	                    <? /* for($i=0; $i<3; $i++) {
	                        if ($i === 0) { ?>
	                            <img src="<?=$myphotos_thumb[$i]?>" width="150px" name="my" style="z-index: 3">
	                        <? } else { ?>
	                            <img src="<?=$myphotos_thumb[$i]?>" width="150px" name="my">
	                        <? } ?>
	                    <? } */ ?>
						</a>
					</div>
					<div class="album_info">
						<div>
							<a href="/my_photos/"><?=$this->lang->line('photos_views_my_photos_link');?></a>
						</div>
						<div><?=$myphotos_count?> <?=$this->lang->line('photos_views_photo_lexicon');?><? if($myphotos_count>1){echo 's';}?></div>
					</div>
				</div>
			<? }?>
			<div class="clear"></div>
		</div>
	<? if (!$page_id) { ?>
	</div>
	<div class="album_bot"></div>
	<? } ?>
	<div class="clear"></div>
<? /* ?>
    <script type="text/javascript">
		$(function(){
			$('.rotateImages')
							.css({
								'position' : 'relative'
							});
			$('.rotateImages').hover(function(){
                $(this).addClass('noborder');
				var imgs = $(this).find('img').length;
                var $mid = $(this).find('img').not(':last-child, :first-child');
				var $first = $(this).find('img:first-child');
				var $last = $(this).find('img:last-child');

				if(imgs==3) {
					$mid.css({ 'position' : 'absolute', 'z-index'  : '12' });
					$first.css({'z-index'  : '13'});
					$last.css({ 'position' : 'absolute', 'z-index'  : '11' });
					$mid.animate({ rotate: '-=15deg', translateY: '-=30', translateX: '-=23' }, 200);
					$last.animate({ rotate: '+=15deg', translateY: '+=23', translateX: '+=28' }, 200);
				} else if (imgs==2) {
					$first.css({ 'position' : 'absolute', 'z-index'  : '12' });
					$last.css({ 'position' : 'absolute', 'z-index'  : '11' });
					$last.animate({ rotate: '-=15deg', translateY: '-=30', translateX: '-=23' }, 200);
				} else if (imgs==1) {
                    $first.animate({ scaleX: '1.2', scaleY: '1.2'}, 200);
                }

			}, function(){
				var imgs = $(this).find('img').length;
				var $mid = $(this).find('img').not(':last-child, :first-child');
                var $first = $(this).find('img:first-child');
				var $last = $(this).find('img:last-child');
				if(imgs==3) {
					$mid.animate({ rotate: '+=15deg',	translateY: '+=30',	translateX: '+=23' }, 200);
					$last.animate({ rotate: '-=15deg', translateY: '-=23', translateX: '-=28' }, 200);
				} else if (imgs==2) {
					$last.animate({ rotate: '+=15deg', translateY: '+=30', translateX: '+=23' }, 200);
				} else if (imgs==1) {
                    $first.animate({ scaleX: '1', scaleY: '1'}, 200);
                }
                $first.css('z-index', '3');
                $mid.css('z-index','1');
                $last.css('z-index','2');
                $(this).removeClass('noborder');
			});
		});
    </script>
 <? */ ?>
    
<!--</div>--> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/photos/albums.php ) -->' . "\n";
} ?>
