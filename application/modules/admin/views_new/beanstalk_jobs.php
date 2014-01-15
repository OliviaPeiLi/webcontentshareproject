<script type="text/javascript" src="/js/admin/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="/js/admin/flot/jquery.flot.threshold.min.js"></script>


<!-- Wrapper -->
<div class="wrapper">
		<!-- Left column/section -->
		<h3>Last <?= $limit?> Beanstalk Jobs</h3>
		<section class="column width4 first"><h4>Start delay</h4></section>
		<section class="column width4"><h4>Processing time</h4></section>
		

		<? foreach($flots as $i => $flot):?>
			<section class="column width4 first">			
				<div id="p_left<?=$i?>" class="place"></div>			
			</section>
			<section class="column width4">			
				<div id="p_right<?=$i?>" class="place"></div>			
			</section>
		<? endforeach?>
	
</div>
<!-- End of Wrapper -->

<style type="text/css">
	.place{width:99%; height:300px;}
</style>

<script type="text/javascript">
$(function () {
	var default_options = {
		/*series: {
			threshold: {
			  below: 30,
			  color: "rgb(200, 20, 30)"
			}
		},*/
		bars: {show:true, barWidth: 0.6, fill:0.9},
		xaxis: {show:false, mode:'time'},
		grid: {
			backgroundColor: { 
				colors: ["#fff", "#eee"]
			}
		}	
	};
    <? foreach($flots as $i => $flot):?>
		console.info(<?= $flot['start_delay']?>);
    	$.plot($("#p_left<?=$i?>"), <?= $flot['start_delay']?>, default_options );
    	$.plot($("#p_right<?=$i?>"), <?= $flot['processing_time']?>, default_options );
    <? endforeach?>
   	
});
</script> 