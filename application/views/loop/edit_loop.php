<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/loop/edit_loop.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('loop/loop_views', LANGUAGE); ?>
<h2><? $this->lang->line('loop_views_edit_loop_heading'); ?></h2>
    
    <ul>
        <li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
        <li><? echo form_open('/update_loop'); ?></li>
        <? echo form_hidden('loop_id',$loop_id); ?>
        <? if($loop_info['loop_name'] == $this->lang->line('loop_views_loop_main_lexicon'))
        {
        	echo '<li>'.$this->lang->line('loop_views_loop_name_lbl').': '.$this->lang->line('loop_views_loop_main_lexicon').'</li>';
        }else{
        ?>
        	<li><? echo $this->lang->line('loop_views_loop_name_lbl'). Form_Helper::form_input('loop_name', set_value('loop_name', $loop_info['loop_name']));?></li>
        <? } ?>
            <ul>
                <li><?=$this->lang->line('loop_views_choose_conn_lbl');?></li>
                <? foreach($my_connections as $key=>$item)
                {
                ?>
                    <li><? echo form_checkbox('loop_member[]', $item['uid'], $item['check']); ?> <a href="/collections/<?=$item['uri_name']?>/<?=$item['uid']?>"><?=$item['first_name'].' '.$item['last_name']?> </a></li>
                <?
                }
                ?>
                <? foreach($my_follow as $key=>$item)
                {
                ?>
                    <li><? echo form_checkbox('loop_member[]', $item['uid'], $item['check']); ?> <a href="/collections/<?=$item['uri_name']?>/<?=$item['uid']?>"><?=$item['first_name'].' '.$item['last_name']?> </a></li>
                <?
                }
                ?>
            </ul>
        <li><? echo form_submit('submit', $this->lang->line('loop_views_submit_btn'));
        echo form_close();?></li>
    </ul> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/loop/edit_loop.php ) -->' . "\n";
} ?>
