<div id="container" class="container_24">
	<div id="main" class="prefix_3 grid_18 suffic_3">
		<div id="profile_edit_interests">
		    <div class="info_main">
			    <div class="info_top">
			        <h2>Share your Vote</h2>
			    </div>
				<? if ($this->session->userdata['fb_id']) { ?> 
				    <div class="info_section" id="fb_section">
				    	<h6>Post on Facebook</h6>
				    	<ul class="section_body">
				    	<? echo form_open('/tc_facebook_post') ?>
							<li><input type="submit" value="Post on Facebook"></li>
						<? echo form_close() ?>
						</ul>
			    	</div>
		    	<? } ?>
				<? if ($this->session->userdata['twitter_id']) { ?> 
				    <div class="info_section" id="fb_section">
				    	<h6>Post on Twitter</h6>
				    	<ul class="section_body">
				    	<? echo form_open('/tc_twitter_post') ?>
							<li><input type="submit" value="Post on Twitter"></li>
						<? echo form_close() ?>
						</ul>
			    	</div>
		    	<? } ?>
				<? if (!$this->session->userdata['twitter_id'] && !$this->session->userdata['twitter_id']) { ?> 
				    <div class="info_section" id="fb_section">
				    	<h6>Refer 3 of your friends</h6>
				    	<ul class="section_body">
				    	<? echo form_open('/tc_email_post') ?>
				    		<? echo Form_Helper::form_input('email[]','Email 1', 'id="email1" class="input_placeholder"'); ?>
				    		<? echo Form_Helper::form_input('email[]', 'Email 2', 'id="email2" class="input_placeholder"'); ?>
				    		<? echo Form_Helper::form_input('email[]', 'Email 3', 'id="email3" class="input_placeholder"'); ?>
							<li><input type="submit" value="Refer"></li>
						<? echo form_close() ?>
						</ul>
			    	</div>
		    	<? } ?>		    	
		    	<div class="clear"></div>
		        <div class="info_bot"></div>
		        <div class="clear"></div>
			</div>
		</div>
	</div>
	
</div>
<div class="clear"></div>

