<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/home/newsfeed_layout/magazine1.php ) --> ' . "\n";
	} ?>
<script type="text/javascript" src="/js/plugins/jquery.masonry.min.js"></script>
<style>
#mag_lay .col0 { width: 200px; } <?php //no content - really rare?>
#mag_lay .col1 { width: 200px; }
#mag_lay .col2 { width: 422px; } 
#mag_lay .col3 { width: 644px; } <?php //a lot of content?>

</style>
<div id="mag_lay">
	<?php foreach ($items as $item): ?>
		<div class="mag_lay-content col<?php echo $item->col?>" style="margin: 5px; padding: 5px; border: 1px solid #777; float:left">
			<div class="media"><?php echo $item->data['media'] ? $item->data['media'] : '<img src="'.$item->data['link_img'].'" style="max-width:100%"/>'?></div>
			<div style="display:none"><?php echo $item->time?></div>
			<h3><?php echo $item->data['title']?></h3>
			<p class="info">Shared by <?php echo $item->data['link']?></a>
			<span class="date"><?php echo $item->time?></span></p>
			<a href="" class="link"><?php echo $item->data['link_domain']?></a>
			<p><?php echo $item->data['content']?></p>
		</div>
	<?php endforeach;?>
</div>
<script type="text/javascript">
(function($, undefined) {
	$.fn.imagesLoaded = function(callback){
	  var elems = this.find('img'),
	      len   = elems.length,
	      blank = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
	  elems.bind('load.imgloaded',function(){
		  if (--len <= 0 && this.src !== blank){ 
	        elems.unbind('load.imgloaded');
	        callback.call(elems,this); 
	      }
	  })
	  .bind('error', function() {
		  if (--len <= 0 && this.src !== blank){ 
	        elems.unbind('load.imgloaded');
	        callback.call(elems,this); 
	      }
	  })
	  .each(function(){
	     // cached images don't fire load sometimes, so we reset src.
	     if (this.complete || this.complete === undefined){
	        var src = this.src;
	        // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
	        // data uri bypasses webkit log warning (thx doug jones)
	        this.src = blank;
	        this.src = src;
	     }  
	  }); 
	
	  return this;
	};
})(jQuery);

var $tumblelog = $('#mag_lay');
$tumblelog.imagesLoaded( function(){
  console.info('LOADED');
  $tumblelog.masonry({
    itemSelector : '.mag_lay-content',
    columnWidth: 0
  });
});

</script> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/home/newsfeed_layout/magazine1.php ) -->' . "\n";
} ?>
