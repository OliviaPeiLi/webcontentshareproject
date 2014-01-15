<? $this->lang->load('message/message', LANGUAGE) ?>
<? $this->load->view('message/new_message'); ?>

	<div id="main_message_container">
			<!--~~~~~~~~~~~ User info upon hover ~~~~~~~~~~~~~~~~~-->
			<div id="thread_hover" style="display:none" tabindex=100>
				<div class="hover_box_up"><div class="hover_box_up_inset"></div></div>
				<div id="thread_container">
					<?  foreach($thread->users as $user) { ?>
						<div class="person"><?=$user->full_name?></div>
					<? } ?>
				</div>
			</div>

		<div class="messages_top primary_title">
			<h1 class="inlinediv"><?=$this->lang->line('message_msg_inbox_title');?></h1>
			<a id="new_message" class="" href="#private_msg_form" rel="popup" title="<?=$this->lang->line('message_new_message_btn');?>">
				<span class="plusIcon"></span>
				<?=$this->lang->line('message_new_message_btn');?>
			</a>
			<a id="go_back_to_inbox" class="btn-grey" href="/messages/">
				<span class="backIcon"></span>
				<span class="backText">Inbox</span>
			</a>
		</div>

		<div class="js-messages_container messages_container">
			<div class="clear"></div>
			<div id="conversation_title">
				<h2><?=$this->lang->line('message_conversation_lexicon')?></h2>
				<div class="title_info">
					<?=sprintf($this->lang->line('message_title_info_text'), Html_helper::anchor($this->user->url, 'you'))?>
					<? if (count($thread->users) > 3) { ?>
						<a id="num_people"><?=sprintf($this->lang->line('message_num_other_people_text'), count($thread->users))?></a>
					<? } else { ?>
						<? foreach($thread->users as $k=>$user) { ?>
							<?=Html_helper::anchor($user->uri_name, $user->full_name)?>
							<?= $k < count($thread->users) - 1 ? ', ' : ''?>
						<? } ?>
					<? } ?>
				</div>
			</div>
				
			<?php echo Modules::run('message/message/index', $thread->thread_id)?>
			
			<!--~~~~~~~~~~~ Reply to Thread ~~~~~~~~~~~~-->
			<div class="clear"></div>
			<div id="private_msg_reply">
				<div id="status"></div>
				<?=Form_Helper::open('reply_msg', array('id' => 'msg_thread_message_form', 'rel' => 'ajaxForm', 'data-type' => 'json'), array('thread_id'=>$thread->thread_id))?>
					<div class="error blank_body" style="display:none">The message cannot be blank</div>
					<textarea name="msg_body" id="private_msg_body" data-maxlength="250" style="height:auto" rows="3" wrap="soft"></textarea>
					<div class="textLimit">250</div>
					<input type="submit" name="submit" value="<?=$this->lang->line('message_post_submit_btn')?>" class="blue_bg blue_bg_tall"/>
				<?=Form_Helper::close()?>
			</div>
			<div class="clear"></div>

		</div>
		<div class="messages_bot"></div>
	</div>
		
	<?=Html_helper::requireJS(array("message/msg_thread"))?> 
