<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/chat/main_page.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('chat/chat_views', LANGUAGE); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$this->lang->line('chat_main_page_title');?></title>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url() . 'js/modules/chat/';?>functions.js"></script>
	<script type="text/javascript" src="<?php echo base_url() . 'js/modules/chat/';?>chat.js"></script>

<script type="text/javascript">

// for using in_array() function
Array.prototype.in_array = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return true;
		}
	}
	return false;
}

// for trim a string
String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}


// set global variables
var base_url = "<?php echo base_url();?>index.php/";
var base_url_woi = "<?php echo base_url();?>";
var chatbox_show = 1;
var buddy_show = 0;

// get all buddy at the first time (is reset as '1' if refresh the page)
var get_buddy_all=1;


var scrollend = 1;
var datalength = 0;
var current_friend = 0; // = <?php echo "$friendid"; ?>;
var friendid;			 // = <?php echo "$friendid"; ?>;
var typing=0;			 // = <?php echo "$friendid"; ?>;


// session restore
var messages=[];
var myname = "<?php echo $myname;?>";
var selfonline = <?php echo $selfonline;?>;
var soundmute = <?php echo $soundmute;?>;
var friendlist = new Array();
var friendlist_name = new Array();

<?php
	if ($havefriend == 1) {
		$maxreachcnt = 5;
		$i = 0;
		foreach ($friendlistid as $f) {
			 if ($f <> 0 && $i < $maxreachcnt) {
			   echo "friendlist[$i] = " . $f . ";";
			   $i++;
			 }
		}
	
		$i = 0;
		foreach ($friendlist_name as $f) {
			 if (strcmp($f, 'Unknown') <> 0 && $i < $maxreachcnt) {
			 echo "friendlist_name[$i] = '$f';";
			 $i++;
			 }
		}
		echo 'if (friendlist.length > 0 && friendlist[0] != 0) { buddy_select(friendlist[0]); }';

	}
?>

var online_id;
var online_name;
var online_unread;

var offline_id;
var offline_name;
var offline_unread;

</script>


  	<link rel="stylesheet" href="<? echo base_url() . 'chatui/chatui.css';?>" type="text/css" media="screen" />
  	<link rel="stylesheet" href="<? echo base_url() . 'chatui/general.css';?>" type="text/css" media="screen" />

</head>

<body>

<h1>Welcome to <?php echo "$user"; ?> ! </h1>
<div> <a href="logout"><?=$this->lang->line('chat_logout_btn');?></a> </div>


<div id="example">
</div>

<!--
<div id="testsound">
	<object type="application/x-shockwave-flash" data="http://localhost/webchat/chatui/sound/player_mp3_mini.swf" width="0" height="0">
		<param name="movie" value="http://localhost/webchat/chatui/sound/player_mp3_mini.swf" />
		<param name="bgcolor" value="000000" />
		<param name="FlashVars" value="mp3=http://localhost/webchat/chatui/sound/message_receive.mp3&amp;autoplay=1" />
	</object>
</div>
-->


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

</body>
</html> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/chat/main_page.php ) -->' . "\n";
} ?>
