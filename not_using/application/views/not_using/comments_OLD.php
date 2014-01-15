<?
// For this to work, make sure that the following variables are set and are accessible:
//   $type: {'page', 'profile'}
//   $page_info (if $type==='page above)
//   $news_array: the array of the newsfeed that included this file
//   $fvalue: (from foreach loop that included this file)
//   $can_comment: determines whether user is authorized to edit comments
?>
<? 
$curr_user_id = $this->session->userdata('user_id');
if ($this->session->userdata('page_id') !== 0) {
	$curr_user_id = $this->session->userdata('page_id');
}
?>
<div class="newsfeed_entry_comments" rel="<?=count($news_array['comments'])?>"> <!-- contains comments -->
	<? 
	
	//NEW COMMENTING SYSTEM (HIERARCHIAL COMMENTS)
	//echo 'PAGEPAGEPAGE: '.$page_id;
	if ($page_id) {
		
		$this->load->helper('comment_helper');
		//var_dump($this->comment);
		//$comment_instance = new Comment($news_array['comments']);
		if (count($news_array['comments']) > 0) {
			$root = array();
			foreach ($news_array['comments'] as $k => $comment) {
				if ($comment['parent_id'] === '0') {
					$root[] = array('children_id' => $comment['comment_id']);
				}
			}
/* THREADED COMMENTS
 *	Inputs: news_array 		- All comments for the single news post
 *			prev_comments	- All comments that have been touched by this function (to ensure we do not repeat the same comment)
 *			children		- ids of children comments
 *			page_id			- id of the page/interest
 *			fvalue			- Newsfeed data
 *			entity_id			- Id of page/interest
 *			view_type		- Type of view page/profile/home
 *			parent			- used internally (optional)
 *			level			- used internally (optional)
 *			is more			- used internally (optional)
 *			order			- used internally (optional)
 *			nextParentMore	- used internally (optional)
 */
			get_loopCommentEvent($news_array,array(),$root, $page_id, $fvalue, $entity_id, $view_type, $in);
			//testtesttest();
		} ?>

		<script type="text/javascript">
		</script>
	<? 
	} else {
		foreach ($news_array['comments'] as $ck=>$cv)
		{
			$is_from_page_child = ($cv['page_id_from'] !== '0');
			$thumb = $cv['thumbnail'];
			if($thumb == s3_url())
			{
				if ($is_from_page_child) 
				{
					$thumb = s3_url().'pages/default/defaultInterest/'.$cv['category_id'].'.png';
				} 
				else 
				{	
					$thumb = s3_url().'users/default/defaultMale.png';			
				}
			}
	        include('application/views/comment/comment.php');
		} 
	}

	?>
	<? //echo form_close();?>
	<!--~~~~~~~~~~~~~~ Post a comment ~~~~~~~~~~~~~~~~~~~-->
	<? if ($fvalue['type'] === 'photo' || $fvalue['type'] === 'photo_comm' || $fvalue['type'] === 'photo_like' || $fvalue['type'] === 'photo_comm_like') { ?>
		<div class="newsfeed_entry_add_comment" style="display: block;">
			<? echo validation_errors(); ?>

			<? echo form_open('/comment'); ?>
				<? if ($view_type === 'page') {
					echo form_hidden('uri_name', $page_info[0]['uri_name']);
				}
				if ($view_type == 'profile') {
					echo form_hidden('uri_name', $my_data['uri_name']);
				}?>
			
				<b id="reply_photo_<?=$news_array['photo_id']?>"> </b>
				<b id="reply_type_photo_<?=$news_array['photo_id']?>"> </b>
				<b id="to_photo_<?=$news_array['photo_id']?>"> </b>
				<? //TODO: figure out the diference btw $this->uri->segment(1) and 'page' as page_type ?>
				<? if (!$this->uri->segment(1)) {
					$page_type = ($type === 'page') ? 'page' : 'profile'; 
				} else {
					if($this->uri->segment(1) == 'loop' || $this->uri->segment(1) == 'profile')
					{
						$page_type = 'profile';
					}
					else
					{
						$page_type = 'interests';
					}
				} ?>
				
				<? if($news_array['page_id_to']!='0'){ $to = $news_array['page_id_to'];}else{$to = $news_array['user_id_to'];}?>
				
				<? if($this->uri->segment(1) == '')
				{
					echo form_hidden('view_type', 'home'); 
				}?>
				<?php echo form_hidden('to', $to); ?>
				<? echo form_hidden('loop_id',$loop_id); ?>
				<? echo form_hidden('page_type', $page_type); ?>
				<? echo form_hidden('reply_to_type', $type); ?>
				<? echo form_hidden('comm_type', 'photo_comm'); ?>
				<? echo form_hidden('photo_id', $news_array['photo_id']); ?>
				<? echo form_hidden('newsfeed_id', $fvalue['newsfeed_id']); ?>
				<? if($this->session->userdata['id']) { ?>
					<div class="comment_input_container">
						<? echo Form_Helper::form_input('comm_msg', set_value('comm_msg', ''), 'class="reply_comm"');?>
					</div>
					<div class="comment_submit_container">
						<? echo form_submit('submit', 'Comment', 'class="comment_submit blue_bg"'); ?>
					</div>
				<? } ?>
			<? echo form_close();?>
		</div>
	<!-- POST Comments -->
	<? } else if ($fvalue['type'] === 'post' || $fvalue['type'] === 'post_comm' || $fvalue['type'] === 'post_like' || $fvalue['type'] === 'post_comm_like') { ?>
		 <div class="newsfeed_entry_add_comment" style="display: block;">
			<?php echo validation_errors(); ?>
			<?php echo form_open('comment'); ?>
				<?php echo form_hidden('uri_name', $page_info[0]['uri_name']); ?>
				
				<b id="reply_post_<?=$news_array['post_id']?>"> </b>
				<b id="reply_type_post_<?=$news_array['post_id']?>"> </b>
				<b id="to_post_<?=$news_array['post_id']?>"> </b>
				
				<? if (!$this->uri->segment(1)) {
					$page_type = ($type === 'page') ? 'page' : 'profile';
				} else {
					//$page_type = $this->uri->segment(1);
					if($this->uri->segment(1) == 'loop' || $this->uri->segment(1) == 'profile')
					{
						$page_type = 'profile';
					}
					else
					{
						$page_type = 'interests';
					}
				} ?>
				<? if($view_type == 'home')
				{
					echo form_hidden('view_type',$view_type);
				}?>
				<? if($news_array['page_id_to']!=0){ $to = $news_array['page_id_to'];}else{$to = $news_array['user_id_to'];}?>
	
				<?php echo form_hidden('to', $to); ?>
	
				<?php echo form_hidden('page_type', $page_type); ?>
				<? echo form_hidden('loop_id',$loop_id); ?>
				<?php echo form_hidden('comm_type', 'post_comm'); ?>
				<?php echo form_hidden('post_id', $news_array['post_id']); ?>
				<?php echo form_hidden('newsfeed_id', $fvalue['newsfeed_id']); ?>
				<? if($this->session->userdata['id']) { ?>
					<div class="comment_input_container">
						<? echo Form_Helper::form_input('comm_msg', set_value('comm_msg', ''), 'class="reply_comm"');?>
					</div>
					<div class="comment_submit_container">
						<? echo form_submit('submit', 'Comment', 'class="comment_submit blue_bg"'); ?>
					</div>
				<? } ?>

			<? echo form_close();?>
		</div>
	<!-- LINK Comments -->
	<? } else if ($fvalue['type'] === 'link' || $fvalue['type'] === 'link_comm' || $fvalue['type'] === 'link_like' || $fvalue['type'] === 'link_comm_like') { ?>
		 <div class="newsfeed_entry_add_comment" style="display: block;">
			<?php echo validation_errors(); ?>
			<?php echo form_open('comment'); ?>
				<?php echo form_hidden('uri_name', $page_info[0]['uri_name']); ?>
	
				<b id="reply_link_<?=$news_array['link_id']?>"> </b>
				<b id="reply_type_link_<?=$news_array['link_id']?>"> </b>
				<b id="to_link_<?=$news_array['link_id']?>"> </b>
	
				<? if (!$this->uri->segment(1)) {
					$page_type = ($type === 'page') ? 'page' : 'profile';
				} else {
					if($this->uri->segment(1) == 'loop' || $this->uri->segment(1) == 'profile')
					{
						$page_type = 'profile';
					}
					else
					{
						$page_type = 'interests';
					}
				} ?>
				<? if($view_type == 'home')
				{
					echo form_hidden('view_type',$view_type);
				}?>
				<? if($news_array['page_id_to']!=0){ $to = $news_array['page_id_to'];}else{$to = $news_array['user_id_to'];}?>
	
				<?php echo form_hidden('to', $to); ?>
	
				<?php echo form_hidden('page_type', $page_type); ?>
				<? echo form_hidden('loop_id',$loop_id); ?>
				<?php echo form_hidden('comm_type', 'link_comm'); ?>
				<?php echo form_hidden('link_id', $news_array['link_id']); ?>
				<?php echo form_hidden('newsfeed_id', $fvalue['newsfeed_id']); ?>
				<? if($this->session->userdata['id']) { ?>
				<div class="comment_input_container">
					<? echo Form_Helper::form_input('comm_msg', set_value('comm_msg', ''), 'class="reply_comm"');?>
				</div>
				<div class="comment_submit_container">
					<? echo form_submit('submit', 'Comment', 'class="comment_submit blue_bg"'); ?>
				</div>
			<? } ?>
			<?  echo form_close();?>
			    
		</div>
    <? } ?>
</div>
<? //HERE LIES THE MOST TIME CONSUMING </DIV> ?>