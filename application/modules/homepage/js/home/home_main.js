/**
 * Home page logic
 * @link / 
 */
define(['common/utils', 'jquery'], function(utils) {
	
	/* ==================== Events ====================== */

	/* ==================== Direct code =========================== */
    $('#open_catathon_intro_popup').click();

    /**
     * Mixpanel tracking
     */
    if (typeof(mixpanel) !== 'undefined') {

        var user = php.userId ? php.userId : 0;
        //mixpanel.people.identify(user);
        mixpanel.track('Home: Select Sort - '+utils.getParamFromURL('sort_by'), {'user':user});
        mixpanel.track('HOME - '+php.category, {'user':user});
        mixpanel.track('HOME Filtered by - '+utils.getParamFromURL('type') || 'All', {'user':user});

    }
    
});
