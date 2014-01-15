<div class="content-box corners content-box-closed">
	<header style="cursor: s-resize; ">
		<h3><img src="/images/admin/find.png" alt="">Forms</h3>
	</header>
	<section class="filter">
		<?php echo form_open_multipart();?>
			<fieldset>
				<? foreach($fields as $field=>$type){ ?>
					<p>
						<label><?=$field?>:</label>
						<?=$this->ft_admin->render_submit_form_field($field, $type )?>
					</p>
				<? } ?>
				<input type="hidden" name="Submit" value="Submit" />
				<p><input type="submit" value="Submit" class="btn btn-blue" /></p>				
			</fieldset>
		<?=form_close()?>
	</section>
</div> 