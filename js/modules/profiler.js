/**
 * 
 */
define(['jquery'], function() {
	$('#codeigniter_profiler').css({left: -$('#codeigniter_profiler').width()+32}).show('fade');
	$('#codeigniter_profiler .dev-toolbar-handler a').on('click', function() {
		var bar = $(this).parents('#codeigniter_profiler');
		bar.find('fieldset').hide();
		bar.animate({
			left: bar.offset().left < 0 ? 0 : -bar.width()+32
		}, 100);
		return false;
	});
	$('#codeigniter_profiler a[rel]').on('click', function() {
		$(this).parents('#codeigniter_profiler').find('fieldset').hide();
		$(this).parents('#codeigniter_profiler').find('fieldset#'+$(this).attr('rel')).show('fade');
		return false;
	});
});