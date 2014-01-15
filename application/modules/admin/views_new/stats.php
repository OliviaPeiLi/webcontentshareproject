<style type="text/css">
	.chart_form .datepick.start { border-radius: 3px 0 0 3px; border-width:1px 0 1px 1px; }
	.chart_form .datepick.end { border-radius: 0; border-width:1px 0 1px 0; }
</style>
<h4>Page Views</h4>
<hr/>
<form action="/admin/google_charts/<?=$method?>" class="chart_form">
<p class="box">
		<input type="hidden" name="metric" value="<?=isset($metric) ? $metric : 'ga:pageviews'?>"/>
		<input type="hidden" name="dimension" value="<?=isset($dimension) ? $dimension : 'ga:date'?>"/>
		<input type="hidden" name="page" value="<?=isset($url) ? $url : '/'?>"/>
		<input type="text" name="from" class="datepick start" value="2012-01-01"/>
		<input type="text" name="to" class="datepick end" value="<?=date('Y-m-d')?>"/>
</p>
</form>
<div id="flotPlaceholder" style="height:300px;"></div>
<input id="clearSelection" type="button" class="btn" value="Zoom out" style="display:none"/>
<p>&nbsp;</p>
<script type="text/javascript" src="/js/admin/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="/js/admin/flot/jquery.flot.selection.min.js"></script>
<script type="text/javascript">
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
    var placeholder = $("#flotPlaceholder");
    var placeholder_form = placeholder.prevAll('.chart_form');
    placeholder_form.bind('submit', function() {
        var first_submit = placeholder.html() == '';
    	$.get($(this).attr('action'),$(this).serialize(), function(data) {
        	if (first_submit && data[0].data != -1) {
            	console.info(data);
            	for (var i=0;i<data[0].data.length;i++) if (data[0].data[i][1] != 0) break;
            	var d = new Date(data[0].data[i-1][0]);
            	placeholder_form.find("[name=from]").val(d.toISOString().substr(0,10));
            	placeholder_form.submit();
        	}
        	$.plot(placeholder, data, options);
		},'json')
        return false;
	});
    placeholder_form.find('input').change(function() {
        $(this).closest('form').submit();
    });
    var previousPoint = null;
    placeholder.bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.datapoint[0]) {
				previousPoint = item.datapoint[0];
				if (placeholder.tooltip) placeholder.tooltip.remove();
				placeholder.tooltip = $('<div class="tooltip">' + item.datapoint[1] + '</div>');
				placeholder.tooltip.css( {
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
			if (placeholder.tooltip) placeholder.tooltip.remove();
			previousPoint = null;            
		}
	});
    
	
    if (placeholder.closest('.ui-tabs-hide').length) {
    	placeholder.closest('.ui-tabs').find("a[href='#"+placeholder.closest('.ui-tabs-panel').attr('id')+"']").bind('click', function() {
    		placeholder_form.submit();
       	});
    } else {
    	placeholder_form.submit();
    }
</script> 