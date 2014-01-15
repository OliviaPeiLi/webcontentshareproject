

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>

	<script>
		if ( typeof(console) === 'undefined' ) {
			console = {
				log : function () {} 
			};
		};
	</script>

	<script type="text/javascript" src="<?php echo base_url() . 'public/';?>jquery.topzindex.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url() . 'public/';?>prototype.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url() . 'public/';?>functions.js"></script>
	<script type="text/javascript" src="<?php echo base_url() . 'public/';?>circle.js"></script>
	<script type="text/javascript" src="<?php echo base_url() . 'public/';?>script.js"></script>

<!-- For IE (IE9 support shadow) -->
<!--[if lt IE 9]> <link rel="stylesheet" href="<? echo base_url() . 'public/circle_ie.css';?>" type="text/css" media="screen" /> <![endif]-->
<!--[if IE 9]>    <link rel="stylesheet" href="<? echo base_url() . 'public/circle.css';?>" type="text/css" media="screen" /> <![endif]-->

<!-- For broswer not IE -->
<![if !IE]>
<link rel="stylesheet" href="<? echo base_url() . 'public/circle.css';?>" type="text/css" media="screen" />
<![endif]>

  <link rel="stylesheet" href="<? echo base_url() . 'public/friend.css';?>" type="text/css" media="screen" />
  <link rel="stylesheet" href="<? echo base_url() . 'public/tooltip.css';?>" type="text/css" media="screen" />

<script>
	var size_height=30, size_width=30;
	var circle_element = new Array();
	<?php
		foreach ( $circle_element as $i=>$s ) {
				echo "circle_element[$i] = [$s];\n" ;
		}
	?>

	// pre-fetch for caching
	var avatar = new Array();
	var base_url = "<?php echo base_url(); ?>";
	var s3_url = "<?=s3_url()?>";
	<?php
		foreach ( $avatar as $i=>$s ) {
				echo "avatar[$i] = \"$s\";\n" ;
				echo "(new Image(size_height,size_width)).src = avatar[$i];\n";
		}
	?>

	var friendname = new Array();
	<?php
		foreach ( $friendname as $i=>$s ) {
				echo "friendname[$i] = '$s';\n" ;
		}
	?>

	var circlename = new Array();
	<?php
		foreach ( $circlename as $i=>$s ) {
				echo "circlename[$i] = \"$s\";\n" ;
		}
	?>

// location of circle
var circle_newleft = new Array(), circle_newtop = new Array();
circle_newleft[0] = 0  ; circle_newtop[0] = 100;

var circle_position_left = new Array(),
		circle_position_top = new Array();

		circle_position_order = [<?php echo $circle_order;?>];

<?php
		$order = 1;
		foreach ($circlebox as $i=>$c)
		{		
//			echo 'circle_newleft['.$c['cid'].'] = '.$c['left'].'; circle_newtop['.$c['cid'].'] = '.$c['top'].';' . "\n";
			echo 'circle_position_left['.$order.'] = '.$c['left'].'; circle_position_top['.$order.'] = '.$c['top'].';' . "\n";
			$order++;
		}
?>

var friend_newleft = new Array(), friend_newtop = new Array();
friend_newleft[0] = 20 ; friend_newtop[0] = 130;

<?php
	foreach ($friendbox as $i=>$c)
	{
		echo '	friend_newleft['.$i.'] = '.$c['left'].'; friend_newtop['.$i.'] = '.$c['top'].';'."\n";
	}
?>


</script>

<div id="loop_list_instructions">Drag your interests into Lists -></div>

<div id="tc_switch_to_loops" class="button">Switch to Loops</div>
<div id="circle_type" style="display:none">list</div>
<div id="circle_ui_content" class="container_24">

	<? 
	echo form_open();
	echo form_close();
	?>
	
	<!-- friend boxes container -->
	<div class="friendboxes grid_15 alpha" id="friendboxes">
		<div class="newfriendbox" name="0" uid="0" id="newfriendbox" style="left:300px;top:300px;">
			<br />
			<a href="#" onclick="alert('Adding new friend here');">Add new friend</a>
		</div>
	
		<!-- transferred from server -->
		<?php
			foreach ($friendname as $i=>$c)
			{						
				echo '<!-- Friendbox ' . $i . '-->'."\n";
				echo '	<div class="friendbox" name="'.$i.'" uid="'.$i.'" style="left:300px;top:300px;">' . "\n";
				echo '		<div class="friendavatar"><img class="friendavatar" src="'.$avatar[$i].'" /></div>' . "\n";
				echo '		<div class="friendname" name="'.$i.'" rel="'.$c.'"></div>' . "\n";
				echo '	</div>' . "\n";
			}
		?>
	</div>
	
	<!-- circle boxes container -->
	<div class="circleboxes grid_11 omega">
		<div id="balloon"></div>
		<div class="newcirclebox" name="0" id="circlebox_0"	style="top:300px;left:300px;">
			<div class="circlebase circle_boundary" name="0">
				<div class="circlebase circle_title">
	<!--				<a href="#" onclick="create_newcircle(); return false;">Create new Loop</a>   -->
					<a>Create new List</a>
				</div>
				<div class="friendlist"> </div>
			</div>
		</div>
	
		<!-- transferred from server -->
		<?php
			foreach ($circlebox as $i=>$c)
			{		
				echo '<!-- Circle ' . $c['cid'] . '-->'."\n";
				echo '	<div class="circlebox" name="' . $c['cid'] . '" style="top:300px;left:300px;" id="circlebox_' . $c['cid'] . '">' . "\n";
				echo '		<div class="circlebase circle_boundary" name="'.$c['cid'].'">'."\n";
				echo '			<div class="circlebase circle_title"> </div>'."\n";
				echo '			<div class="friendlist"> </div>'."\n";
				echo '		</div>'."\n";
				echo '	</div>'."\n";
			}
		?>
	
		<div id="bar"> </div>
	
	</div>
	<div class="clear"></div>
	
	<!-- for showing introduction box -->
	<div class="username_introduction">
		<a>Welcome to Cat circle</a><br />
		<a>This is a Cat</a><br />
		<a>This is a form, and could changed in future easily</a><br />
	</div>
	
	<!-- for showing username -->
	<div class="username_tooltip"> </div>
</div>
<div class="clear"></div>

<div id="newcircle_form">
	<label id="newcircle_circlenamelabel">List name
		<input type="text" size="15" id="newcircle_circlename">
	</label>
	<div id="newcircle_member">
	</div>
	<div id="newcircle_button">
		<button id="newcircle_creation">Create new List</button>
		<button id="newcircle_cancel">Cancel</button>
	</div>
</div>

<div id="edit_form">
	<label id="edit_circlenamelabel">List name
		<input type="text" size="15" id="edit_circlename">
	</label>
	<div id="edit_circle_button">
		<button id="edit_changename">Change List name</button>
		<button id="edit_cancel">Cancel</button>
	</div>
</div>
<div class="clear"></div>


<script type="text/javascript">
$(function() {
	$('#circle_ui_content').height($(window).height());
});

$('#tc_switch_to_loops').live('click', function() {
	window.location = '<?=base_url()?>/tc_loops';
});

</script>
