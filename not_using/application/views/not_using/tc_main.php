<div class="container_24">
	<div class="grid_12">
		<div id="tc_title">Visiting TechCrunch</div>
		<div id="tc_description">
	<p>Visiting TechCrunch?  The conference is full of great ideas with bright futures.  Select your favorite Disrupt startups and let us know why you like them.</p>
		</div>
	</div>
	<div class="grid_12">
		<img id="tc_main_vote" class="tc_main_section" style="width:400px;" src="/images/tc_vote.png">
	</div>
	
	<div class="clear"></div> 
	
	<div class="grid_12">
		<iframe src="http://player.vimeo.com/video/28942249?title=0&amp;byline=0&amp;portrait=0" width="450" height="263" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
	</div>
	<div class="grid_12">
		<div id="tc_title">Community Video</div>
		<div id="tc_description">
		<p>The best way to make new friends is through shared interests.  Fandrop introduces you to new people who are doing the same activities you are.  You’re already connected to your friends, now it’s time to connect with new people and expand the world you love.</p>
		</div>
	</div>
	
	<div class="clear"></div> 
	
	<div class="grid_12">
		<div id="tc_title">Discover Graph</div>
		<div id="tc_description">
<p>Fandrop’s Discover Graph is the best way to discover new people and new interests.  Navigate the Discover Graph to see how you connect to people around the world.  You’ll also discover the interests shared by those similar to yourself.  It will broaden your horizons.</p>
		</div>
	</div>
	<div class="grid_12">
		<img id="tc_main_graph" class="tc_main_section" style="width:400px;" src="/images/tc_graph.png">
	</div>
	
	<div class="clear"></div> 
	
	<div class="grid_12">
		<img id="tc_main_loops" class="tc_main_section" style="width:400px;" src="/images/tc_loops.png">
	</div>
	<div class="grid_12">
		<div id="tc_title">Loops</div>
		<div id="tc_description">
		<p>When following interests, you’ll meet new exciting people.  Keep them in the loop by adding them to Fandrop Loops.  You’ll be able to sort your new friends through the activities that bond you together.</p>
		</div>
	</div>
	
	<div class="clear"></div> 

	<div class="grid_12">
		<div id="tc_title">Demo Concept</div>
		<div id="tc_description">
<p>Fandrop is based on the interest graph.  The interest graph is a way to map individuals to interests and bring people together through those interests.  Watch this video to better understand the foundation underlying Fandrop’s vision.</p>
		</div>
	</div>
	<div class="grid_12">demo</div>
	
	<div class="clear"></div> 
	
</div>

<script type="text/javascript">

$(function(){
	var winH = $(window).height();
	
		//Vote
		$('#tc_main_vote').live('click', function() {
			$('#tc_vote_iframe').dialog({
				  width: 1100,
				  height: 800,
				  modal: true,
				  show: {effect: "fadeIn", duration: 800},
				  close: function() {
					  $(this).dialog('destroy');
					  $('html').css('overflow','auto');
				  }
			});
			$('html').css('overflow','hidden');
		});
		
		//Graph
		$('#tc_main_graph').live('click', function() {
			$('#tc_graph_iframe').dialog({
			  width: 1100,
			  height: 800,
			  modal: true,
			  show: {effect: "fadeIn", duration: 800},
			  close: function() {
				$(this).dialog('destroy');
				$('html').css('overflow','auto');
			  }
			});
			$('html').css('overflow','hidden');
		});

		//Loops/Lists
		$('#tc_main_loops').live('click', function() {
			$('#tc_loops_iframe').dialog({
			  width: 1100,
			  height: 800,
			  position: 'top',
			  modal: true,
			  show: {effect: "fadeIn", duration: 800},
			  close: function() {
				$(this).dialog('destroy');
				$('html').css('overflow','auto');
			  }
			});
			$('#tc_loops_iframe').dialog( "option", "position", [0,0] );
			$('#tc_loops_iframe').css('top','10px').css('left','10px');
			$('html').css('overflow','hidden');
		});		
	
	
});

</script>

<div id="tc_vote_iframe" style="display:none; overflow: hidden" width="100%" height="100%">
	<iframe width="100%" height="100%" src="/tc_vote" style="overflow: auto">
		<p>Your browser does not support iframes.</p>
	</iframe>
</div>
<div id="tc_graph_iframe" style="display:none; overflow: hidden" width="100%" height="100%">
	<iframe width="100%" height="100%" src="/tc_graph" style="overflow:auto">
		<p>Your browser does not support iframes.</p>
	</iframe>
</div>
<div id="tc_loops_iframe" style="display:none; overflow: hidden" width="100%" height="800px">
	<iframe width="100%" height="100%" src="/tc_loops" style="overflow:auto">
		<p>Your browser does not support iframes.</p>
	</iframe>
</div>
