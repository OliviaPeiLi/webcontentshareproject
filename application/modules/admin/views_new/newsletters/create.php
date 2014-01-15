<!-- Page content -->
<div id="page">
	<!-- Wrapper -->
	<div class="wrapper">
		<?php if ($success) : ?>
			<div class="success" id="save_success">
				Your template is going to be sent to all users.
			</div>
		<?php endif;?>
		<section class="column width8 first" id="insert_template_data">
			<h3>Create a Newsletter</h3>
			<?= Form_Helper::open('',array("id"=>"email_template_form")); ?>
				<fieldset>
					<p class="js-template">
						<label class="required" for="firstname">Template:</label><br/>
						<select name="template">
							<option value="">- Select One -</option>
							<?php foreach ($templates as $template) { ?>
							<option value="<?=$template?>"><?=$template?></option>
							<?php } ?>
						</select>
					</p>
					<p class="js-template-data"></p>
					<p>
						
						<a href="#preview" class="btn btn-blue">Preview</a>
					</p>
					<p class="js-preview">
					
					</p>
					<p class="box">
						<input type="submit" value="Send to All Users" class="btn btn-blue" />
					</p>
				</fieldset>

			<?=Form_Helper::close()?>
		</section>
		<!-- End of Left column/section -->	
	</div>
	<!-- End of Wrapper -->
</div>
<!-- End of Page content --> 
<script src="/js/admin/newsletters/create.js"></script>