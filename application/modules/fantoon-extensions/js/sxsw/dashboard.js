/**
 * Logic for sxsw dashboard
 */
define(['plugins/jquery.flot', 'jquery'], function() {
	
	/* ===================== Vars ========================== */
	var container = '#controlDashboard';
	var companies = container+' .companyTile';
	var graph = ' .companyGraph span';
	
	/* ======================= Functions ===================== */
	
	function init() {
		$(companies+graph).each(function() {
			var $this = $(this);
			var newsfeed_id = $(this).closest('[data-id]').attr('data-id');
			/*graph_config['width'] = $this.css('width');
			graph_config['height'] = $this.css('height');
			$(this).sparkline(php.companies_data[newsfeed_id], graph_config);*/
			var data = [];
			for (var i in php.companies_data[newsfeed_id]) {
				data.push([i, php.companies_data[newsfeed_id][i]]);
			}
			$.plot($this, [data], {
				'grid': {'show': false},
				'series': {
					'lines': {
						'fill': true, 'fillColor': {'colors': ['#FFFFFF','#00adef']},
					},
				},
				'colors': [ '#00adef' ] 
			});
		})
	}
	
	/* ======================= Events ======================== */
	
	var num_companies = 0;
	for (var i in php.companies_data) num_companies++;
	if (num_companies == $(companies+graph).length) {
		init();
	} else {
		$(document).ready(function(){ init(); });
	}
});