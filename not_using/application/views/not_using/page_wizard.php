	<div class="pagewizard_container container_24">
<div><h1>Choose Interest Category</h1></div>
</br>
</br>
<? /*
<div id="pagewizard_categories">
	<?
		echo '<h3>Main Interest Topic</h3>';
		echo form_open('create_page_submit');
		echo form_dropdown('category',$categories, '', 'id="interest_category_charity" class="box_input"');
		echo form_submit('submit', 'Submit');
		echo form_close();
	?>
	<? //$i=0; ?>
	<div>
*/ ?>
	<div>
		<? echo form_open('create_page_submit'); ?>
		Page Name : <input type="text" name="pname" />
	</div>
	</br>
	</br>
	<div>
		Category : &nbsp &nbsp
		<select name='category'>
		<?php foreach ($categories as $a=>$y){ 
	  			if($y['id']=1) echo "<option value='1' selected='selected'>{$y['type']}</option>";
	  				else echo "<option value='{$y['id']}'>{$y['type']}</option>";
	  		  }
		?>
		</select>
	</div>	
	</br>
	</br>
	<div>
			<a id="upload_newimg_lnk" style="display:block;" href="">Upload new image</a>
			<div id="imgupload_newimg_pane" style="display: none;">
				<?php echo form_open_multipart('upload_photo_profile/'.$page_id, 'id="orig_img_upload_form"'); ?>
				<?php echo form_hidden('album','profile'); ?>
				<?php echo form_hidden('ispage','1'); ?>
				<?php echo form_hidden('page_id',$this->uri->segment(3)); ?>
				<?php echo form_hidden('ajax','1'); ?>
				<?php echo form_upload('userfile','','size="20"'); ?>
				<?php echo form_close(); ?>
			</div>
	</div>
	<div>
			<a id="upload_editthumb_lnk" style="display: block;" href="">Edit Thumbnail</a>
			<div id="imgupload_thumb_pane" style="display: none;">
				<div id="thumbnail" style="width:30px; height: 30px; border:1px #e5e5e5 solid; overflow:hidden; ">
					<img src="<? echo s3_url().'pages/default/default_pic.jpg'; ?>" style="vertical-align: top; position: relative; width: 30px; height: 30px;"/>
				</div>
				<? $attrs = array('name' => 'thumbnail', 'id' => 'thumb_form'); ?>
				<? echo form_open('cropped_image', $attrs); ?>
				<input type="hidden" name="x1" id="x1" />
				<input type="hidden" name="y1" id="y1" />
				<input type="hidden" name="w" id="w" />
				<input type="hidden" name="h" id="h" />
				<input type="hidden" name="user" id="user" value=<? echo $page_id ?>/>
				<input type="hidden" name="src_img" id="src_img" />
				<input type="hidden" name="ispage" id="ispage" value="1"/>
				<input type="hidden" name="page" id="page" value=<? echo $page_id ?>/>
				<? echo form_submit('upload_thumbnail', 'Save Thumbnail', 'id="upload_thumb"'); ?>
				<? echo form_close(); ?>
				<div id="thumb_saved" style="display:none; font-color: green; font-weight: bold;">New thumb saved</div>
				<div>Please Select an area on the picture for cropping</div>
		      </div>
		      </br>
			<input type="submit" value="Submit" />
	</div>
<? /* 
		<? $classes = 'cell inlinediv'; ?>
		<? if ($i%5 === 0) { ?>
			<div class="row grid_24">
			<!--<div class="row_hidden" style="width: 800px">-->
			<? $classes .= ' first'; ?>
		<? } ?>
		<? if ($i%5 === 4) { ?>
			<? $classes .= ' last'; ?>
		<? } ?>
		<? $cat_title = ucfirst($v['type']); ?>
		<div class="<?=$classes?>">
			<div class="wrap">
				<div class="categoryImg categoryContainer">
					<img src="/images/pagewizard_icons/default<?=$cat_title?>.png">
				</div>
				<div class="categoryWizard categoryContainer" name="pagewiz_box_<?=$v['type']?>">
					<?
							echo '<h3>'.$cat_title.'</h3>';
							echo form_open('create_page_submit');
							echo Form_Helper::form_input('page_name','Page Name', 'class="box_input box_input_placeholder"');
							echo form_hidden('box_id', 'charity');
							echo form_hidden('category_title', $cat_title);
							echo form_hidden('general_category_id', $v['id']);
							echo form_hidden('interest_category_id', $v['id']);
							echo form_checkbox('accept_terms','Accept Terms', FALSE);
							$attr = array('class'=>'terms');
							echo form_label('I agree to Fandrop Pages Terms','accept_terms',$attr);
							$submit = Array ("name" => "submit", "value" => "Submit", "id" => "cat_submit", "class" => "pagewiz_submit_1_2 button");
							echo form_submit($submit);
							echo form_close();
					?>
				</div>
			</div>
		</div>
		<? if ($i%5 === 4) { ?>
			<div class="clear"></div>
			<!--</div>-->
			</div>
		<? } ?>
		<? $i++; ?>
	<? } ?>
</div>
*/ ?>
