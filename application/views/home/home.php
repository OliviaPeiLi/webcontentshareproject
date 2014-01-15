<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/home/home.php ) --> ' . "\n";
} ?>
<?
$email = $this->user->email;
$id = $this->session->userdata('id');
$error_msg = $this->session->flashdata('link_exsits');
$attributes = array('id' => 'csrf_form');
echo Form_Helper::form_open('page/post_comm', $attributes);
echo form_hidden('dummy', 'dummy');
echo form_close();
$last_timestamp['friends'] = 0;
$last_timestamp['interests'] = 0;
?>
<div class="dummy" style="display: none">
<? echo Form_Helper::form_open('dummy'); ?>
<? echo form_hidden('dummy'); ?>
<? echo form_close(); ?>
</div>

<? //include($include_file); ?>
<?=$this->load->view($include_file,'',true); ?>
 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/home/home.php ) -->' . "\n";
} ?>
