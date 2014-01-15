<div id="publishers">
	<div class="pubTop">
		<div class="container">
			<div class="row">
				<div class="left">
					<div class="pub_header">GET PAID</div>
					<div class="pub_textBody">
						<strong>We place the content</strong> you choose to your site.
						You start getting <strong>paid</strong> as early as <strong>today</strong>.
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
	<div class="pubMid1">
		<div class="container">
			<div class="row">
				<?=Form_helper::open('', array('rel'=>'ajaxForm', 'class'=>'public'))?>
					<div class="pubMid1_caption"><label>Ready to start?</label></div>
					<input name="url" value="" placeholder="Enter your blog URL" class="pub_emailEntry"/>
					<input type="submit" name="submit" value="Start Making money" class="pub_emailSubmit"/>
					<div class="error"></div>
					<div class="pubMid1_captionLower">We will create your account and email password instructions. We promise to never spam you.</div>
				</form>
			</div>
		</div>
		<div id="thankyou-msg" style="display:none">
			Thank you!
		</div>
	</div>
	<div class="pubMid2">
		<div class="container">
			<div class="row">
				<div class="pubMid2_caption">It's <span class="pub_colorEmphasis">Really Easy</span> to get started</div>
				<ul class="pubSteps">
					<li class="span8"><span class="pubNumbers">1</span><span class="pubNumbers_caption">Add your website or app to our directory.</span></li>
					<li class="span8"><span class="pubNumbers">2</span><span class="pubNumbers_caption">Pick sponsored content or integrate with our API.</span></li>
					<li class="span8"><span class="pubNumbers">3</span><span class="pubNumbers_caption">Start earning money.</span></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="pubBottom">
		<div class="container">
			<div class="row">
				<div class="pubBottom_header">We're Trusted By</div>
				<ul class="pubBottom_logos">
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
