/* *********************************************************
 * Graph custom code
 *  Code that draws custom objects in the graph.
 *   for topics, code can be reused from script.js.
 *
 * ******************************************************* */

define(function() {

	var labelType, useGradients, nativeTextSupport, animate;
	
	(function() {
	  var ua = navigator.userAgent,
	      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
	      typeOfCanvas = typeof HTMLCanvasElement,
	      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
	      textSupport = nativeCanvasSupport 
	        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
	  //I'm setting this based on the fact that ExCanvas provides text support for IE
	  //and that as of today iPhone/iPad current text support is lame
	  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
	  nativeTextSupport = labelType == 'Native';
	  useGradients = nativeCanvasSupport;
	  animate = !(iStuff || !nativeCanvasSupport);
	})();
	
	/*var Log = {
	  elem: false,
	  write: function(text){
	    if (!this.elem) 
	      this.elem = document.getElementById('log');
	    this.elem.innerHTML = text;
	    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
	  }
	};*/
	var parser = new Parseur();
	var network = parser.parse(source);
	json = [];
	nodes = network.nodes;
	edges = network.edges;
	for(var val in nodes)
	{
		var node = {};
		
		node.id = val;
		if (nodes[val].label)
		{
			node.name = nodes[val].label;
		}
		else
		{
			node.name = val;
		}
		node.data = {};
		if (nodes[val].link)
		{
			node.data.link = nodes[val].link;
		}
		/*if (nodes[val].kf)
		{
			node.data.$dim = nodes[val].kf * initvar.dim;
		}*/
		if (nodes[val].size)
		{
			koef = sizes[nodes[val].size];
	          if (!koef)
	          {
	               koef = 1;
	          }
	          node.data.$dim = initvar.dim * koef;
			node.data.fontSize = (initvar.fontSize * sizes[nodes[val].size])+'px';
		}
		
		if (nodes[val]['size-caption'])
		{	
			node.data.fontSize = (initvar.fontSize * sizes[nodes[val]['size-caption']])+'px';		
		}
		
		if (nodes[val]['size-node'])
		{	
			node.data.$dim = initvar.dim * sizes[nodes[val]['size-node']];
		}
		
		if (nodes[val].color)
		{
			node.data.$color = nodes[val].color;
			node.data.colorTag = nodes[val].color;
		}
		
		if (nodes[val]['color-node'])
		{
			node.data.$color = nodes[val]['color-node'];
			node.data.colorTag = initvar.captionTagColor;
		}
		if (nodes[val]['color-tag'])
		{
			node.data.colorTag = nodes[val]['color-tag'];
		}
		
		if (nodes[val]['color-caption'])
		{
			node.data.colorCaption = nodes[val]['color-caption'];
		}
		
		node.adjacencies = [];
		
		for(var val1 in edges[val])
		{
			var adj = {};
			adj.nodeTo = val1;
			adj.nodeFrom = val;
			adj.data = {};
			if (edges[val][val1].color)
			{
				adj.data.$color = edges[val][val1].color;
			}
			node.adjacencies.push(adj);
		}
		json.push(node);
	}
	
	
	function init(invar){
	    //init RGraph
	     rgraph = new $jit.RGraph({
	        //Where to append the visualization
	        injectInto: 'infovis',
	        'width': invar.width,  
	        'height':invar.height,
	        //Optional: create a background canvas that plots
	        //concentric circles.
	        background:{
	    	 numberOfCircles: initvar.numberOfCircles,
	    	 CanvasStyles: {
	            strokeStyle: '#555'            
	          }
	        },
	        //Add navigation capabilities:
	        //zooming by scrolling and panning.
	        Navigation: {
	          enable: true,
	          panning: true,
	          zooming: false
	        },
	        //Set Node and Edge styles.
	        Node: {
	        	overridable: true,  
	            color: invar.nodeColor,
	            dim: 10
	        },
	        
	        Edge: {
	          overridable: true,
	          color: invar.edgeColor,
	          lineWidth:1.5
	        },
	
	        Label: {  
	        		  overridable: true,  
	        		  type: 'HTML', //'SVG', 'Native'  
	        		  style: ' ',  
	        		  size: 20,  
	        		  family: 'sans-serif',  
	        		  textAlign: 'center',  
	        		  textBaseline: 'alphabetic',  
	        		  color: '#000'  
	        },  
	        Events:{
	        	enable: true,
	            type: 'Native',
	        	 onClick:function(node, eventInfo, e){
	        	if (node)
	        			rgraph.onClick(node.id, {onComplete:function(){}});        	        
	        	},
	        	 onMouseEnter: function(node, eventInfo, e){  
	        	      rgraph.canvas.getElement().style.cursor = 'pointer';
	        	    },
	        	onMouseLeave: function(node, eventInfo, e){  
	          	      rgraph.canvas.getElement().style.cursor = 'default';  
	          	    }
	        },
	        onBeforeCompute: function(node){
	        	 //Log.write("centering " + node.name + "...");
	            //Add the relation list in the right column.
	            //This list is taken from the data property of each JSON node.
	            //$jit.id('inner-details').innerHTML = node.data.relation;
	        	
	        },
	        
	        //Add the name of the node in the correponding label
	        //and a click handler to move the graph.
	        //This method is called once, on label creation.
	       
	        onCreateLabel: function(domElement, node){
	        	//console.log(rgraph.canvas.canvases[1].getCtx().plot());
	            domElement.innerHTML = node.name.substr(0,15);      
	            if (node.data.link)
	            {
	            	domElement.innerHTML = '<a href="'+node.data.link+'">'+domElement.innerHTML+'</a>';            	
	            }
	            
	            if (node.data.colorTag)
	            {            
	            	domElement.style.backgroundColor = node.data.colorTag;
	            }
	            else
	            {
	            	domElement.style.backgroundColor = initvar.captionTagColor;
	            }
	            
	            if (node.data.colorCaption)
	            {
	            	$(domElement).find('a').css('color',node.data.colorCaption);
	          	     domElement.style.color = node.data.colorCaption;
	            }
	            else
	            {
	            	$(domElement).find('a').css('color',initvar.captionColor);
	            	domElement.style.color = initvar.captionColor;
	            }
	            
	            if (node.data.fontSize)
	            {            
	            	domElement.style.fontSize = node.data.fontSize;
	            }
	            else
	            {
	            	domElement.style.fontSize = initvar.fontSize+'px';
	            }
	                                   
	            /*domElement.onclick = function(){
	                rgraph.onClick(node.id, {
	                    onComplete: function() {
	                        Log.write("done");
	                    }
	                });
	            };*/
	        }
	        
	        //Change some label dom properties.
	        //This method is called each time a label is plotted.
	        /*onPlaceLabel: function(domElement, node){
	            var style = domElement.style;
	            style.display = '';
	            domElement.innerHTML = node._depth;
	            /*if (node._depth <= 1) {
	               // style.fontSize = "0.8em";
	                style.color = invar.captionTagColor;
	            
	            } else if(node._depth == 2){
	                //style.fontSize = "0.7em";
	                style.color = invar.captionTagColorFar;
	            
	            } else {
	                style.display = 'none';
	            }
	
	            var left = parseInt(style.left);
	            var w = domElement.offsetWidth;
	            style.left = (left - w / 2) + 'px';
	        }*/
	    });
	    //load JSON data
	    rgraph.loadJSON(json);
	    //trigger small animation
	    rgraph.graph.eachNode(function(n) {
	      var pos = n.getPos();
	      pos.setc(-200, -200);
	    });
	    rgraph.compute('end');
	    rgraph.fx.animate({
	      modes:['polar'],
	      duration: 2000
	    });
	   
	    //end
	    //append information about the root relations in the right column
	    //$jit.id('inner-details').innerHTML = rgraph.graph.getNode(rgraph.root).data.relation;
	    $jit.id('infovis-bkcanvas').style.backgroundColor = invar.background;
	     
	}
	
	$(function(){
		m1 = Math.min(initvar.width,initvar.height);	
		k = Math.sqrt(750/m1); 
	     
	     critery = Math.max(initvar.treeLength*2, initvar.numberOfCircles);
	     maxVal = (Math.log(critery)/Math.log(1.17)*k)+10;
	     prevVal = maxVal-10;
	     
		function myscale(value)
		{
			step = Math.abs(value-prevVal);		
			if (value > prevVal)
			{
				scale = Math.pow(1.1, step);
			}
			else
			{
				scale = Math.pow(0.9090909,step);
			}
			rgraph.canvas.scale(scale,scale);
			prevVal = value;
		}
		
		$( "#slider" ).slider({
			range: "min",
			orientation: "vertical",
			value: prevVal,
			min: 1,
			max: maxVal,
			slide: function( event, ui ) {
				myscale(ui.value);			
			},
			change: function( event, ui ) {
				if (event.originalEvent == undefined)
				{
					myscale(ui.value);				
				}
			}
			
		});
		
		
	     $("#infovis").css({width:initvar.width, height:initvar.height});
	     
	     slider_height = 300 / 750 * initvar.height;
	     $( "#slider" ).css({height: slider_height, top: initvar.height-slider_height-10});
	
		$("#infovis").mousewheel(function(event, delta){
			event.stopPropagation();
			event.preventDefault();
			slider = $( "#slider" );
			max = slider.slider("option", "max");
			min = slider.slider("option", "min");
			
			if (delta>0 && max > slider.slider("option", "value") || (delta<0 && min < slider.slider("option", "value")))
			{
				slider.slider("option", "value", slider.slider("option", "value") + delta);
			}
		});
		
	    //For the handling of links inside the graph
	    $('#infovis #infovis-label .node a').live('click', function() {
	    	//console.log($(this).attr('href'));
	    	top.document.location.href = $(this).attr('href');
	    	return false;
	    });
		
		
		
	});

});