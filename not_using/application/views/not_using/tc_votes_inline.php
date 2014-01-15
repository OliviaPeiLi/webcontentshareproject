<div id="profile_edit_interests">
    <?//print_r($all_interests)?>
    <?//print_r($share_list_owner)?>
    <?//print_r($share_list_shared)?>
    <div class="votes_main">

	    <div id="vibe_section">
	    	<div style="padding:10px">Results of the voting is below.</div>
			
			<ul class="tc_users">
				<? foreach($votes as $k => $user) { ?>
					<li class="tc_user">
						<div class="voter_label"><?=$user['first_name'].' '.$user['last_name']?> has voted for </div>
						<ul class="tc_user_votes">
							<?
							foreach($user['votes'] as $u => $vote) { ?>
							
								<li class="vote tc_show_badge page_avatar">
									<img class="vote" src="<?=s3_url().$vote['thumbnail']?>">
									<div class="obj_id" style="display: none;"><?=$vote['p_id']?></div>
								</li>
							
							<? } ?>
						</ul>
					</li>
				<? } ?>
			
			</ul>
    	</div>
	</div>
</div>
<div class="clear"></div>

<script tyle="text/javascript">
$(function() {

	    var hoverintent_config = {
	        over: tc_show_badge,
	        timeout:200,
	        interval: 300,
	        out: tc_hide_badge
	    };
	    $('.tc_show_badge').hoverIntent(hoverintent_config);
	    


	$('#close_frame').click('live', function() {
		$(window.opener.document.getElementById('tc_vote_iframe')).dialog('destroy');
	});

});


function tc_show_badge() {
    if($('.badge').text()!='') {$('.badge').remove();return false;}
    //e.stopPropagation();
    var badge_style = $(this).find('.badge_style').text();

    var obj_id = $(this).find('.obj_id').text();
    var x = $(this).find('.offset .x').text();
    var y = $(this).find('.offset .y').text();
    console.log('['+x+','+y+']');
    var more_info = $(this).find('.more_info').html();

    if ($(this).hasClass('interests_item')) {
        //alert('aaa');
        th=$(this);
        th.append('<div class="badge"></div>');
        obj_id = $(this).attr('rel');
        $(this).find('.thumb').append('<div class="badge"></div>');
        var top = $(this).offset().top+$(this).height();
        var left = $(this).offset().left;
        $('.badge').offset({top: top,left: left});
    } else {
        th=$(this);
        th.append('<div class="badge"></div>');
    }
    console.log($(this).offset());
    if ($(this).find('.offset').length !== 0) {
        var top = parseInt($(this).offset().top)+parseInt(y);
        var left = parseInt($(this).offset().left)+parseInt(x);
        $('.badge').offset({top: top, left: left});
    }

    var type = ($(this).hasClass('user_avatar')) ? 'user' : 'page';
    //$('.badge').append('<div class="loading">Loading...</div>');
    if ($(this).find('.badge_style').length !== 0) {
        $('.badge').addClass(badge_style);
    } else {
        $('.badge').addClass('badge_light');
    }
    var badge_dir = $(this).find('.badge_direction').text();
    var link_disable = $('#link_disable').text();
    console.log(badge_dir);
    if ($(this).find('.badge_direction').length !== 0) {
        $('.badge').addClass(badge_style+'_'+badge_dir);
    }
    $('.badge').load('/tc_badge/'+type+'/'+obj_id+'/'+link_disable, function() {
        //var badge_src = $(this).closest('.show_badge');
        if (more_info !== null && more_info !== undefined) {
            more_info = $(more_info);
            $('.badge').prepend(more_info).css('padding-top','0px');
            more_info.show();
        }
        $('.badge').fadeIn(500);
    });
}
function tc_hide_badge() {
    $('.badge').fadeOut('fast',function(){
        $('.badge').remove();
    });
}

</script>