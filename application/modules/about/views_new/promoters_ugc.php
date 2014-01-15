<? $this->lang->load('about/footer', LANGUAGE); ?>
<div id="promoters">
	<div class="promoTop">
		<div class="container">
			<div class="row">
				<div class="left">
					<div class="promo_header"><?=$this->lang->line('footer_promoters_top_title');?></div>
					<div class="promo_textBody">
						<?=$this->lang->line('footer_promoters_top_text');?>
					</div>
				</div>
				<div class="right">
					<ul>
						<li><img src="" alt=""/></li>
						<li><img src="" alt=""/></li>
						<li><img src="" alt=""/></li>
						<li><img src="" alt=""/></li>
						<li><img src="" alt=""/></li>
						<li><img src="" alt=""/></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="promoMid1">
		<div class="container">
			<div class="row">
				<?=Form_helper::open('', array('rel'=>'ajaxForm', 'class'=>'public'))?>
					<div class="promoMid1_caption"><label><?=$this->lang->line('footer_promoters_mid_caption');?></label></div>
					<input name="email" value="" placeholder="<?=$this->lang->line('email');?>" class="promo_emailEntry"/>
					<input type="submit" name="submit" value="<?=$this->lang->line('footer_promoters_mid_submit');?>" class="promo_emailSubmit"/>
					<div class="error"></div>
					<div class="promoMid1_captionLower"><?=$this->lang->line('footer_promoters_mid_caption2');?></div>
				</form>
			</div>
		</div>
		<div id="thankyou-msg" style="display:none">
			<?=$this->lang->line('thank_you');?>!
		</div>
	</div>
	<div class="promoMid2">
		<div class="container">
			<div class="row">
				<div class="promoMid2_caption"><?=$this->lang->line('footer_promoters_mid2_caption');?></div>
				<ul class="promoSteps">
					<li class="span8"><span class="promoNumbers">1</span><span class="promoNumbers_caption"><?=$this->lang->line('footer_promoters_mid2_step1');?></span></li>
					<li class="span8"><span class="promoNumbers">2</span><span class="promoNumbers_caption"><?=$this->lang->line('footer_promoters_mid2_step2');?></span></li>
					<li class="span8"><span class="promoNumbers">3</span><span class="promoNumbers_caption"><?=$this->lang->line('footer_promoters_mid2_step3');?></span></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="promoBottom">
		<div class="container">
			<div class="row">
				<div class="promoBottom_header"><?=$this->lang->line('footer_promoters_bot_heading');?></div>
				<ul class="promoBottom_logos">
					<li class="inlinediv"><a href="" class="inlinediv"><img src="/images/landingpage_headerimages/press/sfGateLogo.png" alt=""/></a></li>
					<li class="inlinediv"><a href="" class="inlinediv"><img src="/images/landingpage_headerimages/press/cbsNewsLogo.png" alt=""/></a></li>
					<li class="inlinediv"><a href="" class="inlinediv"><img src="/images/landingpage_headerimages/press/wsjLogo.png" alt=""/></a></li>
					<li class="inlinediv"><a href="" class="inlinediv"><img src="/images/landingpage_headerimages/press/bostonLogo.png" alt=""/></a></li>
					<li class="inlinediv"><a href="" class="inlinediv"><img src="/images/landingpage_headerimages/press/techCrunchLogo.png" alt=""/></a></li>
					<li class="inlinediv"><a href="" class="inlinediv"><img src="/images/landingpage_headerimages/press/pandoDailyLogo.png" alt=""/></a></li>
					<li class="inlinediv"><a href="" class="inlinediv"><img src="/images/landingpage_headerimages/press/techvibesLogo.png" alt=""/></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?=Html_helper::requireJS(array('about/promoters'))?>