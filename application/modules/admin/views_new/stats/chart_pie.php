<script type="text/javascript" src="/js/admin/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="/js/admin/flot/jquery.flot.pie.js"></script>
<?php $chart_id = uniqid()?>
<div class="pie_chart" id="chart-<?=$chart_id?>">
	<form action="/admin/google_charts/pie" class="chart_form" style="display:none">
	<p class="box">
			<input type="hidden" name="metric" value="<?=isset($metric) ? $metric : 'ga:pageviews'?>"/>
			<input type="hidden" name="dimension" value="<?=isset($dimension) ? $dimension : 'ga:date'?>"/>
			<input type="hidden" name="page" value="<?=isset($url) ? $url : ''?>"/>
			<input type="text" name="from" class="datepick start" value="2012-01-01"/>
			<input type="text" name="to" class="datepick end" value="<?=date('Y-m-d')?>"/>
	</p>
	</form>
	<div class="chart" style="height:300px;"></div>
</div>
<script type="text/javascript">
	var pie_options = {
			series: { pie: {
					show: true,
					combine: {
	                    color: '#999',
	                    threshold: 0.02
	                }
				} },
			legend: { show: false }
    };
	<?php if (isset($data)): ?>
		$.plot($('#chart-<?=$chart_id?> .chart'), <?=json_encode($data)?>, pie_options);
	<?php else: ?>
	    $('.pie_chart form').bind('submit', function() {
	        var $form = $(this);
	        if ($form.hasClass('loading'))return false;
	        $form.addClass('loading');
	    	$.get($(this).attr('action'),$(this).serialize(), function(data) {
	        	$form.removeClass('loading');
	        	$.plot($form.closest('.pie_chart').find('.chart'), data, pie_options);
			},'json');
	        return false;
		}).submit();
	<?php endif; ?>
 
</script> 