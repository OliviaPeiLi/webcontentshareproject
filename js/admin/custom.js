$(document).ajaxComplete(function(e, xhr, opts) {
	if (!opts.dataType || opts.dataType == 'html' || opts.dataTypes && opts.dataTypes[0] == 'text' && opts.dataTypes[1] == 'html') {
		Administry.dateInput('.datepick');
	}
});
$(document).ready(function(){
	 $.extend({
		post: function(url, data, callback, type) {
			if (typeof data == 'object' && !data[php.csrf.name]) data[php.csrf.name] = php.csrf.hash; 
			$.ajax({
				'type': 'POST',
				'url': url,
				'dataType': type,
				'data': data,
				'success': callback
			});
		}
	 });
	 
	 $('.nyroModal').nyroModal();
	 	 
	/* setup navigation, content boxes, etc... */
	Administry.setup();
	$("#tabs, #tabs2").tabs();
	// validate signup form on keyup and submit
	var validator = $("#loginform").validate({
		rules: {
			username: "required",
			password: "required"
		},
		messages: {
			username: "<?=$this->lang->line('common_username_message');?>",
			password: "<?=$this->lang->line('common_password_message');?>"
		},
		// the errorPlacement has to take the layout into account
		errorPlacement: function(error, element) {
			error.insertAfter(element.parent().find('label:first'));
		},
		// set new class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("ok");
		}
	});
	Administry.dateInput('.datepick');
	// html textbox editor
	$('.wysiwyg').wysiwyg({
		controls: {
			strikeThrough : { visible : true },
			underline     : { visible : true },

			justifyLeft   : { visible : true },
			justifyCenter : { visible : true },
			justifyRight  : { visible : true },
			justifyFull   : { visible : true },

			indent  : { visible : true },
			outdent : { visible : true },

			subscript   : { visible : true },
			superscript : { visible : true },

			undo : { visible : true },
			redo : { visible : true },

			insertOrderedList    : { visible : true },
			insertUnorderedList  : { visible : true },
			insertHorizontalRule : { visible : true },

			h4: {
				  visible: true,
				  className: 'h4',
				  command: $.browser.msie ? 'formatBlock' : 'heading',
				  arguments: [$.browser.msie ? '<h4>' : 'h4'],
				  tags: ['h4'],
				  tooltip: 'Header 4'
			},
			h5: {
				  visible: true,
				  className: 'h5',
				  command: $.browser.msie ? 'formatBlock' : 'heading',
				  arguments: [$.browser.msie ? '<h5>' : 'h5'],
				  tags: ['h5'],
				  tooltip: 'Header 5'
			},
			h6: {
				  visible: true,
				  className: 'h6',
				  command: $.browser.msie ? 'formatBlock' : 'heading',
				  arguments: [$.browser.msie ? '<h6>' : 'h6'],
				  tags: ['h6'],
				  tooltip: 'Header 6'
			},

			cut   : { visible : true },
			copy  : { visible : true },
			paste : { visible : true },
			html  : { visible: true }
		}
	});
	
	// select all checkboxes functionality
	var select_all = $("#select_all");
	var select_rows = $(".select_row");
	
	select_all.change(function(){			
		var is_checked =select_all.attr('checked');
		if(is_checked){
			select_rows.attr('checked', true);
		}
		else{
			select_rows.attr('checked', false);
		}
	});
	select_rows.change(function(){
		var num_total = select_rows.length;
		var num_selected = $(".select_row:checked").length;	
		if(num_total == num_selected){
			select_all.attr('checked',true);
		}		
		else{
			select_all.attr('checked', false);
		}
	});
	
});

function SendForm(frm){
 	ShowLoader();
	$.ajax({
		type: "POST",
		url: $(frm).attr('action'),
		data:$(frm).serialize(),
		beforeSend: function(data){return Validate(frm)},
		success: function(data){ShowResults(data, frm)},
		error: function(data, status){ShowAjaxError(data)},
		dataType: "json"
	});
	return false;
 }

	function Validate(frm)
	{
	return;
	}

	function ShowResults(data, frm)
	{
		HideLoader();
		$("#message").html("");
		if(data.error){
			ShowError(data.error, data.container);
		}
		if(data.success){
			ShowSuccess(data.success, data.container);
		}
		if(data.redirect){
			window.location = data.redirect;
		}		
		if(data.refresh){
			location.reload(true);
		}
		if(data.back){
			history.back();
		}				
		if(data.callback){
			eval(data.callback);
		}	
		if(data.popup){
			$.nmData(data.popup	);
		}
	}

 function ShowError(message, container)
 {
 	if(container == null) container = "#message";
    $(container).hide().html('<div class="box box-error">'+message+'</div>').fadeIn(1000);
	HideLoader();
	InitObjects(container);
 }
 function ShowSuccess(message, container)
 {
 	if(container == null) container = "#message";
    $(container).hide().html('<div class="box box-info">'+message+'</div>').fadeIn(1000);
	InitObjects(container);
 }
 function ShowAjaxError(data)
 {
    alert("Ajax error occured: \nPage Status: " + data.status +"\nStatus Text: "+data.statusText);
	$("#debug").html(data.responseText);
	HideLoader();
 }
 function InitObjects(container)
 {	 	
	$("#debug").html('');  
 }

 function ShowLoader()
 {
 	var h = $(window).height();
	$("#loaderimg").css({"margin-top": h/2});
 	$("#loader").show();
 }
 function HideLoader()
 {
 	$("#loader").hide();
 }
 function confirmDelete(){
	if(confirm("Are you sure you want to delete?")) return true; else return false;
}
