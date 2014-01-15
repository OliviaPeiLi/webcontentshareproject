<? $this->lang->load('message/message', LANGUAGE) ?>

<div id="private_msg_form" style="display: none;" class>
	<?=Form_Helper::open('send_msg', array('id'=>'send_msg_message_form', 'rel'=>'ajaxForm'))?>
		<ul style="width:100%">
			<li class="autocomplete_input inlinediv">
				<div class="text_align_left form_field inlinediv">
					<?=Form_Helper::input('receivers', '', array(
											'id'=>"private_msg_name",
											'class'=>"tokenInput",
											'data-url'=>"/ac_get_connections",
											'theme'=>"google",
											'linkedText'=>$this->lang->line('message_add_people_btn'),
											'prevent_duplicates'=>"true",
											'no_results_text'=>"No people found.",
											'style'=>"width:400px;",
											"Placeholder"=>"To:"
					)) ?>
				</div>
			</li>
			<div class="popup_row js-popup_row_error popup_row_error js-err_msg_receiver err_msg_receiver" style="display:none">Choose at least one recipient</div>
			<li class="form_row">
				<div class="text_align_left form_field inlinediv">
					<?=Form_Helper::textarea('msg_body', '', array(
						'placeholder' => $this->lang->line('message_msg_body_default'),
						'rows' => "5",
						'id'=>'msg_body',
						'data-validate' => 'required|maxlength',
						'data-error-required' => 'The message cannot be left blank',
						'data-maxlength' => 250,
						'id'=>'msg_body',
						'data-nokey'=>'true',
						'style' => "width:388px; height: 70px;"
					))?>
					<div class="textLimit">250</div>
				</div>
				<div class="error js_error_msg"></div>
			</li>
			<li>
				<div> 
					<?=Form_Helper::submit('submit', $this->lang->line('message_send_submit_btn'), array('id'=>"send_msg_btn", 'class'=>"blue_bg"))?>
				</div>
			</li>
		</ul>
	<?=Form_Helper::close()?>
</div>

<script type="text/javascript">
	php.hinttext = "<?=$this->lang->line('search_people_hinttext')?>";
</script>
<?=Html_helper::requireJS(array("message/msg_inbox"))?> 