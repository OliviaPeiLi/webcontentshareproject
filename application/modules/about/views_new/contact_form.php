<? $this->lang->load('about/footer', LANGUAGE); ?>
<div id="contact_form_result" style="display:none;">
</div>
<?=Form_Helper::open('/docontact_us', array('rel'=>'ajaxForm', 'id' => 'contact_us_form', 'class' => 'public', 'success' => $this->lang->line('footer_form_success_text')))?>
<?//=$this->session->flashdata('message')?>
    <ul>
        <li class="form_row">
            <div class="about_label inlinediv">
            <?=$this->lang->line('name');?>:
            </div>
            <div class="about_field inlinediv">
                <?=Form_Helper::input('name', Form_Helper::set_value('name'), array(
                    'id' => "contactus_name",
                    'class' => "contactus_field",
                    'maxlength' => '30',
                    'cols' => '200',
                    'rows' => '1',
                    'placeholder' => $this->lang->line('footer_contact_form_name_placeholder'),
                    'data-validate'=>"required|maxlength|specialchars",
                    'data-error-required' => 'Please type in your name',
                    'data-error-maxlength' => "Your name can be up to 30 characters",
                    'data-error-specialchars' => "No special characters are allowed."
                )); ?>
                <div class="error"></div>
            </div>
            <?// echo Form_Helper::form_error('name', '<div class="error">', '</div>'); ?>
        </li>
        <li class="form_row">
            <div class="about_label inlinediv">
            <?=$this->lang->line('email');?>:
            </div>
            <div class="about_field inlinediv">
                <?=Form_Helper::input('email', Form_Helper::set_value('email'), array(
                    'id' => "contactus_email", 
                    'size' => "50",
                    'class' => "contactus_field",
                    'placeholder' => $this->lang->line('footer_contact_form_email_placeholder'),
                    'cols' => '200',
                    'rows' => '1',
                    'data-validate'=>"required|email",
                    'data-error-email' => "Doesn't look like a valid email.",
                    'data-error-required' => 'An email is required!',
                ));?>
                <div class="error"></div>
            </div>
            <?// echo Form_Helper::form_error('email', '<div class="error">', '</div>'); ?>
        </li>
        <li class="form_row">
            <div class="about_label inlinediv">
            <?=$this->lang->line('footer_contact_form_message_lbl');?>:
            </div>
            <div class="about_field inlinediv">
                <?=Form_Helper::textarea('msg_body', Form_Helper::set_value('msg_body'), array(
                    'id' => 'contactus_body',
                    'placeholder' => $this->lang->line('footer_contact_form_message_text'),
                    'cols' => '200',
                    'rows' => '6',
                    'data-validate' => "required",
                    'data-error-required' => 'Type in your message'
                ))?>
                <div class="error"></div>
            </div>
            <?// echo Form_Helper::form_error('msg_body', '<div class="error">', '</div>'); ?>
        </li>
        <li class="form_submit">
            <div class="about_label inlinediv"> </div>
            <div class="about_field inlinediv">
                <?=Form_Helper::submit('submit', $this->lang->line('footer_contact_form_submit_btn'), array('id'=>"send_msg_btn", 'class'=>"blue_bg blueButton blue_bg_tall"))?>
            </div>
        </li>
    </ul>
<?=Form_Helper::close()?>
<?=Html_helper::requireJS(array("about/contact_us","common/formValidation"))?>
