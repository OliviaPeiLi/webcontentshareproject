<div class="js-messages_constrictor messages_constrictor fd-autoscroll" id="thread_messages" data-template="#js-message" data-maxscrolls="-1" data-url="/view_msg/<?=$thread_id;?>"  data-thread_id="<?=$thread_id;?>">
	<?=$this->load->view( 'message/msgs_item', array('message'=>$this->msg_content_model->sample(),"sample"=>true) )?>
	<?php foreach ($messages as $k=>$message) { ?>
		<? $this->load->view('message/msgs_item', array('message'=>$message,"sample"=>false))?>
		<? if ($message->from == $this->user->id) $message->msg_info->mark_read();?>
	<?php } ?>
	<?php  if (count($messages) > 10) : ?>
	<div class="feed_bot">Load more...</div>
	<?php  endif;?>
</div>