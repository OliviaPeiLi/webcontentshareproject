define(['common/fd-scroll','common/autoscroll_new', 'jquery', 'jquery-ui'], function() {
	
	var self = '#lists .listManager_managerColumn';
	
	function init() {
		 $( ".listManager_listList" ).sortable({
			 axis: 'y',	
			 stop: function(e, ui) {
				 $.post('/manage_lists/resort_folders', $(this).sortable('serialize'), function(res) {
					 console.info(res);
				 });
			 }
		 });
		 $( ".listManager_listList" ).disableSelection();
	}
	
	if ($('.listManager_listList').length) {
		init();
	} else {
		$(function() { init(); });
	}
	
	return this;
});

