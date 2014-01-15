/*
 * Individual folder JS.
 * Logic for page that displays folder contents
 * @link /collection/Robert1/facebook_collection
 * @uses jquery
 * @uses common/utils - for the getParamFromURL() function
 */
define(['common/utils', "social/all", 'common/fd-scroll', "common/autoscroll_new", 'jquery'], function(utils) {

	/* ================== Variables ================= */
	var folder_top = '#folderTop';
	var folder_nums = '.num';

	/**
	 * Up button
	 */

	$( document ).on('preAjax',folder_top + ' .upvote', function() {
		console.info('Up collection', this);
		//toggle buttons
		$this = $( this );
		$this.hide().parent().find( '.downvote' ).show();
		//update count
		$this.parent().find( folder_nums ).each(function() {
			$this = $( this );
			$this.text(parseInt( $this.text() ) + 1);
		});
	});
	
	/**
	 * UnUp button
	 */
	$( document ).on('preAjax', folder_top + ' .downvote', function() {
		console.info('UnUp collection', this);
		//toggle buttons
		$this = $( this );
		$this.hide().parent().find( '.upvote' ).show();
		//update count
		$this.parent().find( folder_nums ).each(function() {
			$this = $( this );
			$this.text(parseInt( $this.text() ) - 1);
		});
	});
	
});
