<?php if ($filters): ?>
<div class="content-box corners content-box-closed">
	<header style="cursor: s-resize; ">
		<h3><img src="/images/admin/find.png" alt="">Search</h3>
	</header>
	<section class="filter">
		<?=Form_Helper::open('', array('method'=>'GET'))?>
			<fieldset>
				<? foreach($filters as $field=>$type){ ?>
					<p>
						<label><?=$field?>:</label>
						<?=$this->ft_admin->render_filter_field($field, $type )?>
					</p>
				<? } ?>
				<input type="hidden" name="search" value="search" />
				<p><input type="submit" value="Search" class="btn btn-blue" /></p>				
			</fieldset>
		<?=Form_Helper::close()?>
	</section>
</div>
<?php endif; ?> 