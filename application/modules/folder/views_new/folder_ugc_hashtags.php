<? $this->lang->load('folder/folder', LANGUAGE); ?>
<div class="container">
	<div class="row">
		<div class="span24 hrule"></div>
	</div>
	<div class="row">
		<? if(!$cache = $this->cache->get($cache_name)) { 
			ob_start();
			?>
			<?php foreach ($folders as $key=>$folder_hashtag) { ?>
				<div class="hashUnit span8 <? /*?> <?=! ($key % 3) ? 'offset2' : ''?><? */ ?>">
					<a href="<?=strtolower(str_replace('#', '',$folder_hashtag['hashtag']->hashtag_name))?>" class="hashtag">
						<?=str_replace('#', '', $folder_hashtag['hashtag']->hashtag_name)?>
					</a>
					
					<?php $folder = $folder_hashtag['folders'][0]; ?>
					<div class="folder_belowHash">
						<div class="folder first">
							<a href="<?=$folder->folder_url?>" class="imageContainer">
								<img src="<?=@$folder->recent_newsfeeds[0]->img_320?>" data-newsfeed_id="<?=@$folder->recent_newsfeeds[0]->newsfeed_id?>"/>
							</a>
							<div class="info">
								<a href="<?=$folder->folder_url?>" class="infoTitle" ><?=$folder->folder_name?></a>
								<div class="writtenBy"><?=$this->lang->line('folder_written_by_lexicon');?> <a href="/<?=$folder->user->uri_name?>"><?=$folder->user->full_name?></a></div>
							</div>
						</div>
						<div class="row">
						<?php for ($i=1;$i < count($folder_hashtag['folders']);$i++) { $folder = $folder_hashtag['folders'][$i]; ?>
							<div class="folder smallFolder span8">
								<a href="<?=$folder->folder_url?>" class="imageContainer">
									<img src="<?=@$folder->recent_newsfeeds[0]->img_bigsquare?>" data-newsfeed_id="<?=@$folder->recent_newsfeeds[0]->newsfeed_id?>"/>
								</a><div class="inlinediv">
									<a href="<?=$folder->folder_url?>" class="info">
										<?=$folder->folder_name?>
									</a><div class="writtenBy"><?=$this->lang->line('folder_written_by_lexicon');?> <a href="/<?=$folder->user->uri_name?>"><?=$folder->user->full_name?></a></div>
								</div>
							</div>
						<?php } ?>
						</div>
						<div class="seeMore">
							<a href="<?=strtolower(str_replace('#', '',$folder_hashtag['hashtag']->hashtag_name))?>"><?=$this->lang->line('folder_see_all_lexicon');?></a>
						</div>
					</div>
				</div>
			<?php } 
			
			$cache = ob_get_clean();
			$this->cache->save($cache_name, $cache);
		} ?>
		<? print($cache); ?>
	</div>
</div>