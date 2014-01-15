    <link rel="stylesheet" href="css/super-sort.css" />
<? /* ?><link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" /> <? */ ?>
<link rel="stylesheet" href="css/loadmask.css" />
<? /* <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
*/?>
<script type="text/javascript" src="js/loadmask.min.js"></script>
<script type="text/javascript" src="js/tc_super-sort.js"></script>
<script type="text/javascript">
user_id = '<?php echo $this->session->userdata('id') ?>';
var super_sort_cached = {};
</script>

<div id="container" class="container_24">
	<div id="main" class="prefix_3 grid_18 suffix_3">
		<div id="profile_edit_interests">
		    <?//print_r($all_interests)?>
		    <?//print_r($share_list_owner)?>
		    <?//print_r($share_list_shared)?>
		    <div class="info_main">
			    <div class="info_top">
			        <h2>Sort Vibes</h2>
			        <a class="wide_button" id="save_changes" href="/tc_signup">Next Step</a>
			    </div>
				 
			    <div class="tc_main" id="vibe_section">
					Please order your selections (the best startup in your opinion should be in the first place)
					<div id="order_picks" style="display:none">
				        <? 
						echo form_open('/tc_signup');
						echo form_close();
						?>
					</div>
			        <ul class="section_body">
			            <li class="vibe_list_edit">
							 <div id="topic_vibe" class="interest_topic tc_interest_topic">
								 <div class="interest_label"> Startups: </div>
								 <div class="interest_edit_area">
									  <div class="interests_list_wrapper">
									  <ul class="interests_list" rel="vibe">
										   <?php 
										   $i = 0;
										   foreach ((array)@$pages as $id => $row)
										   {
												$class = (++$i <= 5)?"interest_full":"interests_collapsed";
												if ($i == 5)
												{
													 $class .= " five";
												}
										   ?>
												<li class="<?php echo $class ?>" rel="<?php echo $row[0]['page_id'] ?>"> <img src="<?=s3_url().$row[0]['thumbnail'] ?>">
													 <div class="interest_caption"><?php echo $row[0]['page_name']; ?></div>
												</li>
										   <? } ?>
									  </ul>
									  <div class="clear"></div>
									  </div>
								 </div>
								 <ul class="interest_position">
								 	<? 
								 	for ($i=1; $i<=5; $i++) {
								 		echo '<li><b>'.$i.'</b></li>';
								 	}
								 	?>
								 </ul>
							</div>
							<div class="clear"></div>
							<hr>								
			            </li>
		        	</ul>
		    	</div>
		    	<div class="clear"></div>
		        <div class="info_bot"></div>
		        <div class="clear"></div>
			</div>
		</div>
	</div>
	
</div>
<div class="clear"></div>

<script type="text/javascript">
$(function() {
    var hash = window.location.hash;
    console.log(hash);
    if (hash === '#my_interests') {
        $('#interests_section').show();
    }
    else if (hash === '#shares') {
            $('#sharetags_section').show();
    }
    else {
        $('#vibe_section').show();
        $('#favs_section').show();
    }


    $('#profileTabs #tab_vibes').live('click', function() {
        $('.info_section').hide();
        $('#vibe_section').show();
        $('#favs_section').show();
        return false;
    });
    $('#profileTabs #tab_interests').live('click', function() {
        $('.info_section').hide();
        $('#interests_section').show();
        return false;
    });
    $('#profileTabs #tab_sharetags').live('click', function() {
        $('.info_section').hide();
        $('#sharetags_section').show();
        return false;
    });
    $('.rm_share').live('click', function() {
        var page_id = $(this).closest('.page_share_tag').attr('value');
        var url = '/rm_share_page_people/'+page_id;
        var entry = $(this).closest('.share_tag_you');
        var page_entry = $(this).closest('.page_share_tag');
        //console.log(page_entry.children().length);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                //alert('ajax');
                if(page_entry.find('.share_tag').length <= 1) {
                    page_entry.hide('blind').remove()
                } else {
                    entry.hide('blind').remove();
                }
            }
        });

        return false;
    });
    $('.untag_from_share').live('click', function() {
        var page_id = $(this).closest('.page_share_tag').attr('value');
        var user_id = "<?=$this->session->userdata('id')?>";
        var url = '/rm_share_page_people/'+page_id+'/'+user_id;
        var entry = $(this).closest('.share_tag_other');
        var page_entry = $(this).closest('.page_share_tag');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                //alert('ajax');
                if(page_entry.find('.share_tag').length <= 1) {
                    page_entry.hide('blind').remove()
                } else {
                    entry.hide('blind').remove();
                }
            }
        });
        return false;
    });
    $('.share_tags .user_avatar').hover(function() {
        $(this).find('.remove_tag').show();
    }, function() {
        $(this).find('.remove_tag').hide();
    });
    $('.share_tags .remove_tag').live('click', function() {
        var page_id = $(this).closest('.page_share_tag').attr('value');
        var user_id = $(this).parent().find('.obj_id').text();
        if (user_id !== null && user_id !== undefined) {
            var url = '/rm_share_page_people/'+page_id+'/'+user_id;
            var entry = $(this).closest('.user_avatar');
            var page_entry = $(this).closest('.share_tags');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    //alert('ajax');
                    //console.log('A: '+page_entry.find('.user_avatar').length);
                    if(page_entry.find('.user_avatar').length <= 1) {
                        //console.log('B: '+page_entry.closest('.page_share_tag').find('.share_tag').length);
                        if (page_entry.closest('.page_share_tag').find('.share_tag').length <= 1) {
                            page_entry.closest('.page_share_tag').hide('fade').remove();
                        } else {
                            page_entry.closest('.share_tag').hide('blind').remove();
                        }
                    } else {
                        entry.hide('fade').remove();
                    }
                }
            });
        }
        return false;
    });

    $('.share_description input[type=submit]').live('click', function() {
        var url = $(this).closest('form').attr('action');
        var data = {
            description: $(this).closest('.share_description').find('textarea').val(),
            ci_csrf_token: $('input[name=ci_csrf_token]').val()
        };
        var updated = $(this).closest('form').find('.updated');
        var form = $(this).closest('form');
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            success: function(data) {
                if (updated.length === 0) {
                    form.append('<span class="updated">Updated</span>');
                }
            }
        });
        return false;
    });
    $('.share_description textarea').live('keyup', function() {
        if ($(this).closest('form').find('.updated').length !== 0) {
            $(this).closest('form').find('.updated').remove();
        }
    });
});
</script>