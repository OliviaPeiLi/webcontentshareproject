
var labelType, useGradients, nativeTextSupport, animate,direction = true;

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
var nodesCount = 0;
var images_arr = new Array;
var clicked = false;

function json_from_source(network)
{
var json = [];
var nodes = network.nodes;
var edges = network.edges;

for(var val in nodes)
{
	var node = {};
	node.data = {};
	
	node.id = val;	
	
	node.data.children = nodes[val].children-1;
	if (!node.data.children)
	{
		node.data.obtained = true;
	}
	
	if (nodes[val].real_id)
	{
		node.data.real_id = nodes[val].real_id;
	}
	else
	{
		node.data.real_id = node.id;
	}
	
	if (nodes[val].label)
	{
		node.name = nodes[val].label;		
	}
	else
	{
		node.name = '';
	}
	//node.name = 'aaa';
	
	//console.log(node);
	node.data.fullText = node.name;
	var node_name_length = node.name.length;
	node.name = node.name.substr(0,initvar.captionTruncateLimit);
	//console.log(node.data.fullText);
	//console.log(node_name_length);
	if (node_name_length > 15) {
		node.name = node.name+'...';
	}
	node.data.left = nodes[val].left;
	node.data.right = nodes[val].right;
	node.data.parent_id = nodes[val].parent_id;
	//node.data.pagecheck = nodes[val].pagecheck;

	if (nodes[val].link)
	{
		node.data.link = nodes[val].link;
	}
	
	if (nodes[val]['border-color'])
	{
		node.data.borderColor = nodes[val]['border-color'];
	}
	
	if (nodes[val]['border-width'])
	{
		node.data.borderWidth = nodes[val]['border-width'];
	}
	
	if (nodes[val]['width'])
	{
		node.data.initialWidth = nodes[val]['width'];
	}
	else
	{
		node.data.initialWidth = initvar.nodeWidth;
	}
	
	if (nodes[val]['height'])
	{
		node.data.initialHeight = nodes[val]['height'];
	}
	else
	{
		node.data.initialHeight = initvar.nodeHeight;
	}

	if (nodes[val].shape)
	{
		node.data.shape = nodes[val].shape;
	}
	else
	{
		node.data.shape = 'rounded';
	}
	
	node.data.bounce = {x:(Math.random()*50)-25,y:(Math.random()*50)-25};
	
	node.data.adlink = {};
	if (nodes[val]['adlink-text'])
	{
		node.data.adlink.text = 'Add to favorites';//nodes[val]['adlink-text'];
		node.data.adlink.url = nodes[val]['adlink-url'];
	}
	
	
	
	if (nodes[val].img)
	{
		node.data.img = nodes[val].img;
		if (typeof images_arr[node.id] == 'undefined')
		{
			var img = new Image();		
			img.src = nodes[val].img;			
			images_arr[node.id]= img;
		}
	}
	

	if (nodes[val]['size-caption'])
	{	
		node.data['$label-size'] = (initvar.fontSize * sizes[nodes[val]['size-caption']]);		
	}
			
	if (nodes[val]['color-caption'])
	{
		node.data['$label-color'] = nodes[val]['color-caption'];
	}
	
	node.data.duplicate_id =nodes[val]['duplicate_id'];
	
	node.data.fog = 0;
	node.data.postback = {};
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
		return json;
}

function init(invar){
  
	$jit.Canvas.Background.Circles.implement({
		'plot': function(base) {   	
		
	      var canvas = base.canvas,
	          ctx = base.getCtx(),
	          conf = this.config,
	          styles = conf.CanvasStyles,
	          tx = base.translateOffsetX / base.scaleOffsetX,
	      	ty = base.translateOffsetY  / base.scaleOffsetY;	     
	      	var radgrad = ctx.createRadialGradient(-tx,-ty,1,-tx,-ty,base.size.width/2);
	      //var radgrad = ctx.createRadialGradient(0,0,1,0,0,450);
	        radgrad.addColorStop(0, 'rgb(240,240,240)');	        
	        radgrad.addColorStop(1, 'rgb(200,200,200)');
	        //radgrad.addColorStop(1, 'rgba(0,0,0,1)');
	      	ctx.fillStyle = radgrad;
	    	ctx.fillRect(-tx-(base.size.width/2)/ base.scaleOffsetX,-ty-(base.size.height/2)/ base.scaleOffsetY,base.size.width/ base.scaleOffsetX,base.size.height/ base.scaleOffsetY);
	      	
		}		
	});
	
	  $jit.ForceDirected.Label.Native.implement({
	  
		  'renderLabel': function(canvas, node, controller) {	  
	      var ctx = canvas.getCtx();
	      var pos = node.pos.getc(true);
	      var showAdlink = node.data.showAdlink?true:false;
	      //var showlink = node.data.showlink?true:false; //LINK_ALWAYS
	      if (node.data.adlink.text && showAdlink)
	      {
	    	  ctx.fillText(node.data.adlink.text, pos.x, pos.y - node.getData("height")/2-9);
	    	  var textLength = ctx.measureText(node.data.adlink.text).width;
	    	  if (node.data.adlink.url)
		      {
		    	  ctx.beginPath();
		    	  ctx.lineWidth = 1;
		    	  ctx.strokeStyle="#000";
		    	  ctx.moveTo(pos.x-(textLength/2), pos.y - node.getData("height")/2-7);
		    	  ctx.lineTo(pos.x+ (textLength/2), pos.y - node.getData("height")/2-7);
		    	  ctx.closePath();
		    	  ctx.stroke();
		      }
	      }
	      
	      
	      //if (node.data.link && showlink) //LINK_ALWAYS
	      //{
	      		//console.log('aaa');
	    	  ctx.fillText(node.name, pos.x, pos.y + (node.getData("height")/2+ node.getLabelData('size'))+2);
		      var textLength = ctx.measureText(node.name).width;	 
		      if (node.data.link) {    //LINK_ALWAYS
	    	  ctx.beginPath();
	    	  ctx.lineWidth = 1;
	    	  ctx.strokeStyle="#000";
	    	  ctx.moveTo(pos.x-(textLength/2), pos.y + (node.getData("height")/2+ node.getLabelData('size')+4));
	    	  ctx.lineTo(pos.x+ (textLength/2), pos.y + (node.getData("height")/2+ node.getLabelData('size')+4));
	    	  ctx.closePath();
	    	  //ctx.stroke();
	      }
	      
	     
		  
	    }
	  });
	  $jit.ForceDirected.Plot.NodeTypes.implement({  
		  'image': {  		
		    'render': function(node, canvas) {		  		
		  		//var img = new Image();   // Создаём новый объект Image
		  		var img = images_arr[node.id];
		  		//console.log(node.id);
		  		
		  		/*if (node.data.img && !img.src)
		  		{	  			
			  		img.src = node.data.img;	
		  		}
		  		else
		  		{
		  			img.src = "noimage.png";	
		  		}
		  		*/
		  		var that = this;
		  		function redraw(node, canvas)
		  		{
		  			function drawRound()
		  			{		  		
		  				//console.log('bbb');		
		  				that.nodeHelper.circle.render('stroke', pos, dWidth/2,canvas);
		  			}
		  			
		  			function drawTriangle()
		  			{		  	
		  				//console.log('triangle');			
		  				that.nodeHelper.triangle.render('stroke', pos, dWidth/2,canvas);		  					  				
		  			}
		  			function drawTriangleDown()
		  			{		  		
		  				//console.log('triangle_down');		
		  				that.nodeHelper.triangle_down.render('stroke', pos, dWidth/2,canvas);		  					  				
		  			}

		  			
		  			function drawSquare(dc)
		  			{
		  				ctx.beginPath();
		  				br = br-dc;
				  		ctx.moveTo(pos.x-dWidth/2-dc,pos.y);
				  		ctx.lineTo(pos.x-dWidth/2-dc,pos.y-(dHeight/2-br));
				  		ctx.quadraticCurveTo(pos.x-dWidth/2-dc,pos.y-dHeight/2,pos.x-(dWidth/2-br),pos.y-dHeight/2-dc);
				  		ctx.lineTo(pos.x+(dWidth/2-br),pos.y-dHeight/2-dc);
				  		ctx.quadraticCurveTo(pos.x+dWidth/2,pos.y-dHeight/2-dc,pos.x+dWidth/2+dc,pos.y-(dHeight/2-br));
				  		ctx.lineTo(pos.x+dWidth/2+dc,pos.y+(dHeight/2-br));
				  	
				  		if (node.data.shape == 'rounded')
				  		{
				  			ctx.quadraticCurveTo(pos.x+dWidth/2+dc,pos.y+dHeight/2,pos.x+(dWidth/2-br),pos.y+dHeight/2+dc);		  			
				  		}
				  		else
				  		{		  			
				  			ctx.lineTo(pos.x+dWidth/2+dc,pos.y+dHeight/2+dc);
				  		}
				  		
				  		ctx.lineTo(pos.x-(dWidth/2-br),pos.y+dHeight/2+dc);
				  		ctx.quadraticCurveTo(pos.x-dWidth/2,pos.y+dHeight/2+dc,pos.x-dWidth/2-dc,pos.y+(dHeight/2-br));
				  		ctx.lineTo(pos.x-dWidth/2-dc,pos.y);
				  		ctx.closePath();			  			
				  		/*if (node.data.borderWidth)
				  		{
				  			ctx.lineWidth = node.data.borderWidth;
				  		}
				  		if (node.data.borderColor)
				  		{
				  			
				  			ctx.strokeStyle = node.data.borderColor;
				  			ctx.stroke();
				  		}*/				  		
		  			}
			  		var ctx = canvas.getCtx();
			  		
			  		var pos = node.getPos();
			  		if (img.width < img.height)
			  		{
			  			
			  			var sx = 0,
			  				sy = img.height/2 - img.width/2,
			  				sWidth = img.width,
			  				sHeight = img.width;			  				
			  		}
			  		else
			  		{
			  			var sy = 0,
		  				sx = img.width/2 - img.height/2 ,
		  				sWidth = img.height,
		  				sHeight = img.height;		  				
			  		}
			  		
			  	var	dWidth = Math.round(node.getData('width')),
	  				dHeight = Math.round(node.getData('height')),
	  				dx = pos.x-dWidth/2,
	  				dy = pos.y-dHeight/2;	  	
			  		var br = 15;
			  		
			  		//console.log(node.data.shape);
			  		ctx.save();
			  		if (node.data.shape == 'round')
			  		{
			  			drawRound();
			  		}
			  		else if (node.data.shape == 'triangle')
			  		{
			  			drawTriangle();
			  		}
					else if (node.data.shape == 'triangle_down')
			  		{
			  			drawTriangleDown();
			  		}
			  		else
			  		{
				  		drawSquare(0);
			  		}			  		
			  		ctx.clip();
			  		ctx.drawImage(img, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight);
			  		//var showlink = node.data.showlink?true:false; //LINK_ALWAYS
			  		//if (showlink ) //LINK_ALWAYS
			  		//{
			  			ctx.fillStyle = 'rgba(0,0,0,0.5)';
			  			ctx.fillRect(pos.x-node.getData("width")/2,pos.y+node.getData("width")/3-5,node.getData("width"),21);
			  			ctx.fillStyle = 'rgb(255,255,255)';
			  			ctx.fillText(node.data.children, pos.x, pos.y + node.getData("height")/3+10);				  		
			  		//}

			  		if (node.data.fog)
			  		{
			  			ctx.fillStyle = "rgba(255,255,255,"+node.data.fog+")";
			  			ctx.fill();				  	
			  		}			  		
			  		
			  		
			  		/*ctx.lineWidth = 5;
			  		ctx.strokeStyle = 'rgba(0,0,0,0.5)';
		  			ctx.stroke();*/
		  			//console.log(node.data.shape);
			  		ctx.restore();	
			  		if (navigator.userAgent.match('Opera'))
			  		{
			  			ctx.restore();
			  		}
			  		ctx.lineWidth = 2;
			  		ctx.strokeStyle = '#000';
			  		if (node.data.shape == 'round')
			  		{			  			
				  		drawRound(); 		
			  		}
			  		else if (node.data.shape == 'triangle')
			  		{
			  			drawTriangle();
			  		}
			  		else if (node.data.shape == 'triangle_down')
			  		{
			  			drawTriangleDown();
			  		}
			  		else
			  		{
				  		drawSquare(0);
				  		ctx.stroke();				  		
			  		}
			  		
			  		if (typeof node.data.obtained == 'undefined')
			  		{
			  			//console.log(node.data.shape);
			  			ctx.lineWidth=initvar.moreWidth;
			  	      	ctx.strokeStyle = initvar.moreColor;
			  			if (node.data.shape == 'round')
				  		{			  			
			  	      	
				  		that.nodeHelper.circle.render('stroke', pos, dWidth/2+3,canvas);
				  		}
				  		else if (node.data.shape == 'triangle')
				  		{
			  				that.nodeHelper.triangle.render('stroke', pos, dWidth/2+3,canvas);
				  		}
				  		else if (node.data.shape == 'triangle_down')
				  		{
			  				that.nodeHelper.triangle_down.render('stroke', pos, dWidth/2+3,canvas);
				  		}

			  			else
			  			{
			  				drawSquare(3);
			  				ctx.stroke();
			  			}
			  		}
		  		}
		  		redraw(node, canvas);
		  		img.onload = function(){redraw(node, canvas);};
		  		
		      },  
		    'contains': function(node, pos) {
		    	var npos = node.getPos();
		    	var nheight = Math.round(node.getData('height'));
		  		var nwidth = Math.round(node.getData('width'));	
		  		var labelArea = 0;
		  		var labelAreaTop = 0;
		  		var addWidth = 0;
		  		if (node.data.link)
		  		{
		  			labelArea = node.getLabelData('size')+node.getLabelData('data').addHeight;
		  			addWidth = node.getLabelData('data').addWidth;
		  		}
		  		
		  		if (node.data.adlink.url)
		  		{
		  			labelAreaTop = node.getLabelData('size')+node.getLabelData('data').addHeight;
		  		}
		  		
		    	if (pos.x>=npos.x-nwidth/2-addWidth && pos.x<=npos.x+nwidth/2+addWidth && pos.y>=npos.y-nheight/2-labelAreaTop && pos.y<=npos.y+nheight/2+labelArea) return true;
		    	else return false;
		    	 //return this.nodeHelper.circle.contains(node.getPos(), pos, 15); //true  
		      
		    }  
		  }  
		});  
	  
	  $jit.ForceDirected.Plot.implement({ 
		   plot: function(opt, animating) {
		     var viz = this.viz, 
		         aGraph = viz.graph, 
		         canvas = viz.canvas, 
		         id = viz.root, 
		         that = this, 
		         ctx = canvas.getCtx(), 
		         min = Math.min,
		         opt = opt || this.viz.controller;
		     
		     opt.clearCanvas && canvas.clear();
		       
		     var root = aGraph.getNode(id);
		     if(!root) return;
		     
		     var T = !!root.visited;
		     for (var i=0;i<rg.graph.a_edges.length;i++)
	         {
	          that.plotLine(rg.graph.a_edges[i],rg.canvas,animating);
	         }
		     aGraph.eachNode(function(node) {
		       var nodeAlpha = node.getData('alpha');
		       node.eachAdjacency(function(adj) {
		         var nodeTo = adj.nodeTo;
		         if(!!nodeTo.visited === T && node.drawn && nodeTo.drawn) {
		           !animating && opt.onBeforePlotLine(adj);
		           that.plotLine(adj, canvas, animating);		           		          
		           !animating && opt.onAfterPlotLine(adj);
		         }
		       });		    
		       if(node.drawn) {
		         !animating && opt.onBeforePlotNode(node);
		         that.plotNode(node, canvas, animating);
		         !animating && opt.onAfterPlotNode(node);
		       }
		       if(!that.labelsHidden && opt.withLabels) {
		         if(node.drawn && nodeAlpha >= 0.95) {
		           that.labels.plotLabel(canvas, node, opt);
		         } else {
		           that.labels.hideLabel(node, false);
		         }
		       }
		       node.visited = !T;
		     });
		    }		  
	  });
	  
    //init RGraph	  
     rg = new $jit.ForceDirected({
        //Where to append the visualization
        injectInto: 'infovis',
        'width': invar.width,  
        'height':invar.height,
        startTemp:10,
        duration: 2500,  
        fps: 35,  
        transition: $jit.Trans.Back.easeOut,
        levelDistance:130,
        iterations: 250,
        
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
          panning: 'avoid nodes',          
          zooming: 20
        },
        //Set Node and Edge styles.
        Node: {
        	overridable: true,  
            //color: invar.nodeColor,
            width:initvar.nodeWidth,
            height:initvar.nodeHeight,//initvar.dim
            type: 'image',
            CanvasStyles: {
                fillStyle: '#555'                        
              }            
        },
        
        Edge: {
          overridable: true,
          color: initvar.edgeColor,
          lineWidth:1          
        },

        Label: {  
        		  overridable: true,  
        		  color: '#000',
        		  type: 'Native', //'SVG', 'Native'  
        		  style: ' ',  
        		  size: 15,  
        		  family: 'sans-serif',  
        		  textAlign: 'center',  
        		  textBaseline: 'alphabetic',
        		  data: {
        				addHeight:initvar.labelAddHeight,
        				addWidth:initvar.labelAddWidth
        				}
        },          
        Events:{
        	enable: true,
            type: 'Native',
            
        	onClick:function(node, eventInfo, e){         	
        
        	var epos = eventInfo.getPos();
        	
        	 if (clicked) 
        	 {
        		 if (!node) 
        		 {
           			 var base = rg.canvas;
        			var zoomsteps  = 15; 
           			var tx = epos.x;
           			var ty = epos.y;
           			$( "#slider" ).everyTime(25,function(){           				
           				if (base.scaleOffsetX < 3 && base.scaleOffsetY < 3)
           				{
           					base.translate((-base.translateOffsetX-tx)/zoomsteps,(-base.translateOffsetY-ty)/zoomsteps);
           				}
           				$( "#slider" ).slider("option", "value", $( "#slider" ).slider("option", "value") + 1);           					
           				},zoomsteps);
           			
           			
        			 return;
        		 }
        		 
        		if (node.data.link) 
        		{
        			        		
	        		if (typeof window.parent != 'undefined')
	             	window.parent.location = node.data.link;
	        		else if (typeof window.top != 'undefined')
        			window.top.location = node.data.link;	
	             	else
	             	window.location = node.data.link;
        		}
      		  } 
        	 else
        	 { 
      		    clicked = true; 
      		    timer = setTimeout(function() { clicked = false; }, 500); 
      		 } 
        	
        	if (!node) return;
        	var npos = node.getPos();        	
        	var nheight = Math.round(node.getData('height'));
        	
        	
        	if (npos.y-nheight/2 > epos.y)
        	{
        		alert('node'+ node.id+ ' clicked');
        		
        		return false;
        	}
        	else if (npos.y+nheight/2 < epos.y && epos.y < npos.y+nheight/2+node.getLabelData('size')+node.getLabelData('data').addHeight) 
        	{
    			
        		if (typeof window.parent != 'undefined')
        		window.parent.location = node.data.link;
        		else if (typeof window.top != 'undefined')
        		window.top.location = node.data.link;	
        		else
        		window.location = node.data.link;
        		//nodesFold(node);
        	}
        	else 
        	{
        		get_subtree(node);
        	}
        	
        	},
        	//Update node positions when dragged  
        	onDragStart:function (node, eventInfo, e){     
        		
        		if (rg.root == node.id && initvar.disableRootMove) return;
        		if (!rg.fx.animation.check())
        		{
        			rg.fx.animation.pause();
        		}
        		node.data.dbtn = e.button;
        	},        	
        	onDragCancel:function (node, eventInfo, e){
        		
        	},
        	onDragEnd:function (node, eventInfo, e){
        		if (rg.root == node.id && initvar.disableRootMove) return;
        		var pos = eventInfo.getPos();        		
        		node.setPos(new $jit.Complex(pos.x,pos.y),'end');        		
        		 var dx,dy;
                 dx = pos.x - node.pos.x ;
                 dy = pos.y - node.pos.y ;
	        		node.getSubnodes().forEach(function(n){
	                 	if (n.id == node.id) return;
	                 	var np = n.getPos();
	                 	n.setPos(new $jit.Complex(np.x+dx, np.y+dy),'end');                 	
	                 });                 
                rg.fx.animate(rg.fx.animation.opt);    		
        	},        	
            onDragMove: function(node, eventInfo, e) {
        		if (rg.root == node.id && initvar.disableRootMove) return;
                var pos = eventInfo.getPos();
                var dx,dy;
                dx = pos.x - node.pos.x ;
                dy = pos.y - node.pos.y ;                
                node.pos.setc(pos.x, pos.y);
	                node.getSubnodes().forEach(function(n){
	                	if (n.id == node.id) return;
	                	var np = n.getPos();
	                	n.pos.setc(np.x+dx, np.y+dy);
	                	
	                });
                rg.plot(); 
            },  
            //Implement the same handler for touchscreens  
            onTouchMove: function(node, eventInfo, e) {  
              $jit.util.event.stop(e); //stop default touchmove event  
              this.onDragMove(node, eventInfo, e);  
            },  
            onMouseMove: function(node, eventInfo, e){
            	
            	if(node)
            	{
	            	var npos = node.getPos();
	            	var epos = eventInfo.getPos();
	            	var nheight = Math.round(node.getData('height'));
	        	/* 	if (npos.y+nheight/2 <= epos.y)
	            	{
	        	 		rg.canvas.getElement().style.cursor = 'pointer';
	        	 		//node.name = node.data.fullText;	        	 	
	            	}
	        	 	else if (npos.y-nheight/2 > epos.y && npos.y-nheight/2-node.getLabelData('size') < epos.y)
	            	{
	        	 		rg.canvas.getElement().style.cursor = 'pointer';
	        	 		//node.name = node.data.fullText;	        	 	
	            	}
	        	 	else
	        	 	{
	        	 		rg.canvas.getElement().style.cursor = '';
	        	 		//node.name = node.name.substr(0,initvar.captionTruncateLimit);
	        	 	}
	        	 	*/
	        	 	//	if (rg.fx.animation.check())rg.plot();
	        	 	//rg.fx.plotNode(node,rg.canvas);
	            	
	            	if (npos.y+nheight/2+node.getLabelData('size')+node.getLabelData('data').addHeight > epos.y )
	            	{
	        	 		rg.canvas.getElement().style.cursor = 'pointer';	        	 		        	 
	            	}
	        	 	else
	        	 	{
	        	 		rg.canvas.getElement().style.cursor = '';
	        	 	}
	        	 	
	        		
            	}
            },
        	onMouseEnter: function(node, eventInfo, e){
            	
            	//Bring hovered node to foreground
            	var nd = rg.graph.nodes[node.id];
            	delete rg.graph.nodes[node.id];
            	rg.graph.nodes[nd.id] = nd;
            	
            	node.data.showAdlink = true;
            	//node.data.showlink = true; //LINK_ALWAYS

            	var npos = node.getPos();
            	var epos = eventInfo.getPos();
            	var nheight = Math.round(node.getData('height'));
            	rg.canvas.getElement().style.cursor = 'pointer';
            	/*if (npos.y+nheight/2 <= epos.y)
            	{
        	 		rg.canvas.getElement().style.cursor = 'pointer';	        	 	
            	}
        	 	else
        	 	{
        	 		rg.canvas.getElement().style.cursor = '';

        	 	}*/
        	     //Enlarge            	            
        	     node.setData('width',initvar.imageDelayZoomSize,'end');
     			 node.setData('height',initvar.imageDelayZoomSize,'end');
     			 node.name = node.data.fullText;
     			 /*
	     		if (node.data.link) {    //LINK_ALWAYS
	     			var ctx = rg.canvas.getCtx();
		    	  ctx.beginPath();
		    	  ctx.lineWidth = 1;
		    	  ctx.strokeStyle="#000";
		    	  ctx.moveTo(pos.x-(textLength/2), pos.y + (node.getData("height")/2+ node.getLabelData('size')+4));
		    	  ctx.lineTo(pos.x+ (textLength/2), pos.y + (node.getData("height")/2+ node.getLabelData('size')+4));
		    	  ctx.closePath();
		    	  ctx.stroke();
		      	}
		      	*/
	
     			       
     			var anim = rg.fx.animation;
            	if (!anim.check())
            	{		
            			//anim.pause();
            			anim.stopTimer();
            			anim.opt.modes.push('node-property:width:height');            		
            			//anim.opt.duration = (anim.opt.duration + 1000)/2;
            			//anim.resume();
                		rg.animate(anim.opt);
                		
            	}
            	else
            	{
	     			 rg.animate( {  
	     		        modes: ['node-property:width:height'],  
	     		        duration: 1000,  
	     		        transition: $jit.Trans.Cubic.easeOut
	     		      });
            	}
     			
        	 },
        	onMouseLeave: function(node, eventInfo, e){  
        		//node.data.showAdlink = false;
        		//node.data.showlink = false;
          	    rg.canvas.getElement().style.cursor = '';  
          	  
          	     
          		node.setData('width',node.data.initialWidth,'end');
            	node.setData('height',node.data.initialHeight,'end');
            	//console.log(node);
            	var node_name_length = node.name.length;
            	//console.log(node_name_length);
            	node.name = node.name.substr(0,initvar.captionTruncateLimit);
            	if (node_name_length > 15) {
            		node.name = node.name+'...';
            	}
            	
            	var anim = rg.fx.animation;
            	if (!anim.check())
            	{		
            		anim.stopTimer();
            		anim.opt.modes.push('node-property:width:height');
            		//anim.opt.duration = (anim.opt.duration + 1000)/2;
            		rg.animate(anim.opt);
            		
            	}
            	else
            	{
	          	    rg.animate( {  
	   		        modes: ['node-property:width:height'],  
	   		        duration: 1000,  
	   		        transition: $jit.Trans.Cubic.easeOut,
	   		        onComplete: function(){}
	          	    });
            	}
 			 
            },
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
	            if (initvar.captionBgColor)
	            {
	            	$(domElement).find('a').css('color',initvar.captionBgColor);
	            	domElement.style.backgroundColor = initvar.captionBgColor;
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
	         
          	
        }
        
       
    });
    //load JSON data
     rg.loadJSON(json_from_source(network));
     rg.graph.nc = 1;
     rg.graph.nextZoomAt  = initvar.zoomEveryNodes;
     rg.graph.a_edges = new Array();
     //disable red circle on root node
     //rg.graph.getNode(rg.root).data.obtained = true;
     rg.config.startIterations = rg.config.iterations;
     //rg.refresh();
     rg.graph.eachNode(function(n) {  
    	  var pos = n.getPos();  
    	  pos.setc(0, 0);  
    	});  
    	rg.compute('end');  
    	rg.fx.animate({  
    	  modes:['linear'],  
    	  duration: 2000  
    	});  
    	for(var i in init_postback)
		{
		   	var n  = rg.graph.getNode(init_postback[i].id);
		   	if (typeof n == "undefined") continue;
		   	n.data.postback = init_postback[i];
	    }
    //$jit.id('infovis-bkcanvas').style.backgroundColor = initvar.background;
     
}

$(function(){
	
    	 maxVal = initvar.zoomInSteps+initvar.zoomOutSteps+1;
    	 initialScale = prevVal = initvar.zoomOutSteps+1;
     
     
	function myscale(value)
	{
		step = value-prevVal;			
		scale = Math.pow(initvar.scaleMultiplier, step);	    
		rg.canvas.scale(scale,scale);
		
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
	
	
	$('#zoomer #minus').mousedown(function() {
		$( "#slider" ).everyTime(50,'bt',function(){$( "#slider" ).slider("option", "value", $( "#slider" ).slider("option", "value") - 1)});
	});
	$('#zoomer #plus').mousedown(function() {
		$( "#slider" ).everyTime(50,'bt',function(){$( "#slider" ).slider("option", "value", $( "#slider" ).slider("option", "value") + 1)});
	});
	$('body').mouseup(function(){$( "#slider" ).stopTime('bt');});
	
     $("#infovis").css({width:initvar.width, height:initvar.height});
     
     slider_height = 300 / 750 * initvar.height;
     $( "#slider" ).css({height: slider_height});

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
		
});

function live(direction)
{	
	if (!rg.fx.animation.check())
	{	
		//return;
		rg.fx.animation.stopTimer();			
	}
	if (!direction)
	{
	rg.graph.eachNode(function(node) {
			var pos = node.getPos().getc();
			node.setPos(new $jit.Complex(pos.x+node.data.bounce.x,pos.y+node.data.bounce.y), 'end');			
			//node.setPos(new $jit.Complex(pos.x+25,pos.y-25), 'end');
		});
		var trans = $jit.Trans.Sine.easeInOut;
	}
	else
	{
		rg.graph.eachNode(function(node) {
			var p =node.getPos('current');
			node.setPos(node.getPos('start'), 'end');			
		});
		var trans = $jit.Trans.Sine.easeInOut;
	}
	      rg.fx.animate( {  
	        modes: ['linear','node-property:width:height'],  
	        duration: 4000,  
	        transition: trans	        
	      });  
}


function get_subtree(node)
{
	//alert(source.search('<--------->'));
	if (typeof node.data.obtained == 'undefined')
	{						
		if (typeof node.ajaxOffset == 'undefined')
		{
			node.ajaxOffset = 0;
		}		
		/*if (node.id.search('more')>=0)
		{			
			var n = rg.graph.getNode(node.id.replace('_more',''));
			node.ajaxOffset = n.ajaxOffset;
		}*/
		
		if(node.data.shape == 'round')
		{
			var node_type = 'user';
		}
		else if(node.data.shape == 'square')
		{
			var node_type = 'page';
		}
		else
		{
            var node_type = 'topic';
		}
/*		
		var overhead = source.substr(source.search('<--------->'));
		source = source.replace(overhead,'');
		overhead = overhead.replace('<--------->','');
		overhead = $.parseJSON(overhead);
*/		
		var csrf_token = $('input[name=ci_csrf_token]').val();
		var data = {ci_csrf_token:csrf_token,postback:node.data.postback,parent_id:node.data.parent_id,node_left:node.data.left,node_right:node.data.right,id:node.data.real_id,type:node_type,depth:initvar.ajaxFetchLevels,length:initvar.ajaxSubtreeMaxLength,offset:node.ajaxOffset};
		node.setCanvasStyle('shadowBlur', 30,'end');
		node.ajaxOffset = node.ajaxOffset+initvar.ajaxSubtreeMaxLength;
		node.data.fog = 0.8;
		rg.plot();
		$('#loading-div').show();
		$.post('/get_more_graph',data,function(source){								
				var parser = new Parseur();
				
				var overhead = source.substr(source.search('<--------->'));
				source = source.replace(overhead,'');
				overhead = overhead.replace('<--------->','');
				overhead = $.parseJSON(overhead);
				
				node.data.children = overhead.nodes_left;
				if (!overhead.nodes_left)
				{
					
					node.data.obtained = true;
					if (node.id.search('more')<0)
					{
						rg.graph.removeNode(node.id+'_more');
					}
					else
					{												
						n.obtained = true;
						rg.graph.removeNode(node.id);
					}
					
				}
				
				
				//var nodes_left = source.substr(source.search('nodes_left'));
/*				
				var nodes_left = source.substr(source.search('nodes_left'), source.search('pagecheck')-source.search('nodes_left'));
				var pagecheck = source.substr(source.search('pagecheck'), source.search('usercheck')-source.search('pagecheck'));
				var usercheck = source.substr(source.search('usercheck'));
				
				var nodes_left_int = parseInt(nodes_left.replace('nodes_left:',''));
				source = source.replace(nodes_left,'');
				node.data.children = nodes_left_int;
				
				var pagecheck_int = parseInt(pagecheck.replace('pagecheck:',''));
				source = source.replace(pagecheck,'');
				node.data.pagecheck = pagecheck_int;
				
				var usercheck_int = parseInt(usercheck.replace('usercheck:',''));
				source = source.replace(usercheck,'');
				node.data.usercheck = usercheck_int;
				
				if (!nodes_left_int)
				{
					
					//node.data.obtained = true;
					if (node.id.search('more')<0)
					{
						rg.graph.removeNode(node.id+'_more');
					}
					else
					{												
						n.obtained = true;
						rg.graph.removeNode(node.id);
					}
					
				}				
*/				
				if (node.id.search('more')>=0)
				{	
					n.ajaxOffset = node.ajaxOffset;
				}
				
				
				var network = parser.parse(source);				
				var json = json_from_source(network);
				$('#loading-div').hide();
				node.data.fog = 0;
				rg.plot();
				
				if (json.length < 2) return;

				if  (!rg.fx.animation.check())
				{
					rg.fx.animation.stopTimer();
				}
				//	$(document).stopTime();
				direction = true;
				
				rg.op.sum(json, {  
					  type: 'fade:fandrop',  
					  duration: 2500 ,
					  hideLabels: false,
					  transition: $jit.Trans.Back.easeOut,
					  onComplete: function(){
					
						//$(document).oneTime(0,function(){live(direction=!direction)});
						//$(document).everyTime(4000,function(){live(direction=!direction)});
					  }	
					});  					
				var nc=0;
			    for(var i in rg.graph.nodes)
			    {
			    	nc++;
			    }
			    rg.graph.nc = nc;
			    if (nc >= rg.graph.nextZoomAt)
			    {
			    	rg.graph.nextZoomAt += initvar.zoomEveryNodes;
			    	$( "#slider" ).everyTime(25,function(){$( "#slider" ).slider("option", "value", $( "#slider" ).slider("option", "value") - 1)},2);
			    }
			    if (rg.config.startIterations - Math.pow(rg.graph.nc,1.2) > 50)
			    {
			    	rg.config.iterations  = rg.config.startIterations - parseInt(Math.pow(rg.graph.nc,1.2));
			    }		
			    for (var i in json)
				{				
			    	var elem =  json[i];	
			    	rg.graph.eachNode(function(n){
			    		if (n.id == elem.id) return;
						if (n.data.duplicate_id == elem.data.duplicate_id && n.data.duplicate_id != 'a' && n.data.shape != 'round')
						{
							rg.graph.a_edges.push(new $jit.Graph.Adjacence(rg.graph.getNode(n.id),rg.graph.getNode(elem.id),{$color:initvar.adEdgeColor,$lineWidth: initvar.adEdgeWidth},rg.graph.Edge));
						}
			    	});
			    }			    			    			  			   			  
			    
			    for(var i in overhead.postback)
			    {
			    	var n  = rg.graph.getNode(overhead.postback[i].id);
			    	if (typeof n == "undefined") continue;
			    	n.data.postback = overhead.postback[i];
			    }
			    
		}
		,'text');
	}
}

function nodesFold(node)
{
	if (!rg.fx.animation.check())
	{
		return;
	}
	//var centeredNode = rg.graph.getClosestNodeToPos(new $jit.Complex(-rg.canvas.translateOffsetX,-rg.canvas.translateOffsetY));
	var centeredNode = node;
	if (typeof centeredNode.collapsed == 'undefined')
	{	
		/*centeredNode.eachSubgraph(function(sg){
			sg.data.posBeforeFold = sg.getPos();
		});*/
		rg.op.contract(centeredNode, {  
		  type: 'animate',  
		  duration: 1000,  
		  hideLabels: false,  
		  transition: $jit.Trans.Back.easeOut
		});
	}
	else
	{
		
		/*centeredNode.eachSubgraph(function(sg){
			sg.setPos(sg.data.posBeforeFold,'start'); 
		});*/
		rg.op.expand(centeredNode, {  
			  type: 'animate',  
			  duration: 1000,  
			  hideLabels: false,  
			  transition: $jit.Trans.Back.easeOut
		});
	}
}

function getSnapshot()
{
	var oCanvas = document.getElementById("infovis-canvas");
	var oCanvasBg = document.getElementById("infovis-bkcanvas");
	var img = Canvas2Image.saveAsPNG(oCanvas,true);
	var bg = Canvas2Image.saveAsPNG(oCanvasBg,true);
	return {image:img,bg:bg};
}
function getEdgeCoords()
{
	var maxX =0 ,maxY = 0,minX = 0,minY = 0;
	rg.graph.eachNode(function(node){
		var pos = node.getPos();
		if (maxX < pos.x) maxX = pos.x;
		if (maxY < pos.y) maxY = pos.y;
		if (minX > pos.x) minX = pos.x;
		if (minY > pos.y) minY = pos.y;
	});
	var coords  = {'maxX':maxX,'maxY':maxY,'minX':minX,'minY':minY}
	return coords;
}

function getBigSnapshot()
{
	var scale = Math.pow(1.1,$( "#slider" ).slider("option", "value") - initialScale);
	
	var sTrx = rg.canvas.translateOffsetX * scale;
	var sTry = rg.canvas.translateOffsetY * scale;
	
	var oldSizeX = initvar.width;
	var oldSizeY = initvar.height;
	
	var edgeCoords = getEdgeCoords();
	
	var newSizeX = 0;
	var newSizeY = 0;
	if (edgeCoords.maxX>0) newSizeX+=edgeCoords.maxX;
	if (edgeCoords.minX<0) newSizeX+=Math.abs(edgeCoords.minX);
	if (edgeCoords.maxY>0) newSizeY+=edgeCoords.maxY;
	if (edgeCoords.minY<0) newSizeY+=Math.abs(edgeCoords.minY);
	rg.canvas.resize(newSizeX,newSizeY);
	rg.canvas.translate(-sTrx,-sTry);
	var img = getSnapshot();
	rg.canvas.resize(oldSizeX,oldSizeY);
	rg.canvas.translate(sTrx,sTry);
	return img;
}