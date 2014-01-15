<!-- Page content -->
	<div id="page">
		<!-- Wrapper -->
		<div class="wrapper">
			<section class="column width6 first">
				<? if(count($form_fields) > 0 && array_values($form_fields) == $form_fields) { ?>
                <div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                    	<?php foreach ($form_fields as $key=>$sub_form) { ?>
                        	<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
                        		<a class="<?=!$key?'corner-tl':($key==count($form_fields)-1?'corner-tr':'')?>" href="#tabs-<?=$key?>"><?=$sub_form[0]?></a>
                        	</li>
                        <?php } ?>
                    </ul>
                    <?php foreach ($form_fields as $key=>$sub_form) { ?>
	                <div id="tabs-<?=$key?>" class="ui-tabs-panel ui-widget-content ui-corner-bottom <?=!$key?'':'ui-tabs-hide'?>">
						<h3><?=$sub_form[0]?> <?=singular(ucfirst($this->router->class))?></h3>
						<?=Form_Helper::validation_errors()?>
						<? $keys = array_keys($list_fields,'primary_key'); $primary_key = $keys[0]; ?>
						
						<?=$sub_form[0] != 'Stats' ? Form_Helper::form_open_multipart() : ''?>

						<fieldset>
							<? foreach($sub_form as $field=>$type) { if (is_int($field)) continue; ?>
								<p>
									<label><?=$field?>:</label>
									<?=$this->ft_admin->render_form_field($field, $data, $type )?>
								</p>
							<? } ?>
						</fieldset>
						
						<?php if ($sub_form[0]!='Stats') { ?>
							<p class="box"><?=$this->ft_admin->render_actions($edit_actions, $data, $primary_key)?></p>
							<?=Form_Helper::close()?>
						<?php } ?>

					</div>
                    <?php } ?>
                </div>
				<?php } else { ?>
					<h3>Edit <?=singular(ucfirst(str_replace('_', ' ', $this->router->class)))?></h3>
					<? //$keys = array_keys($list_fields,'primary_key'); $primary_key = $keys[0]; ?>
					<?= Form_Helper::form_open_multipart(''); ?>

						<fieldset>
							<? foreach($form_fields as $field=>$type){ ?>
								<p>
									<label class="required" for="firstname"><?=$field?>:</label><br/>
									<?=$this->ft_admin->render_form_field($field, $data, $type )?>
								</p>
							<? } ?>
							<p class="box"><?=$this->ft_admin->render_actions($edit_actions, $data, $data->_model->primary_key())?></p>
						</fieldset>

					<?=Form_Helper::close()?>
				<?php } ?>
			</section>
			<!-- End of Left column/section -->	
		</div>
		<!-- End of Wrapper -->
	</div>
	<!-- End of Page content --> 