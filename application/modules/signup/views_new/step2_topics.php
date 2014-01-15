<div id="login_wrapper" class="container_24">
	<div class="signup_container choose_category">
		<div class="signup_title">
			<h6>
				<?=$this->lang->line('signup_category_title');?>
			</h6>
			<div class=status>
				<span class="stepText">Step</span>
				<span class="step"></span>
				<span class="step active"></span>
				<span class="step"></span>
			</div>
		</div>
	    <div class="signup_content grey">
			<?=Form_Helper::open('/choose_category',array('id'=>'category_form'))?>
				<div class="form_row">
					<? $hashtags = $this->hashtag_model->top_hashtags()->get_all()?>
					<? foreach($hashtags as $hashtag) { ?>
						<label class="topicTile">
							<span class="topicImage">
								<span class="topicEffects">
									<span class="topicEffects_contents"></span>
								</span>
								<?=Html_helper::img(Url_helper::s3_url().'hashtags/'.$hashtag.'.png')?>
							</span>
							<?= str_replace("_hash_","#",$hashtag)?>
							<input type="checkbox" name="hashtags[]" value="<?=$hashtag?>"/>
						</label>
					<? } ?>
					<div class="clear"></div>
				</div>
				<div class="form_row">
					<input type="submit" name="Next" value="Next" class="blue-btn disabled_bg"/>
				</div>
			<?=Form_Helper::close()?>
	    </div>
	</div>
</div>
<?=Html_helper::requireJS(array("signup/choose_category"))?> 

<img width="1" height="1" src="//t.optorb.com/cv?co=3258&ev=SignUp&am=3.00">

<!-- optimal retargeting tag --> 
<script type="text/javascript"> 
/* <![CDATA[ */ 
var optimal_pixel_code = "JXIY07ckLv3skmW"; 
/* ]]> */ 
</script> 
<script type="text/javascript" src="//evjs.optorb.com/js/r1.js"></script> 
<noscript>
	<div style="display:inline;"> 
		<img height="1" width="1" style="border-style:none;" alt="" src="//u.optorb.com/m?p=1&r=JXIY07ckLv3skmW"/> 
	</div> 
</noscript>
