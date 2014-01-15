var div = document.createElement('div');
	div.style.position = "fixed";
	div.style.left = "50%";
	div.style.top = "50%";
	div.style.width = "200px";
	div.style.height = "60px";
	div.style.marginLeft = "-100px"; 
	div.style.marginTop = "-30px";
	div.style.background = "gray";
	div.style.padding = "10px";
	div.style.color = "white";
	div.innerHTML = '<h1>Fandrop is currently undergoing maintenance. Please try back in a bit</h1>';
	
document.body.appendChild(div);

console.info(div);