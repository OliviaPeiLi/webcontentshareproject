<div id="upload_profilepic_dlg">
    <div id="imgupload_preview">
		<div id="preview" style="margin-right: 20px;">
		    <?=Html_helper::img($user->avatar_73)?>
		</div>
    </div>
    <div id="imgupload_options" style="margin-left: 20px;">
		<div id="imgupload_file_col">
			<?=Form_Helper::open('/profile/edit_picture', 
				array('id'=>'profile_pic', 'rel'=>'ajaxForm', 'class'=>"profile_picture_modal", 'success'=>'Avatar changed'),
				array('src_img' => '')
			)?>
			    <div id="imgupload_newimg_pane">
					<div class="hidden_upload">
						<div id="choose_file_btn" class="blue_bg blueButton" style="color:white; display: inline-block;"><span class="ico"></span>Choose File</div>
						<?=Form_Helper::upload('avatar','',array('size'=>"20", 'id'=>"select_file_btn_hidden"))?>
					</div>
					<div style="display:none" class="upload_err error"></div>
			    </div>
			    <div class="clear"></div>
			    <?=Form_Helper::submit('save', 'Save', array('id'=>"save_preview", 'class'=>"button blue_bg greyButton", 'style'=>"display: none;"))?>
		    <?=Form_Helper::close()?>    
	    </div>
	    <? /* ?>
	    <? //IMGTHUMB: Disabled for now ?>
	    <div id="imgupload_thumb_col">
	    	<?php if (strpos($user->avatar, 'default/') === false) :?>
			<a id="upload_editthumb_lnk" class="" style="display: block;" href="">Edit Thumbnail</a>
			<div id="imgupload_thumb_pane" style="display:none">
			    <div id="thumbnail" style="width:30px; height: 30px; border:1px #e5e5e5 solid; overflow:hidden; ">
					<img src="" alt="preview"/>
			    </div>
			    <?=Form_Helper::open('profile/crop', array('name' => 'thumbnail', 'id' => 'thumb_form'), array('x'=>'','y'=>'','w'=>'','h'=>'','p_w'=>'','img_path'=>'')); ?>
				    <? echo Form_Helper::submit('upload_thumbnail', 'Save Thumbnail', 'class="button blue_bg save_thumb_button"'); ?>
			    <?=Form_Helper::close()?>
			    <div class="clear"></div>
			    <div>Please Select an area on the picture for cropping</div>
			</div>
			<div id="thumb_saved" style="display:none; font-color: green; font-weight: bold;">New thumb saved</div>
			<?php endif;?>
	    </div>
	    <? */ ?>
	</div>
	<?=Html_helper::requireJS(array("profile/edit_picture"))?>
</div>