<?php $is_template = $activity->id <= 0?>
<?= $is_template ? '
<script type="template/html" id="js-activity"
		data-id = ".link-popup @data-newsfeed_id"
		data-thumb = ".link-popup @data-thumbnail, .newsfeed_avatar_img @src"
		data-title = ".link-popup @data-description"
		data-user_id_to = ".obj_id"
		data-user_id_from = ".newsfeed_entry_avatar_user .obj_id"
		data-user_from-url = ".activity_user_from @href"
		data-text = ".t_span"
		data-time = ".timestamp small"
		data-url = ".newsfeed_avatar_a @href"
>' : '' ?>
    <li class="profile_newsfeed_entry newsfeed_text_entry newsfeed_entry newsfeed_full <?=$is_template ? " .js-sample" : ""?>" data-id="<?=$activity->id?>">
		<div class="af_iconHolder">	
			<div class="<?=isset($activity->af_type) ? $activity->af_type : ''?> af_icon"></div>
		</div>
		<div class="inlinediv newsfeed_entry_avatar_user link_avatar <?=in_array($activity->type, array('connection','folder_user')) ? 'show_badge user_avatar' : ''?>">
			<? if(isset($activity->_activity->newsfeed_id) || isset($activity->_activity->_comment) || strpos($activity->url, '/drop/') !== false || $is_template ) { ?>
				<? 
						if ($is_template)	{
							$newsfeed_id = -1;
							$newsfeed_url = -1;
						} elseif(isset($activity->_activity->newsfeed_id) && $activity->_activity->newsfeed_id > 0) {
							$newsfeed_id = $activity->_activity->newsfeed_id;
							$newsfeed_url = $activity->_activity->newsfeed->url;
						} elseif(isset($activity->_activity->_comment) && isset($activity->_activity->_comment->newsfeed_id) && $activity->_activity->_comment->newsfeed_id > 0) {
							$newsfeed_id = $activity->_activity->_comment->newsfeed_id;
							$newsfeed_url = $activity->_activity->_comment->newsfeed->url;
    					} else {
							$parts = explode('/', $activity->url);
							$newsfeed_id = $parts[(count($parts) - 1)];
							$newsfeed_url = $parts[(count($parts) - 1)];
						}
					?>
	        	<a href="#preview_popup" rel="popup" class="link-popup" data-newsfeed_url="<?=$newsfeed_url?>" data-newsfeed_id="<?=$newsfeed_id;?>" data-thumbnail="<?=$activity->thumb?>" data-description="<?=strip_tags($activity->title)?>">
	        		<?=Html_helper::img($activity->thumb, array('class'=>"newsfeed_avatar_img drop-preview-img"))?>
	           	</a>
	        <?php } ?>
			<?php if ( !isset($newsfeed_id) || $newsfeed_id == -1 ) : ?>
	        	<a href="<?=$activity->url?>" class="newsfeed_avatar_a"> 
	        		<?=Html_helper::img($activity->thumb, array('class'=>"newsfeed_avatar_img no_newsfeed_id_avatar"))?>
	           	</a>
			<?php endif; ?>
        </div>
		
        <? //Post info such as who posted on whose profile ?>
       	<span id="activity_<?=$activity->id?>" class="post_info inlinediv">
	        <? //Name of user ?>
	        <a class="activity_user_from" href="<?=$activity->user_from->url?>"><?=$activity->user_from->full_name?></a>
			<span class="t_span"><?=$activity->text?></span>
			<div class="timestamp">
				<small><?=Date_Helper::time_ago($activity->time)?></small>
			</div>
       	</span>
        <div class="clear"></div>
    </li>
<?= $is_template ? '</script>' : ''?>