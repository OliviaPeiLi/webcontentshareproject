<div class="newsfeed_entry_content">
    <table>
        <tr height="80" valign="top">
            <td width="80"><a href="#"><img src="/images/example1.jpg" border="0" height="80px"></a></td>
            <td width="500">
            <div class="name_container"><? echo $detail['link'].' '.$detail['ptime']?></div>
            <? if($detail['user_id_from']==$this->session->userdata['id'] || $detail['user_id_to']==$this->session->userdata['id'])
            {?>
            <div><a href="/del_post/<? echo $detail['post_id']; ?>/<? echo $newsfeed_id?>/show_more/post/<?=$detail['post_id']?>">Delete</a></div>
            <? }?>
            <div class="text_post"><?=$detail['post']?></div>
            <div class="post_info"><? echo $timespan ?> | comment | props</div>
            <div><a href="/like/<?=$detail['post_id']?>/post/<?=$this->uri->segment(2)?>/<? echo $detail['post_id']; ?>/<?=$newsfeed_id?>/<? if($page_type == 'main'){echo 'profile';}else{echo 'page';}?>/<?=$view_type?>">Like</a></div>
            <div><a href="/del_like/<?=$this->session->userdata['id']?>/post/<?=$newsfeed_id?>/<?=$detail['post_id']?>/<? if($page_type == 'main'){echo 'page';}else{echo 'profile';}?>/<?=$view_type?>/<?=$newsfeed_id?>">Del_like</a></div>
            <? foreach ($detail['likes'] as $like_key=>$like_value)
                { ?>
                <div><a href="#"><? echo $like_value['link']; ?></div>
                <?
                }
                ?>
            
            
            <? foreach ($detail['comments'] as $ck=>$cv)
                { ?>
                  
            <div id="comment">
                <div><a href="#"><img src="/images/example.jpg" border="0" height="40px"></a></div>
                
                <div>
                <span class="user_name">
                <? echo $cv['link'];?>
                </span>
                <? echo $cv['ctime']; ?>
                </div>
                <? if($cv['user_id_from']==$this->session->userdata['id'] || $cv['user_id_to']==$this->session->userdata['id'])
                {?>
                <div><a href="/del_comm/<? echo $cv['comment_id']; ?>/<? echo $newsfeed_id?>/show_more/post/<?=$this->uri->segment(3)?>">Delete</a></div>
                <? }?>
                <div><?=$cv['comment']?><input type='button' id='aaa' class="reply_button" onclick="setReply('<?=$detail['post_id']?>', 'post', '<?if($cv['page_id_from']!=0){echo $cv['page_id_from'];}else{echo $cv['user_id_from'];}?>', '<?if($cv['page_id_from']!=0){echo 'page';}else{echo 'profile';}?>')" value = 'reply'></div>
                <div class="post_info"><? echo $timespan ?> | props</div>
                <div><a href="/like/<?=$cv['comment_id']?>/post_comm/<?=$this->uri->segment(2)?>/<? echo $detail['post_id']; ?>/<?=$newsfeed_id?>/<? if($page_type == 'profile'){echo 'page';}else{echo 'page';}?>/<?=$view_type?>">Like</a></div>
                <div><a href="/del_like/<?=$this->session->userdata['id']?>/post_comm/<?=$newsfeed_id?>/<?=$cv['comment_id']?>/<? if($page_type == 'main'){echo 'profile';}else{echo 'page';}?>/<?=$view_type?>/<?=$newsfeed_id?>">Del_like</a></div>
                <? foreach ($cv['likes'] as $l_key=>$l_value)
                { ?>
                <div><a href="#"><? echo $l_value['link']; ?></div>
                <?
                }
                ?>
            </div>
            <?
            } ?>
            
            <div class="newsfeed_entry_add_comment" style="display: block;">
                <?php echo validation_errors(); ?>
                <? echo form_open('comment'); ?>
                <?php if($page_type == 'main') {
                                                echo form_hidden('to', $this->session->userdata['id']);}
                      if($page_type == 'page') {
                                                echo form_hidden('to', $this->session->userdata['page_id']);}?>
                    <b id="reply_post_<?=$detail['post_id']?>"> </b>
                    <b id="reply_type_post_<?=$detail['post_id']?>"> </b> 
                <?php echo form_hidden('page_type', $page_type); ?>
                <?php echo form_hidden('view_type', $view_type); ?>
                <?php echo form_hidden('comm_type', 'post_comm'); ?>
                <?php echo form_hidden('post_id', $detail['post_id']); ?>
                <?php echo form_hidden('newsfeed_id', $newsfeed_id); ?>
                <? echo Form_Helper::form_input('comm_msg', set_value('comm_msg', 'Comm_msg'), 'class="reply_comm"');?>
                <? echo form_submit('submit', 'Post');
                   echo form_close();?>
            </div>
                   
                   
                   
            </td>
            <td width="80"><img src="/images/example3.jpg" border="0" height="80px"></td>
        </tr>
    </table>
</div>