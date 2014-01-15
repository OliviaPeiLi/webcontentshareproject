<? $this->lang->load('message/message', LANGUAGE); ?>
<? $this->load->view('message/new_message'); ?>
<div id="main_message_container">
	<div class="messages_top primary_title">
		<h1 class="inlinediv"><?=$this->lang->line('message_msg_inbox_title');?></h1>
		<a id="new_message" class="" href="#private_msg_form" rel="popup" title="<?=$this->lang->line('message_new_message_btn')?>">
			<span class="plusIcon"></span>
			<?=$this->lang->line('message_new_message_btn');?>
		</a>
	</div>
		<?php
			//RR - add a sample item for the ajaxList/autoscroll 
			$this->load->view('message/threads_item', array('thread'=> $this->msg_thread_model->sample() ,"type"=>"thread" ));
		?>	
	<div class="js-messages_container messages_container">
		<div id="inbox">
			<div id="inbox_messages" class="fd-autoscroll" data-template="#js-message" data-maxscrolls="-1" data-url="/messages">
				<?//=$this->load->view('message/threads_item', array('message'=>$this->msg_info_model->sample()))?>
				<? if ($threads) { ?>
					<? foreach ($threads as $thread) { ?>
						<? $this->load->view('message/threads_item', array('thread'=>$thread)) ?>
					<? } ?>
				<?php } ?>
				<div class="feed_bottom" data-test="etest">
					<a><?=$this->lang->line('folder_get_more_news_btn');?></a>
				</div>					
				<div class="js-no_messages no_messages" style="display: <?=$threads ? "none" : "block";?>"><?=$this->lang->line('message_no_messages_msg');?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="messages_bot"></div>
</div>
<?=Html_helper::requireJS(array("message/msg_inbox"))?>
