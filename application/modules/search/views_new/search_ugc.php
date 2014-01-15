<div id="home" class="container" style="margin-top: 160px">
	<div class="row searchHeader">
		<h1><?=$keyword?></h1>
	</div>
	<div class="row">
		<div class="span15">
			<?=modules::run('search/search/collections_search') ?>
		</div>
	</div>
</div>