/* *********************************************************
 * PR Tab contents for interests (Not used)
 *
 * ******************************************************* */

define(['plugins/jquery.watermarkinput', 'jquery'], function(){

	// <![CDATA[	
	$(document).ready(function(){
		$('#submit').click(function(){var foundurl=$('#url').val(); // button clicked - parse the value to a var foundurl
		if(typeof(foundurl)!='undefined'){ // make sure that there is foundurl
		if(!isValidURL(foundurl)) // isValidURL is external function,which is at the bottom of the page. It checks if the found url looks like real url
			{return false;} // do nothing if the url is bad
			else
			{
			var page_id = php.segment2;
			$.post("/application/views/tools/test_url.php?url="+foundurl,function(alive){if(alive=='works' && $('.url').length==0){ // so here we use test_url.php ! That tests if the foundurl is alive by using ajax.There is also a small rule that checks if previous url has already loaded.
				$('#load').show(); // show loading image
				$.post("/application/views/tools/fetch.php?url="+foundurl+"&pageid="+page_id, { // make ajax request to the fetch.php with the foundurl
				}, function(response){ // ajax have returned a content
					$('#loader').html($(response).fadeIn('slow')); //show the ajax returned content
					$('#load').hide(); //content already loaded - no need of loading bar anymore

	});
	} else{alert('Sorry,the url you have typed does not exists !'); }
		}); 
		}   } 	});	 });

	
		// watermark input fields (you know the yellow hover- thats left from the 99 site example)
		jQuery(function($){
		   
		   $("#url").Watermark("http://");
		});
		jQuery(function($){

		    $("#url").Watermark("watermark","#369");
			
		});	
		function UseData(){
		   $.Watermark.HideAll();
		   $.Watermark.ShowAll();
		}

	
function isValidURL(url){
		var RegExp = /(\.){1}\w/;
	
		if(RegExp.test(url)){
			return true;
		}else{
			return false;
		}
	}

	// ]]>

});