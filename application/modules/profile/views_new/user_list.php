<? $this->lang->load('search/search_views', LANGUAGE); ?>
<div id="show_connections">
	<? $this->load->view('user_items', array('users' => array($this->user_model->sample())))?>
	<ul id="following" class="dynamic_container fd-autoscroll" data-url="<?=$url?>" data-template="#user-template">
		<? if(isset($sub_title)) { ?>
			<h6><?=$sub_title?></h6>
		<? } ?>
		
		<?php if ($users) { ?>
			<? $this->load->view('user_items', array('users' => $users))?>
		<?php } ?>

		<?php if ($users) { ?>
			<?php if (count($users) >= $per_page) { ?>
			<li class="feed_bottom">
				<span>
					<a href=""><?=$this->lang->line('search_views_more_entries_btn');?></a>
				</span>
			</li>
			<?php } ?>
		<?php } else { ?>
			<li class="no_results">
				<div id="no_follow">No followers found</div>
			</li>
		<?php } ?>
	</ul>
	<script type="text/javascript">
		var imgs = document.getElementById('following').getElementsByTagName('img');
		for (var i=0;i<imgs.length;i++) {
			imgs[i].onerror = function() {
				this.src = "<?=$this->user_model->behaviors['uploadable']['avatar']['default_image']?>";
			}
		}
	</script>
</div>
<?=Html_helper::requireJS(array("profile/user_list"))?> 