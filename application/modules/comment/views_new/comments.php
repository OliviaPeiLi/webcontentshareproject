<?php $this->lang->load('comment/comment', LANGUAGE); ?>
<div class="newsfeed_entry_comments" style="<?=count($comments) ? '' : 'display:none'?>">

	<? $this->load->view('comment/comment', array('comment'=>$this->comment_model->sample()))?>
	
	<? foreach ($comments as $comment) { ?>
		<? $this->load->view('comment/comment', array('comment'=>$comment))?>
	<? } ?>
	<span class="num_comments" style="display:none"><?=count($comments)?></span>
</div>

<?php if ($this->user) { ?>
	<script type="text/javascript">
		// delete all comments
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
	    	while (ca[i].charAt(0)==' ') ca[i] = ca[i].substring(1,ca[i].length);
	        var name = ca[i].substring(0,ca[i].indexOf("="));
	        if (name.indexOf("comment_") === 0)	{
	        	console.warn('delete',name+"=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/");
	        	document.cookie = name+"=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";
	        }
	    }
	</script>
<?php } ?>
<?=Html_helper::requireJS(array("plugins/mentions","profile/badge"))?>
