<script charset="utf-8" type="text/javascript">
	//var xmlhttp =new XMLHttpRequest();
	//xmlhttp.onreadystatechange=function() {
	//	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			window.opener.document.getElementById("google_desc2").setAttribute('style','display:none');
			window.opener.document.getElementById("google_desc1").setAttribute('style','display:inline');
			var cn = window.opener.document.getElementById("link_google").className;
			window.opener.document.getElementById("link_google").className = cn.replace('disabled','');
			window.close();
	//	}
	//};
	//xmlhttp.open("GET","/gmail?load=t",true);
	//xmlhttp.send();

</script> 