<div style="padding:10px 20px 20px">	
	
	<?=Form_Helper::open('admin/folders/merge')?>
		<p>			
			<h4> Select main folder:</h4>
			<?=Form_Helper::dropdown("main_folder_id", $folders)?>
		</p>
		<p>
			<h4>Folders to be merged:</h4>
			<ul>
				<? foreach($folders as $folder_id =>$folder):?>
					<li>
						<?=$folder?>
						<input type="hidden" name="folders[]" value="<?=$folder_id?>" />
					</li>
				<?endforeach?>
			</ul>
		</p>
		<p > 
			<input type="submit" value="Merge" class="btn btn-blue"> 
			<a href="javascript:;" class="btn btn-red nyroModalClose">Cancel</a>
		</p>
	<?=Form_Helper::close()?>
</div> 