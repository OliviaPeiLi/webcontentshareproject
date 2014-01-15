<? $this->lang->load('folder/folder', LANGUAGE);?>
<div id="embed_collection_overview" style="display:none;">
	<div class="modal-body">
		<form action="">
			<div class="form_row">
				<textarea class="sample" style="display:none" cols="90" rows="2"><iframe width="460" height="259" style="border: none;" src="<?=Url_helper::base_url()?>embed/collection/{folder_id}"></iframe></textarea>
				<textarea id="embed_code" class="" name="embed_code"cols="90" rows="2"></textarea>
			</div>
		</form>
	</div>
</div> 