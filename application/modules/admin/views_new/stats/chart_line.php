<style type="text/css">
	.chart_form .datepick.start { border-radius: 3px 0 0 3px; border-width:1px 0 1px 1px; }
	.chart_form .datepick.end { border-radius: 0; border-width:1px 0 1px 0; }
</style>
<div class="chart_line">

	<form action="/admin/<?=isset($data_url) ? $data_url : 'admin/google_charts/page'?>" class="chart_form">
	<p class="box">
			<input type="hidden" name="metric" value="<?=isset($metric) ? $metric : 'ga:pageviews'?>"/>
			<input type="hidden" name="dimension" value="<?=isset($dimension) ? $dimension : 'ga:date'?>"/>
			<input type="hidden" name="page" value="<?=isset($url) ? $url : '/'?>"/>
			<input type="text" name="from" class="datepick start" value="<?=isset($starttime) ? $starttime : '2012-01-01';?>"/>
			<input type="text" name="to" class="datepick end" value="<?=isset($endtime) ? $endtime : date('Y-m-d');?>"/>
	</p>
	</form>
	<?=isset($unit) ? $unit : ''?>
	<div class="chart" style="height:300px;"></div>
	<input id="clearSelection" type="button" class="btn" value="Zoom out" style="display:none"/>
</div>
<script type="text/javascript" src="/js/admin/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="/js/admin/flot/jquery.flot.selection.min.js"></script>
<script type="text/javascript">
if (typeof weekendAreas != 'function') {
	function weekendAreas(plotarea) {
		var areas = [];
		var d = new Date(plotarea.xmin);
		// go to the first Saturday
		d.setDate(d.getDate() - ((d.getDay() + 1) % 7))
		d.setSeconds(0);
		d.setMinutes(0);
		d.setHours(0);
		var i = d.getTime();
		do {
			areas.push({ x1: i, x2: i + 2 * 24 * 60 * 60 * 1000 });
			i += 7 * 24 * 60 * 60 * 1000;
		} while (i < plotarea.xmax);
	
		return areas;
	}	
}

	var options = {
			lines: { show: true, lineWidth: 1, fill: true },
			//points: { show: true },
			legend: { noColumns: 2, position: "nw"/*, container: '#flot-legend'*/ },
			//yaxis: { min: -25, max: 25 },
			xaxis: { mode: "time", timeformat: "%d %b", monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"] },
			selection: { mode: "x" },
			grid: { color: "#666", coloredAreas: weekendAreas, hoverable: true },
			colors: ["#87CEEB"]		
    };

    var previousPoint = null;
	$('.chart_line').each(function() {
		if ($(this).hasClass('initialized')) return;
		var $this = $(this);
	    $(this).find('form').bind('submit', function() {
	        var $form = $(this);
	        var $placeholder = $form.closest('.chart_line').find('.chart');
	        var first_submit = $placeholder.html() == '';
	    	$.get($(this).attr('action'),$(this).serialize(), function(data) {
	        	if (data[0].data != -1) {
	            	for (var i=0;i<data[0].data.length;i++) if (data[0].data[i][1] != 0) break;
	            	if (data[0].data.length > 0) {
		            	var dS = new Date(parseInt(data[0].data[0][0]));
		            	var dE = new Date(parseInt(data[0].data[(data[0].data.length-1)][0]));
						console.log(dE.toISOString().substr(0,10) + ' ' + data[0].data[(data[0].data.length-1)][1]);
		            	$form.find("[name=from]").attr('value', dS.toISOString().substr(0,10));
		            	$form.find("[name=to]").attr('value', dE.toISOString().substr(0,10));
	            	}
	        	}
	        	$.plot($placeholder, data, options);
			},'json')
	        return false;
		});

	    $(this).find('form input').on('change', function() {
	        $(this).closest('form').submit();
	    });

	    $(this).find('.chart').bind("plothover", function (event, pos, item) {
			if (item) {
				if (previousPoint != item.datapoint[0]) {
					previousPoint = item.datapoint[0];
					$('#tooltip').remove();
					var date = new Date(parseInt(item.datapoint[0]));
					var text = '<b>' + item.datapoint[1] + '</b>, ' + date.toUTCString().substr(5,11);
					$tooltip = $('<div id="tooltip">' + text + '</div>');
					$tooltip.css( {
			            position: 'absolute',
			            display: 'none',
			            top: item.pageY - 25,
			            left: item.pageX + 5,
			            border: '1px solid #fdd',
			            padding: '2px',
			            'background-color': '#87CEEB',
			            opacity: 0.80
			        }).appendTo("body").fadeIn(200);
					
				}
			} else {
				$('#tooltip').remove();
				previousPoint = null;            
			}
		});

	    if ($this.closest('.ui-tabs-hide').length) {
	    	$this.closest('.ui-tabs').find("a[href='#"+$this.closest('.ui-tabs-panel').attr('id')+"']").bind('click', function() {
	    		$this.find('form').submit();
	       	});
	    } 
	    $this.find('form').submit();
	    
		$(this).addClass('initialized');		
	});    
</script> 