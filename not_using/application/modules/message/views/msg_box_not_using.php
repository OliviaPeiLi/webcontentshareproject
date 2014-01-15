<?
	$this->load->helper('user_helper');
	$this->load->helper('time_ago_helper');
?>

<? if ($stage === 'msg') { ?> 
	<?
	$value = $message_thread[0];
	$unread = ($value['display_status'] == 0) ? 'unread' : 'read';
	$full_name = $value['first_name'].' '.$value['last_name'];
	$msg_type = ($value['from'] === $this->session->userdata('id')) ? 'msg_from_you' : 'msg_from_others';
	?>
	<div class="msg_thread_entry msg_entry">
		<!--<div class="inbox_msg_line <?=$unread;?> <?=$msg_type?>">-->
		<div class="inbox_msg_line <?=$msg_type?>" style="position: relative;">
			<div class="avatar inlinediv">
				<img src="<?= get_avatar_img($value['from']); ?>" ></img>
			</div>
			<div class="sender_info inlinediv">
				<div class="sender_name">
					<a href="/profile/<?=$value['uri_name']?>/<?=$value['from'];?>">
					<? echo $full_name; ?></a>
				</div>
				<div class="clear"></div>
				<div class="timestamp_1"><? echo convert_datetime($value['time']) ?></div>
			</div>
			<div class="body inlinediv">
				<p><? echo $value['msg_body']; ?></p>
			</div>
			<div class="delete_float">
				<? if($value['from'] == $this->session->userdata['id']) 
				{?>
				<a href="/del_msg/<?=$value['msg_id']?>/outbox/<? echo $value['from']; ?>/<?=$thread_id?>" ></a>
				<? }else{ ?>
				<a href="/del_msg/<?=$value['msg_id']?>/inbox/<? echo $value['from']; ?>/<?=$thread_id?>" ></a> 
				<? } ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<? } else if ($stage === 'msg_inbox') { ?> 
	<?
	$value = $message_thread[0];
	$unread = ($value['display_status'] == 0) ? 'unread' : 'read';
	$full_name = $value['first_name'].' '.$value['last_name'];
	$msg_type = ($value['from'] === $this->session->userdata('id')) ? 'msg_from_you' : 'msg_from_others';
	?>
	<div class="msg_entry">
		<div class="inbox_msg_line <?=$unread;?> msg_entry" style="position:relative;">
			<div class="avatar inlinediv">
				<img src="<?= get_avatar_img($value['from']); ?>"></img>
			</div>
			<div class="name_body inlinediv">
				<div>
					<div class="sender_name">
						<? //The most recent poster comes first ?>
						Conversation between you and 
						<? //print_r($reveiver);
						foreach($receiver as $k => $v) {
								echo '<a href="/profile/'.$v['uri_name'].'/'.$v['user_id'].'">'.$v['first_name'].' '.$v['last_name'].'</a>';
								if ($k < count($receiver)-1) {
									echo ', ';
								}
						}
						?>
					</div>
					<div class="timestamp inlinediv"><? echo strftime("%b %d, %Y",strtotime($value['time'])); ?></div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="body"><a href="/view_msg/<?=$value['thread_id'];?>"><p><?=$value['msg_body']; ?></p></a></div>
			</div>
			<? /* //for deleting entire threads ?>
			<div class="delete_float inlinediv">
				<a href="/del_msg/<?=$value['msg_id']?>/inbox/<? echo $value['from']; ?>/"></a>
			</div>
			<? */ ?>
			<div class="clear"></div>
		</div>
	</div>	
<? } else { ?>
	<div id="main">
		<?
		if ($display == 'message_thread') {
			$profile_url_you = anchor('profile/'.$this->session->userdata['uri_name'].'/'.$this->session->userdata('id'), 'you');
			//$profile_others = '';
			foreach($full_name as $k=>$v)
			{
				$profile_url_other[$k] = anchor('profile/'.$v['uri_name'].'/'.$v['id'], $v['name']);
			}
			$title = 'Conversation';
			$title_info = 'between '
			.$profile_url_you
			.' and ';
			$num_people = count($profile_url_other);
			if ($num_people > 3) {
				$title_info .= '<a id="num_people">'.$num_people.' other people</a>';
			}
			else {
				foreach($profile_url_other as $k=>$v)
				{
					$title_info .= $v;
					if ($k < $num_people) { $title_info .= ', '; };
				}
			}
			?>
			<!--~~~~~~~~~~~ User info upon hover ~~~~~~~~~~~~~~~~~-->
			<div id="thread_hover" style="display:none" tabindex=100>
				<!--<img src="/images/tabArrow.png" />-->
				<div class="hover_box_up"><div class="hover_box_up_inset"></div></div>
				<div id="thread_container">
					<? 
					//print_r($full_name);
					foreach($full_name as $k=>$v) {
						echo '<div class="person">'.$v['name'].'</div>';
					}
					?>
				</div>
			</div>
		<?	
		}
		else if ($display === 'inbox')
		{
			$title = 'Inbox';
		}
		else if ($display === 'outbox')
		{
			$title = 'Outbox';
		}
		?>
	
		<div class="messages_top">
			<h2><?=$title;?></h2>
			<? if (!empty($title_info)) { ?>
				<div class="title_info"><?=$title_info?></div>
			<? } ?>
		</div>
		<div class="messages_container">
			<div id="msg_tabs">
				<ul>
					<? if ($display !== 'inbox') { ?>
						<li><a href="/message/">Inbox</a></li> 
					<? } ?>
					<? /* ?>
					<li <? if($display === 'outbox'){echo 'class="active_tab"';}?>><a href="/outbox">Sent</a></li>
					<? */ ?>
					<? if ($display === 'inbox') { ?>
						<li><a id="new_message" href="/new_msg">New Message</a></li>
					<? } ?>
				</ul>
			</div>
			<div class="clear"></div>
	
		
			<? 
			if($display == 'inbox') { ?>
				<? //print_r($inbox); ?>
				<div id="inbox">
					<? 
					foreach ($inbox as $key => $value) {
						$unread = ($value['display_status'] === 0) ? 'unread' : 'read';
						?>
						<div class="inbox_msg_line <?=$unread;?> msg_entry" style="position:relative;">
							<div class="avatar inlinediv">
								<img src="<?= get_avatar_img($value['from']); ?>"></img>
							</div>
							<div class="name_body inlinediv">
								<div>
									<div class="sender_name">
										<? //The most recent poster comes first ?>
										Conversation between you and 
										
										<? /* ?>
										<? if($value['from'] != $this->session->userdata('id')){ ?>
											<a href="/profile/<?=$value['uri_name']?>/<?=$value['from'];?>">
											<? echo $value['first_name'].' '.$value['last_name']; ?></a>
											<? //followed by other participants ?>
											<?
											if (count($value['users']) > 0) {
												echo ', ';
											}
										}
										<? */ ?>
										<?
										foreach($value['users'] as $k => $v) {
											if ($v['user_id'] !== $this->session->userdata('id')) {
												echo '<a href="/profile/'.$v['uri_name'].'/'.$v['user_id'].'">'.$v['first_name'].' '.$v['last_name'].'</a>';
												if ($k < count($value['users'])-1) {
													echo ', ';
												}
											}
										}
										?>
										<? /* ?>
										<a href="/profile/<?=$value['uri_name']?>/<?=$value['from'];?>">
										<? echo $value['first_name'].' '.$value['last_name']; ?></a>
										<? */ ?>
									</div>
									<div class="timestamp inlinediv"><? echo strftime("%b %d, %Y",strtotime($value['time'])); ?></div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
								<div class="body"><a href="/view_msg/<?=$value['thread_id'];?>"><p><?=$value['msg_body']; ?></p></a></div>
							</div>
							<? /* //for deleting entire threads ?>
							<div class="delete_float inlinediv">
								<a href="/del_msg/<?=$value['msg_id']?>/inbox/<? echo $value['from']; ?>/"></a>
							</div>
							<? */ ?>
							<div class="clear"></div>
						</div>
					<? }?>
				</div>
				<div class="clear"></div>
			
			<? 
			} else if($display == 'outbox') { ?>
				<div id="outbox">
					<? 
					foreach ($outbox as $key => $value) {
						$unread = ($value['display_status'] == 0) ? 'unread' : 'read';
						?>
						<div class="inbox_msg_line <?=$unread;?> msg_entry" style="position:relative;">
							<div class="avatar inlinediv">
								<img src="<?= get_avatar_img($value['to']); ?>"></img>
							</div>
							<div class="name_body inlinediv">
								<div>
									<div class="sender_name">
										<a href="/profile/<?=$value['uri_name']?>/<?=$value['to'];?>">
											<? echo $value['first_name'].' '.$value['last_name']; ?>
										</a>
									</div>
									<div class="timestamp inlinediv"><? echo strftime("%b %d, %Y",strtotime($value['time'])); ?></div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
								<div class="body"><? echo $value['msg_body']; ?></div>
								<div class="clear"></div>
							</div>
							<? /* //for deleting entire threads ?>
							<div class="delete inlinediv">
								<a href="/del_msg/<?=$value['msg_id']?>/inbox/<? echo $value['from']; ?>/"></a>
							</div>
							<? */ ?>
							
						</div>
						<div class="clear"></div>
					<? } ?>
				</div>
		
			<? } else if($display == 'message_thread') { ?>
				<? 
				foreach ($message_thread as $key => $value) {
					$unread = ($value['display_status'] == 0) ? 'unread' : 'read';
					$full_name = $value['first_name'].' '.$value['last_name'];
					$msg_type = ($value['from'] === $this->session->userdata('id')) ? 'msg_from_you' : 'msg_from_others';
					?>
					<div class="msg_thread_entry msg_entry">
						<!--<div class="inbox_msg_line <?=$unread;?> <?=$msg_type?>">-->
						<div class="inbox_msg_line <?=$msg_type?>" style="position: relative;">
							<div class="avatar inlinediv">
								<img src="<?= get_avatar_img($value['from']); ?>" ></img>
							</div>
							<div class="sender_info inlinediv">
								<div class="sender_name">
									<a href="/profile/<?=$value['uri_name']?>/<?=$value['from'];?>">
									<? echo $full_name; ?></a>
								</div>
								<div class="clear"></div>
								<div class="timestamp_1"><? echo convert_datetime($value['time']) ?></div>
							</div>
							<div class="body inlinediv">
								<? $string = nl2br_except_pre($value['msg_body']); ?>
								<p><? echo $string; ?></p>
							</div>
							<div class="delete_float">
								<? if($value['from'] == $this->session->userdata['id']) 
								{?>
								<a href="/del_msg/<?=$value['msg_id']?>/outbox/<? echo $value['from']; ?>/<?=$this->uri->segment(2)?>" ></a>
								<? }else{ ?>
								<a href="/del_msg/<?=$value['msg_id']?>/inbox/<? echo $value['from']; ?>/<?=$this->uri->segment(2)?>" ></a> 
								<? }?>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				<? }?>	
				<!--~~~~~~~~~~~ Reply to Thread ~~~~~~~~~~~~-->
				<div class="clear"></div>
				<div id="private_msg_reply">
					<div id="status"></div>
					<?
					echo validation_errors();
					echo form_open('send_msg');
					echo form_submit('submit', 'Post', 'style="float:right" class="blue_bg"');
					echo form_hidden('from', $this->session->userdata('id')); 
					echo form_hidden('to', $to_user_id); 
					echo '<textarea name="private_msg_body" id="private_msg_body" style="float:right; height:auto" rows="3" wrap="soft"></textarea>';
					?>
					<?
					echo form_close();
					?>
				</div>
				<div class="clear"></div>
			<? } //end of display if statement ?>
		</div>
		<div class="messages_bot"></div>
	</div>


	<!--~~~~~~~~~~~~~~ Message Form ~~~~~~~~~~~~~~~-->
	<div class="clear"></div>
	<div id="private_msg_form" style="display: none;" class>
		<div>
			<?php echo validation_errors(); ?>
		</div>
		
		<?php echo form_open('send_msg'); ?>
		<ul>
			<li class="autocomplete_input">
			<!--<li>-->
				<div class="text_align_right form_label inlinediv">
					To:
				</div>
				<!--<div class="autocomplete_input">-->
				<? 
					if(isset($msg_name)) { ?>
						<div id="msg_recipient" class="text_align_left form_field inlinediv">
							<?=$msg_name;?>
							<?php echo form_hidden('receiver', $msg_reply_id); ?>
							<? //echo Form_Helper::form_input('receiver', set_value('receiver', 'Receiver'), 'class="input_placeholder" size="50"');?>
						</div>
					<? } else {
						if (!$friends && empty($friends)) {
							$friends = '\'/ac_get_connections'.$profile_id.'\'';
						}
						//print_r($friends);
						?>
						<div class="text_align_left form_field inlinediv">
							<? echo Form_Helper::form_input('receiver', '', 'id="private_msg_name" class="tokenInput" data-url="'.str_replace('"',"'",$friends).'" theme="google" linkedText="+ Add people" prevent_duplicates="true" style="width:400px;"');?>
						</div>
					<? } ?>	
				<!--</div>-->
			</li>
			<li>
				<div class="text_align_right form_label inlinediv">
					Message:
				</div>
				<div class="text_align_left form_field inlinediv">
					<textarea id="msg_body" <?=$msg_class?> name="msg_body" rows="5" style="width:400px; height: 70px;"><?=$msg_body?></textarea>
				</div>
			</li>
			<li>
				<div class="text_align_right form_label inlinediv"> </div>
				<div class="text_align_left form_field inlinediv"> 
					<? 
					echo form_submit('submit', 'Send', 'id="send_msg_btn" class="blue_bg"');
					echo form_close();
					?>
				</div>
			</li>
		</ul>	
	</div>
	<script type="text/javascript">
		$('#inbox .inbox_msg_line').live('click', function() {
			window.location.href = $(this).find('.body a').attr('href');
		});
		$('#new_message').live('click', function() {
			if ($(this).attr('reply') != null) {
	            var msg_recepient = $(this).closest('inbox_msg_line').find('.sender_name a').last().text();
	            //alert(msg_recipient);
	            $('#private_msg_form').find('#msg_recipient').text(msg_recipient);
	            // to pass msg id, just use $(this).attr('reply')
	        }
	        $('#private_msg_form').dialog(
	        	{	modal: true, 
	        		width: 700, 
	        		height: 250, 
	        		draggable: false,
				resizable: false,
	        		title: 'New Message',
	        		close: function() {
	        			$('.token-input-dropdown-google').hide();
	        		}
	        	});
			$('#private_msg_form').dialog( "option", "zIndex", 700 );
			//ui_widget_overlay_fix();
			return false;
		});
		
		//Logic for sending New Private Message
		$('#send_msg_btn').live('click', function() {	
			var post_form = $(this).closest('form');
			var submit_url = post_form.attr('action');		
			var post_data = {
				receiver: post_form.find('input[name=receiver]').val(),
				msg_body: post_form.find('textarea[name=msg_body]').val(),
				ci_csrf_token: $("input[name=ci_csrf_token]").val()
			};
			
			$.ajax({
				url: submit_url,
				type: 'POST',
				data: post_data,
				success: function(msg) {
					msg = $(msg);
					$('#private_msg_form').dialog('close');
					$('#inbox').prepend(msg);
				}
			});
			return false;
		});
		<? /* ?>
		<?
		if (!$friends && empty($friends)) {
			$friends = '\'/ac_get_connections'.$profile_id.'\'';
		}
		?>
		$('.autocomplete_input #private_msg_name').tokenInput(<?=$friends;?>, {
			theme: "facebook",
			preventDuplicates: true 
		});
		<? */ ?>
		$('#private_msg_reply input[type=submit]').live('click', function() {
			var form = $(this).closest('form');
			var textbox = form.find('#private_msg_body');
			var last_msg = $('.msg_entry:last');
			var msg_from = $('#private_msg_reply').find('input[name="from"]').val();
			var msg_to = $('#private_msg_reply').find('input[name="to"]').val();
			var msg_body = $('#private_msg_reply').find("#private_msg_body").val();
			var  msg_data = {
				receiver:	 	msg_to,
				msg_body:	msg_body,
				ci_csrf_token: $("input[name=ci_csrf_token]").val(),
				ajax: 1,
				type: 'thread'
			};
			$.ajax({
				url: form.attr('action'),
				type: 'POST',
				data: msg_data,
				success: function(msg) {
					textbox.val('').text('');
					msg = $(msg);
					msg.hide();
					//$('#private_msg_reply #status').text('Message Sent. Please refresh page to see your message in this thread');
					//window.location.reload();
					last_msg.after(msg);
					msg.show('fade');
				}
			});
			return false;
		});
		
		$('.delete_float').live('click', function() {
			var link = $(this).find('a').attr('href');
			var msg_entry = $(this).closest('.msg_entry');
			$.ajax({
				url: link,
				type: 'GET',
				success: function(msg) {
					msg_entry.hide('fade').remove();
				}
			});
			return false;
		});
	
		
		$('#num_people').hover(function() {
			var info_div = $('#thread_hover');
			var pos = $(this).position();
			var height = info_div.height();
			info_div.css('top', pos.top+22)
						.css('left', pos.left-28)
						.show('fade',300);
			setTimeout(1000);
			//alert('hey');
		}, function() {
			$('#thread_hover').hide();
			//alert('hey2');
		});
		$('#private_msg_body').val('').text('');
		
	</script>
<? } ?>