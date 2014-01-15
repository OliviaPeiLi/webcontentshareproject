
<? if ($type === 'user') { ?>
	<script type="text/javascript"> 
		$(document).ready(function(){ 
			$(".get_vibe").hover(function() {
				var vibe = $(this).closest('.badge_detail').find('.vibeInterests'); 
				vibe.slideDown('slow');
			},function() {
				var vibe = $(this).closest('.badge_detail').find('.vibeInterests'); 
				vibe.fadeOut('fast');
			});  
			getSimilarityMatch();
		});
	</script> 
	
	
	<? 
		if($user_info['thumbnail'] == '')
		{
			if($user_info['gender'] == 'm')
			{			
				$user_info['thumbnail'] = s3_url()."users/default/defaultMale.png";
			}
			else
			{
				$user_info['thumbnail'] = s3_url()."users/default/defaultFemale.png";
			}
		}else{
			$user_info['thumbnail'] = s3_url().$user_info['thumbnail'];
		}
	?>
	
	<div class="badge_detail">
		<div class="badge_left"><a href="#"><img src="<?=$user_info['thumbnail']?>" alt=""  /></a></div>
		<div class="badge_right">
			<div class="badge_title"><a href="#"><?=$user_info['first_name'].' '.$user_info['last_name']?></a></div>
		<? /* ?><!--	<div id="similarityScore" style="display:none"><?=$similarityValue?></div>
			<div class="matchmeter">
				<a href="">Interest similarity to you <?=$my_data['first_name']?>
					<span class="similarityBar"><span id="similarityBar" style="width: 0%;"></span>
				</span></a>
			</div> -->
		<? */ ?>	
			<div class="clr"></div>
			<div class="badge_options">
				<img id="open_graph" style="cursor: pointer" class="inlinediv" src="/images/interestGraph.png" title="Open graph">
				<div class="get_vibe inlinediv"><a href="" class="do">Vibe</a>
					<div class="vibeInterests" style="display:none;">
						<div class="def"><b>Vibes (top interests)</b></div>
						<ul class="badge_items">
						<? 
						foreach($user_interests as $key => $value) {
							echo '<li>'
                                 .'<a href="/interests/'.$value['uri_name'].'/'.$value['page_id'].'">'.$value['page_name'].'</a>'
                                 .'</li>';
						}
						?>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="clr"></div>
	</div>

	<script type="text/javascript">
	$(function() {
		$('#graph_dialog').dialog({
			modal: true, 
			draggable: false,
			resizable: false,
			width: $(window).width()-100,
			height: $(window).height()-100,
			autoOpen: false,
			close: function() {}
		});
		$('#open_graph').live('click',function() {
			
			$('<iframe id="graph_iframe" name="graph_iframe"></iframe>').appendTo('#graph_dialog');
			$('#graph_iframe').css('width','100%').css('height','100%');
			$('#graph_dialog').find('#graph_iframe').attr('src', '/get_graph/<?=$user_info['id']?>');
			$('#graph_dialog').dialog('open');
		});
	});
	</script>
	<div id="graph_dialog" style="display: none; position: relative"></div>
<? } else {  
			if($page_info['thumbnail'] == '')
			{
				$page_info['thumbnail'] = s3_url().'pages/default/defaultInterest/'.$page_info['interest_id'].'.png';
			}else{
				$page_info['thumbnail'] = s3_url().$page_info['thumbnail'];
			}
?>
	<? if ($stage === 'register') { 
		$badge_link = '';
	} else {
		$badge_link = '/interests/'.$page_info['uri_name'].'/'.$page_info['page_id'];
	}
	?>
	
	<div class="badge_detail">
		<div class="badge_left"><a href="#"><img src="<?=$page_info['thumbnail']?>" alt="" /></a></div>
		<div class="badge_right">
			<? if($link_disable == 1){ ?>
				<div class="badge_title"><b><?=$page_info['page_name']?></b></div>
			<? }else{ ?>
				<div class="badge_title"><a href="#"><?=$page_info['page_name']?></a></div>
			<? } ?>
			<? $person_text = ($page_info['hits'] > 1) ? 'people' : 'person'; ?>
			<div><?=$page_info['hits']?> <?=$person_text?> voted for this company</div>
			<div class="clr"></div>
		</div>
		<div class="clr"></div>
	</div>
<? } ?>