/* *********************************************************
 * Graph (additional logic)
 *  
 *
 * ******************************************************* */

define(["graph/script"], function(sc){

	console.log('graphy.js');
	console.log(php);
	if (!php.source) {
		alert('Tree is not defined');
	}
	//$(window).load(function() {
		//console.log(php);
		sc.init(php.initvar);			
	//});

	$(function(){

		$('.save-btn').click(function(){
			var img = getSnapshot($(this).attr('rel'));					
			$('#save-form input[type="hidden"]').val(img.src);
			$('#save-form').submit();
		});

		$('#save-all-btn').click(function(){
		
		var img = getBigSnapshot();				
		$('#save-form input[type="hidden"]').val(img.src);
			$('#save-form').submit();
		});

		$('#infovis-canvas').click(function(e){
				//console.log('dd');
				e.stopPropagation();
			});
		
	});					


});