<? $this->lang->load('about/footer', LANGUAGE); ?>
<div class="aboutHead">
	<h4><?=ucwords($this->lang->line('contact_us'));?></h4>
</div>
<div class="aboutBody">
	<div id="about_contact_form" class="inlinediv">
	    <? //include('contact_form.php') ?>
	    <?=$this->load->view('about/contact_form','',true); ?>
	</div>
	<div id="about_contact_details" class="inlinediv">
	    <ul id="about_contact_info" class="inlinediv">
			<li>
				<div class="about_label inlinediv" style="margin-top: 0px;">Fandrop <?=$this->lang->line('footer_phone_lexicon');?></div>
				<div class="about_field inlinediv" style="margin-top: 0px;">(650) 223-5386</div>
			</li>
			<li>
				<div class="about_label inlinediv" style="margin-top: 0px;">Fandrop <?=$this->lang->line('email');?></div>
				<div class="about_field inlinediv" style="margin-top: 0px;">info@fandrop.com</div>
			</li>
			<li>
				<div class="about_label inlinediv" style="margin-top: 0px;">Fandrop Address</div>
				<div class="about_field inlinediv" style="margin-top: 0px;">1372 McAllister Street</div>
				<div class="about_field inlinediv" style="margin-top: 0px;">San Francisco, CA</div>
			</li>
	    </ul>
	    <? /* ?><div id="about_contact_map" class="inlinediv">
		<img src="http://maps.googleapis.com/maps/api/staticmap?maptype=roadmap&amp;markers=icon:http://chart.apis.google.com/chart?chst=d_map_pin_icon%26chld=corporate%257C4376CC%7Csize:mid%7CPalo+Alto&amp;size=360x270&sensor=false" alt="">
	    </div><? */ ?>
	</div>
	
</div>