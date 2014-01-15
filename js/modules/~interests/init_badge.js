define(['jquery'], function() {

    var hoverintent_config = {
        over: show_badge,
        timeout:200,
        interval: 300,
        out: hide_badge
    };
    $('.show_badge').hoverIntent(hoverintent_config);

});
