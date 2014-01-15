<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/chat/chat.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('chat/chat_views', LANGUAGE); ?>
<div id="nav">
	<div id="nav_title">
		<img id="online_toggle" src="<?php echo base_url();?>/chatui/images/monline.jpg">
		<img id="sound_toggle" src="<?php echo base_url();?>/chatui/images/msoundon.png">
	</div>
	<div id="nav_content"></div>
</div>

<!--<div id="nav2" style="position:fixed;bottom:30px;right:100px;background-color:lightblue;"><a name="top"></a><a href="javascript:void(0);" onclick="myHeight.toggle();">Chat</a></div>
-->
<div id="nav2" style="position:fixed;bottom:20px;right:100px;background-color:lightblue;">
	<button class="chatbutton" id="chatbutton" onclick="window.show_buddy_toggle();"><?=$this->lang->line('chat_chat_btn');?></button>
	<div id="sound" > </div>
</div>


<div id="friend" style="position:fixed;bottom:30px;right:200px;">
	<div id="friendbox">
		<div class="chatboxlayout" id="chatboxlayout">
		<!--
			<div class="chatboxtitle">
				<label id="current_friend_name">
					<label id="typing">(idle)</label>
				</label>
			</div>
			<div class="chatbox" id="chatbox"> </div>
			<div class="messagebox">
				<input id="message" type="text" size="40" length="100">
			</div>
		-->
		</div>

		<div class="active_friend_layout" id="active_friend_layout"> </div>
	</div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/chat/chat.php ) -->' . "\n";
} ?>
