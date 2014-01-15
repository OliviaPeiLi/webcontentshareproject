<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/loop/loop.php ) --> ' . "\n";
	} ?>
<!--~~~~~~~~~~~~~ Main content of profile is here (posts, wall, albums) ~~~~~~~~~~~-->
<? $this->lang->load('loop/loop_views', LANGUAGE); ?>
<? if ($view_type === 'home') {
    $container = "home_friends_newsfeed";
} else {
    $container = "profile_newsfeeds_placeholder";
}
foreach ($feeds_array as $fkey => $fvalue)
{
    if($value['type']=='connection')
    {
        echo sprintf($this->lang->line('loop_views_connected_text'), $news_array[0], $news_array[1]);
        echo '<br />';
    }
    //$type= 'profile';
    //include('application/views/newsfeed/newsfeed.php');
    echo $this->load->view('newsfeed/newsfeed','',true);
} ?> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/loop/loop.php ) -->' . "\n";
} ?>
