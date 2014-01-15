<? if ($stage === 'unfollow') { ?>
	<a href="/unfollow_folder/<?=$folder_id?>" class="folder_unfollow unfollow_button blue_bg" style="float:right;">Following</a>
<? } else if ($stage === 'follow') { ?>
	<a href="/follow_folder/<?=$folder_id?>" class="folder_follow blue_bg" style="float:right;">Follow</a>
<? } else if ($stage === 'new_folder') { ?>
	<? //print_r($folder_info); ?>
	<? //$profile_id = $this->uri->segment(2); ?>
	
	    <? //$this->load->helper('MY_url'); ?>
		<div class="folder expandable_folder" rel="<?=$folder_info->folder_id?>" rel_name="<?=parse_url_string($folder_info->folder_name)?>">
			<div name="<?=$folder_info->folder_id?>" class="folderbase">
				<? /* ?>
				<div class="folder_title inlinediv"><?=$fv['folder_name']?></div>
				<? if ($profile_id === $this->session->userdata['id']) { ?>
					<button class="folder_delete inlinediv" style="right:4px; display:none" type="submit">Remove</button>
					<button class="folder_edit inlinediv" style="float:right; display:none">Edit</button>
					
				<? } ?>
				<? */ ?>
				<div class="folder_items">
					<? $item_count = 0; ?>
					<? foreach ($folder_info->items as $fik => $fiv) { ?>
						<? 
						$item_display = '';
						if ($item_count >= 6) {
							$item_display = 'style="display:none"';
						} 
						?>
						<? $item = unserialize($fiv['data']); ?>
						<?
						?>
						<div class="img_wrapper folder_item" <?=$item_display?>>
							<img src="<?=$item['link_img']?>" alt="<?=$item['title']?>" title="<?=$item['title']?>" />
						</div>
						<? $item_count++; ?>
					<? } ?>
					

				</div>
				<div class="folder_title"><?=$folder_info->folder_name?></div>
				<div class="folder_privacy" style="display:none"><?=$folder_info->private?></div>
				<div class="folder_buttons" style="display:none">
					<a href="#" class="folder_edit blue_bg" style="float:left;">Edit</a>
					<a href="#" class="folder_delete blue_bg" style="float:right;">Delete</a>
				</div>
				<div class="clear"></div>
			</div>
		</div>

		</div>
	</div>
	<script type="text/javascript">
	
	
		$('.folder.expandable_folder').live('click', function() {
			var folder_id = $(this).attr('rel');
			var folder_name = $(this).attr('rel_name');
			window.location.href = "/folder/<?=$user_url_name?>/"+folder_name+"/"+folder_id;
			return false;
		});
		
		$('.expandable_folder .folderbase').hover(function(){
			$(this).animate({width:'268px',height:'268px',left:25,top:25},100);
			$(this).find('.folder_delete').show();
			$(this).find('.folder_edit').show();
			$(this).find('.folder_item').each(function() {
				$(this).show('fade');
				//console.log('show');
			});
			$(this).find('.folder_title').hide('fade');
			$(this).find('.folder_buttons').show('fade');
		},function(){
			var cnt = 0;
			$(this).animate({width:'165px',height:'165px',left:'40px',top:'40px'},100);
			$(this).find('.folder_delete').hide();
			$(this).find('.folder_edit').hide();
			$(this).find('.folder_item').each(function() {
				if (cnt >= 6) {
					$(this).hide();
					//console.log('hide');
				}
				cnt++;
			});
			$(this).find('.folder_title').show('fade');
			$(this).find('.folder_buttons').hide();
			cnt = 0;
		});
		
		$('.folder .folder_item img').load(function() {
			//console.log(this.width+'x'+this.height+',');
			if (this.width > this.height) {
				var asp = this.width/this.height;
				var new_h = this.width;
				var new_w = Math.round(new_h*asp);
				$(this).css('width',new_w+'px');
				$(this).css('height',new_h+'px');
			}
		});
		
		$('.folder_follow').live('click',function() {
			var url = $(this).attr('href');
			var btn = $(this);
			$.get(url,function(res) {
				var par = btn.parent();
				btn.remove();
				par.append($(res));
			});
			return false;
		});
		$('.folder_unfollow').live('click',function() {
			var url = $(this).attr('href');
			var btn = $(this);
			$.get(url,function(res) {
				var par = btn.parent();
				btn.remove();
				par.append($(res));
			});
			return false;
		});

	</script>

<? } else if ($stage === 'folders' || $stage === 'main') { ?>
	<? if ($stage === 'folders') {
		$profile_id = $this->uri->segment(2);
	} else {
		$profile_id = $this->uri->segment(3);
	} ?>
		<? //$profile_id = $this->uri->segment(2); ?>
	
		<div id="folders">
			<? $f_count = 0; ?>
			<? //$this->load->helper('MY_url'); ?>
			<? foreach ($folders as $fk => $fv) { ?>
				<? //print_r($fv); ?>
				<? if ($profile_id == $this->session->userdata['id'] || ($profile_id != $this->session->userdata['id'] && $fv['private'] !== '1')) { ?>
					<div class="folder expandable_folder" rel="<?=$fv['folder_id']?>" rel_name="<?=parse_url_string($fv['folder_name'])?>">
						<?
						$first_item = ''; 
						if ($f_count === 0) {
							$first_item = 'help="Folders store all web content that you&#39;ve dropped using the Fandrop bookmarklet or our internal scraper." pos_my="left top" pos_at="right bottom"';
						}
						?>

						<div name="<?=$fv['folder_id']?>" class="folderbase" <?=$first_item?> title="folder">
							<? /* ?>
							<div class="folder_title inlinediv"><?=$fv['folder_name']?></div>
							<? if ($profile_id === $this->session->userdata['id']) { ?>
								<button class="folder_delete inlinediv" style="right:4px; display:none" type="submit">Remove</button>
								<button class="folder_edit inlinediv" style="float:right; display:none">Edit</button>
							<? } ?>
							<? */ ?>
							<div class="folder_items">
								<? $item_count = 0; ?>
								<? foreach ($fv['items'] as $fik => $fiv) { ?>
									<? 
									$item_display = '';
									if ($item_count >= 6) {
										$item_display = 'style="display:none"';
									} 
									?>
									<? $item = unserialize($fiv['data']); ?>
									<div class="img_wrapper folder_item" <?=$item_display?>>
										<img src="<?=$item['link_img']?>" alt="<?=$item['title']?>" title="<?=$item['title']?>" />
									</div>
									<? $item_count++; ?>
								<? } ?>
								
	
							</div>
							<div class="folder_title"><?=$fv['folder_name']?></div>
							<div class="folder_privacy" style="display:none"><?=$fv['private']?></div>
							<div class="folder_buttons" style="display:none">

								<? if ($profile_id == $this->session->userdata['id']) { ?>
								    <? if($fv['editable'] == '1') { ?>
    									<a href="#" class="folder_edit edit_button" style="float:left;">Edit</a>
    									<a href="#" class="folder_delete blue_bg" style="float:right;">Delete</a>
    								<? } ?>						
								<? } else { ?>
									<? if ($fv['following']) { ?>
										<a href="/unfollow_folder/<?=$fv['folder_id']?>" class="folder_unfollow unfollow_button blue_bg" style="float:right;">Following</a>
									<? } else { ?>
										<a href="/follow_folder/<?=$fv['folder_id']?>" class="folder_follow blue_bg" style="float:right;">Follow</a>
									<? } ?>
								<? } ?>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				<? } ?>
				<? $f_count++; ?>
			<? } ?>
			<? if ($profile_id == $this->session->userdata['id']) { ?>
				<div class="folder nonexpandable_folder create_new" rel="0">
					<div name="0" class="folderbase" id="folder_create_new" help="You can create new folders by clicking here." title="Folder" style="position:relative;" pos_my="left bottom" pos_at="right top">
						<div class="folder_create_desc">Create New Collection</div>
					</div>
				</div>
			
			<? } ?>
			<div class="clear"></div>
			<div id="toggle_collections" class="more_collections" style="text-align:center">More Collections…</div>
			
		</div>
		<div id="all_folders_feed">
			<? include('application/modules/folder/views/folder.php'); ?>
		</div>
	
		<? /* ?>
		<div id="folders">
			<? foreach ($folders as $fk => $fv) { ?>
				<div class="folder expandable_folder" rel="<?=$fv['folder_id']?>">
					<div name="<?=$fv['folder_id']?>" class="folderbase">
						<div class="folder_title inlinediv"><?=$fv['folder_name']?></div>
						<? if ($profile_id === $this->session->userdata['id']) { ?>
							<button class="folder_delete inlinediv" style="right:4px; display:none" type="submit">Remove</button>
							<button class="folder_edit inlinediv" style="float:right; display:none">Edit</button>
							
						<? } ?>
						<div class="folder_items">
							<? $item_count = 0; ?>
							<? foreach ($fv['items'] as $fik => $fiv) { ?>
								<? 
								$item_display = '';
								if ($item_count >= 6) {
									$item_display = 'style="display:none"';
								} 
								?>
								<? $item = unserialize($fiv['data']); ?>
								<div class="img_wrapper folder_item" <?=$item_display?>>
									<img src="<?=$item['link_img']?>" alt="<?=$item['title']?>" title="<?=$item['title']?>" />
								</div>
								<? $item_count++; ?>
							<? } ?>
							

						</div>
					</div>
				</div>
			<? } ?>
			<? if ($profile_id === $this->session->userdata['id']) { ?>
				<div class="folder nonexpandable_folder create_new" rel="0">
					<div name="0" class="folderbase" id="folder_create_new">
						<div class="folder_create_desc">Create New Folder</div>
					</div>
				</div>
			
			<? } ?>
			
		</div>
		<? */ ?>
	<? /* ?>
	<ul id="folders">
		<? foreach ($folders as $fk => $fv) { ?>
			<? //print_r($fv); ?>
			
			<li class="folder_basic folder inlinediv small" rel="<?=$fv['folder_id']?>">
				<div class="expandable_folder">
					<div class="folder_basic_top">
						<div class="folder_title inlinediv"><?=$fv['folder_name']?></div>
						<? if ($profile_id === $this->session->userdata['id']) { ?>
							<button class="folder_delete inlinediv" style="float:right" type="submit">Remove</button>
							<button class="folder_edit inlinediv" style="float:right;">Edit</button>
							
						<? } ?>
					</div>
					<div class="clear"></div>
					<ul class="folder_items">
					<? $item_count = 0; ?>
					<? foreach ($fv['items'] as $fik => $fiv) { ?>
						<? 
						$item_display = '';
						if ($item_count >= 6) {
							$item_display = 'style="display:none"';
						} 
						?>
						<? $item = unserialize($fiv['data']); ?>
						<li class="folder_item" <?=$item_display?>>
							<div><img class="item_img" src="<?=$item['link_img']?>" title="<?=$item['title']?>"></div>
						</li>
						<? $item_count++; ?>
					<? } ?>
					</ul>
					<div class="clear"></div>
				</div>
				
			</li>
		<? } ?>
		<? if ($profile_id === $this->session->userdata['id']) { ?>
			<li class="folder_basic inlinediv small create_new" rel="0">
				<div class="nonexpandable_folder">
					<div class="folder_basic_top">
						<button id="folder_create_new" class="folder_create_new blue_bg inlinediv" style="float:right;">Create New Folder</button>
					</div>
					<div class="clear"></div>	
				</div>
			</li>
		
		<? } ?>
	</ul>
	<? */ ?>
	<div id="edit_folder_popup" style="display:none;" rel="">
		<? echo form_open('/folder_edit_basic'); ?>
			<div class="popup_row">
				<div class="left inlinediv">Collection Name</div>
				<div class="inlinediv">
					<? echo Form_Helper::form_input('name','name','id="folder_edit_name"'); ?>
				</div>
			</div>
			<div class="popup_row">
				<div class="left inlinediv">Collection Privacy</div>
				<div class="inlinediv">
					<? echo form_radio('privacy',1,FALSE,'class="privacy" id="folder_privacy_private"'); ?> Private
					<? echo form_radio('privacy',0,TRUE, 'class="privacy" id="folder_privacy_public"'); ?> Public
				</div>
			</div>
			<div class="popup_row">
				<? echo form_submit('submit','Save','id="folder_save_basic" class="blue_bg"'); ?>
			</div>
		<? echo form_close(); ?>
	</div>
<script type="text/javascript">

	function calc_folders() {
		var folder_tot = 0;
		var container_w = $('#folders').width();
		var f_w = $('.folder').width();
		//console.log(container_w);
		$('.folder').each(function() {
			if (folder_tot > container_w-2*f_w && !$(this).hasClass('create_new')) {
				$(this).hide();
			}
			folder_tot += $(this).width();
			//console.log(folder_tot);
		});
	}

	$(function() {
	
		calc_folders();

		$('#toggle_collections.more_collections').live('click', function(){
			$('.folder:hidden').show('fade');
			$(this).removeClass('more_collections').addClass('less_collections').text('Less Collections...');
		});
		$('#toggle_collections.less_collections').live('click', function(){
			calc_folders();
			$(this).removeClass('less_collections').addClass('more_collections').text('More Collections...');
		});		

	
		$('.folder.expandable_folder').live('click', function() {
			var folder_id = $(this).attr('rel');
			var folder_name = $(this).attr('rel_name');
			window.location.href = "/folder/<?=$this->uri->segment(2)?>/"+folder_name+"/"+folder_id;
			return false;
		});
		
		$('.expandable_folder .folderbase').hover(function(){
			//console.log('min');
			$(this).animate({width:'268px',height:'268px',left:'0px',top:0},100);
			$(this).find('.folder_delete').show();
			$(this).find('.folder_edit').show();
			$(this).find('.folder_item').each(function() {
				$(this).show('fade');
				//console.log('show');
			});
			$(this).find('.folder_title').hide('fade');
			$(this).find('.folder_buttons').show('fade');
		},function(){
			//console.log('mout');
			var cnt = 0;
			$(this).animate({width:'165px',height:'165px',left:'40px',top:'40px'},100);
			$(this).find('.folder_delete').hide();
			$(this).find('.folder_edit').hide();
			$(this).find('.folder_item').each(function() {
				if (cnt >= 6) {
					$(this).hide();
					//console.log('hide');
				}
				cnt++;
			});
			$(this).find('.folder_title').show('fade');
			$(this).find('.folder_buttons').hide();
			cnt = 0;
		});
		
		$('.folder .folder_item img').load(function() {
			//console.log(this.width+'x'+this.height+',');
			//var sq_side = Math.min(this.width, this.height);
			//$(this).css('width',sq_side+'px');
			//$(this).css('height',sq_side+'px');			
			var asp = this.width/this.height;
			if (this.width > this.height) {
				var new_h = this.width;
				var new_w = Math.round(new_h*asp);
			} else {
				var new_w = this.height;
				var new_h = Math.round(new_w/asp);
			}
			$(this).css('width',new_w+'px');
			$(this).css('height',new_h+'px');			
		});
		
		$('.folder_follow').live('click',function() {
			var url = $(this).attr('href');
			var btn = $(this);
			$.get(url,function(res) {
				var par = btn.parent();
				btn.remove();
				par.append($(res));
			});
			return false;
		});
		$('.folder_unfollow').live('click',function() {
			var url = $(this).attr('href');
			var btn = $(this);
			$.get(url,function(res) {
				var par = btn.parent();
				btn.remove();
				par.append($(res));
			});
			return false;
		});
			
		$('.privacy').change(function() {
			$('.privacy').removeAttr('checked');
			$(this).attr('checked','checked');
			var f_id = $(this).closest('#edit_folder_popup').attr('rel');
			$('.folder[rel='+f_id+']').find('.folder_privacy').text($(this).val());
			return false;
		});

/*		
		$('.expandable_folder').hover(function(){
			$(this).animate({width:'244px',height:'204px',left:'0px',top:'0px'},100);
			$(this).find('.folder_item').each(function() {
				$(this).show('fade');
				//console.log('show');
			});
		},function(){
			$(this).animate({width:'150px',height:'150px',left:'48px',top:'27px'},100);
			var cnt = 0;
			$(this).find('.folder_item').each(function() {
				if (cnt >= 6) {
					$(this).hide();
					//console.log('hide');
				}
				cnt++;
			});
		});
*/		
		/*	
		$('.folder').live('mouseenter', function(e) {
			e.stopPropagation();
			$(this).addClass('large').removeClass('small');
			
			$(this).find('.folder_item').each(function() {
				$(this).show('fade');
				//console.log('show');
			});

			return false;
		});
		$('.folder').live('mouseleave', function(e) {
			e.stopPropagation();
			$(this).addClass('small').removeClass('large');
			
			var cnt = 0;
			$(this).find('.folder_item').each(function() {
				if (cnt >= 6) {
					$(this).hide();
					//console.log('hide');
				}
				cnt++;
			});

			return false;
		});
		*/
		$('.folder_delete').live('click', function() {
			var entry = $(this).closest('.folder');
			var folder_id = entry.attr('rel');
			$.get('/delete_folder/'+folder_id, function() {
				entry.hide('fade').remove();
			});
			return false;
		});
		$('.folder_edit, #folder_create_new').live('click', function() {
			/* get data from folder */
			var folder_title = $(this).closest('.folder').find('.folder_title');
			var folder_privacy = $(this).closest('.folder').find('.folder_privacy');
			var folder_id = $(this).closest('.folder').attr('rel');
			var dlg_title = ($(this).attr('id') === 'folder_create_new') ? 'Create New Collection' : 'Edit Collection';
			/* prepare dialog for data */
			$('#edit_folder_popup').dialog({
				modal: true,
				draggable: false,
				resizable: false,
				title: dlg_title,
				width: 400,
				height: 150,
				autoOpen: false,
				open: function(event, ui) {
		        	$('.ui-widget-overlay').click(function() {
		        		$(this).prev().find('.ui-dialog-content').dialog('close');
		        	});
		        	//console.log(folder_title);
		        	$('#edit_folder_popup #folder_edit_name').val(folder_title.text());
		        	$('#edit_folder_popup .privacy').removeAttr('checked');
		        	if (folder_privacy.text() === '1') {
						$('#edit_folder_popup #folder_privacy_private').attr('checked','checked');
		        	} else {
		        		$('#edit_folder_popup #folder_privacy_public').attr('checked','checked');
		        	}
		        	$('#edit_folder_popup').attr('rel',folder_id);
		        },
				close: function() {
					$('#up_window').dialog('destroy').remove();
				}
			});
			$('#edit_folder_popup').dialog('open');
			return false;
		});
		
		$('#folder_save_basic').live('click', function() {
	        var url = $(this).closest('form').attr('action');
	        var private = ($(this).closest('form').find('.privacy:checked').val() === '1') ? 1 : 0;
	        var folder_name = $("#folder_edit_name").val();
	        var folder_id = $('#edit_folder_popup').attr('rel');
	        var new_folder = (folder_id === '0');
	        var data = {
	        	folder_id: folder_id,
				folder_name: $("#folder_edit_name").val(),
				private: private,
				ci_csrf_token: $("input[name=ci_csrf_token]").val()
			};

	        
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(msg) {
					//smth
					if (new_folder) {
						//console.log('new_folder');
						msg = $(msg);
						msg.hide();
						$('.create_new').before(msg);
						msg.show('fade');
					} else {
						$('.folder[rel='+folder_id+']').find('.folder_title').text(folder_name);
					}
					$('#edit_folder_popup').dialog('close').dialog('destroy');
				}
	        });

			return false;
		});
	});

</script>
<? } ?>


