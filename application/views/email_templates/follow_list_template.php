<? $this->lang->load('email_templates/email_templates_views', LANGUAGE); ?>
<html><head></head><body>
	<div id="email_body">
	    <p>
	        <?=$this->lang->line('email_templates_views_hello_lexicon');?>
	    </p>
	    <p>
	        <a href="{user_link}"><img src="{thumbnail}">{name}</a> <?=$this->lang->line('email_templates_views_follow_list_msg');?>
	    </p>
		
	
	</div>
</body>
</html> 
