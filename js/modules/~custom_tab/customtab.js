/* *********************************************************
 * Custom Tabs (not used now)
 * Logic for laoding content of cutom tabs 
 *
 * ******************************************************* */
 
define(['jquery'], function(){

	$(document).ready(function() {
	    $( "#sortable" ).sortable({
		handle : '.ui-state-default',
		update : function () {
		    //var order = $('#sortable').sortable('serialize');
		    order = [];
		    $('#sortable').children('div.component_tab').each(function(idx, elm) {
			order.push(elm.id.split('_')[1])
		    });
		    $.post('/sort_components/'+php.segment3+'/'+php.segment4+'/', { ci_csrf_token: $("input[name=ci_csrf_token]").val(),'order[]': order});
		    //$("#info").load("/index.php/custom_tab_controller/sort_components/<? echo $this->uri->segment(3) ?>/<? echo $this->uri->segment(4) ?>/"+order);
		}
	    });
	    //$( "#sortable" ).disableSelection();
	});

});