<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/list/list_newsfeed.php ) --> ' . "\n";
} ?>
<? //print_r($this->session->userdata('id'));?>
<? //print_r($this->session->userdata('name'));?>
<?  die('TO-DO: remove this file probably its not used');
$email = $this->session->userdata('email');
$id = $this->session->userdata('id');
//print_r($id);
//echo $id.','.$email;?>
<?
	$attributes = array('id' => 'csrf_form');
	echo Form_Helper::form_open('page/post_comm', $attributes);
	echo form_hidden('dummy', 'dummy');
	echo form_close();
	$last_timestamp = 0;
?>
<? //echo $stage; ?>
<? if ($stage === 'home') { ?>
            <? if(count($num_interests) <= 0): ?>
                <div id="no_interests">
                	This story has no interests.&nbsp; Add more interests. 
                	<a href="/manage_lists" id="manage_lists" rel="popup" data-width="95%" class="blue_bg" title="Manage Your Stories">Manage Your Stories</a>
                </div>
            <?php elseif (count($page_array) <= 0): ?>
                There are no news from this story
            <?php else: ?>
                <?php foreach ($page_array as $fkey => $fvalue) {
				    $news_array=unserialize($fvalue['data']);
                    $type = 'interests';
                    $view_type = 'home';
				    //include('application/views/newsfeed/newsfeed_home_interests.php');
				    echo $this->load->view('newsfeed/newsfeed_home_interests','',true);
			    } ?>
            <?php endif; ?>
            <?=requireJS(array("common/badge","list/view_list_newsfeed"))?>
<? } else { ?>
	<div id="main">
		<div class="container_24">
			<div class="grid_14 alpha">
				<? if($page_array) {?>
					<div>
						<div class="newsfeed_title inlinediv">News from Story "<?=$list_info[0]['list_name']?>"</div>
						<div class="inlinediv">
							<? if($check_list_user) { ?>
								Following, <a href="/unfollow_list/<?=$list_info[0]['list_id']?>">Following</a>
							<? } else { ?>
								Not following, <a href="/follow_list/<?=$list_info[0]['list_id']?>">Follow</a>
							<? } ?>
						</div>
					</div>
					<div id="list_newsfeed">
						<? foreach ($page_array as $fkey => $fvalue) {
							$news_array=unserialize($fvalue['data']);
							//include('application/views/newsfeed/newsfeed.php');
							echo $this->load->view('newsfeed/newsfeed','',true);
						} ?>
					</div>
					<a id="ScrollToTop" href="#top" class="Button WhiteButton Indicator" style="display:none"><strong>Scroll to Top</strong><span></span></a>
				<? } ?>
			</div>
		</div>
	</div>
<? } ?>



 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/list/list_newsfeed.php ) -->' . "\n";
} ?>
