<!DOCTYPE html>
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/recommendations/recommender.php ) --> ' . "\n";
	} ?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Recommender System Test Page</title>

<style type="text/css">

body {
    background-color: #fff;
    margin: 40px;
    font-family: Lucida Grande, Verdana, Sans-serif;
    font-size: 14px;
    color: #4F5155;
}

a {
    color: #444;
    background-color: transparent;
    border-bottom: 1px solid #D0D0D0;
    font-size: 16px;
    font-weight: bold;
    margin: 24px 0 2px 0;
    padding: 5px 0 6px 0;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

h1 {
    color: #444;
    background-color: transparent;
    border-bottom: 1px solid #D0D0D0;
    font-size: 16px;
    font-weight: bold;
    margin: 24px 0 2px 0;
    padding: 5px 0 6px 0;
}

code {
    font-family: Monaco, Verdana, Sans-serif;
    font-size: 12px;
    background-color: #f9f9f9;
    border: 1px solid #D0D0D0;
    color: #002166;
    display: block;
    margin: 14px 0 14px 0;
    padding: 12px 10px 12px 10px;
}

input.val {
    margin-left: 5px;
    margin-right: 5px;
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: 1px solid #999;   
    width: 50px;
    text-align: center;  
}

input.button {
    border: 1px solid #999;  
}

input.button : hover {
    border: 1px solid #0e0;  
}

</style>
</head>
<body>

<h1><?php echo anchor('admin/', 'Recommender System', array('title' => 'Home!'));?></h1>
<h1><?php echo anchor('admin/', 'Back', array('title' => 'Home!'));?></h1>    
<br/>
<br/>
<?php echo form_open('getPagesSimilarity/', array('id' => 'pageSim'));?>
<p>Calculate page similarity (<?php echo Form_Helper::form_input(array('name'=>'page1Id', 'class'=>'val', 'id'=>'page1Id', 'value'=>isset($page1Id)?$page1Id:'1', 'maxlength'=>'4' ));?>, <?php echo Form_Helper::form_input(array('name'=>'page2Id', 'id'=>'page2Id', 'class'=>'val', 'value'=>isset($page2Id)?$page2Id:'1', 'maxlength'=>'4' ));?>) =&nbsp;&nbsp;<b><?php echo isset($pageSimResult)?$pageSimResult:'?';?></b>&nbsp;&nbsp;&nbsp;<?php echo Form_Helper::form_submit(array('name'=>'pageSimB', 'id'=>'pageSimB', 'value'=>'Calculate!', 'class'=>'button'));?></p>
</form>
<?php echo form_open('getUserSimilarity/', array('id' => 'pageSim'));?>
<p>Calculate user similarity (<?php echo Form_Helper::form_input(array('name'=>'user1Id', 'class'=>'val', 'id'=>'user1Id', 'value'=>isset($user1Id)?$user1Id:'1', 'maxlength'=>'4' ));?>, <?php echo Form_Helper::form_input(array('name'=>'user2Id', 'id'=>'user2Id', 'class'=>'val', 'value'=>isset($user2Id)?$user2Id:'1', 'maxlength'=>'4' ));?>) =&nbsp;&nbsp;<b><?php echo isset($userSimResult)?$userSimResult:'?';?></b>&nbsp;&nbsp;&nbsp;<?php echo Form_Helper::form_submit(array('name'=>'userSimB', 'id'=>'userSimB', 'value'=>'Calculate!', 'class'=>'button'));?></p>
</form>
<p><?php if(isset($processTime)) echo ('Last process took <b>'.$processTime.'</b> seconds to complete!'); ?></p>
<p>&nbsp;</p>
<?php echo form_open('buildPageSimDataset/', array('id' => 'pageData'));?>
<p>Build <b>PagesSimilarity</b> dataset. Please notice that this function is processor intensive and should take a while!
    &nbsp;&nbsp;&nbsp;<?php echo Form_Helper::form_submit(array('name'=>'pageBuild', 'id'=>'pageBuild', 'value'=>'Build!', 'class'=>'button'));?></p>
</form>
<?php echo form_open('buildUserSimDataset/', array('id' => 'userData'));?>
<p>Build <b>UsersSimilarity</b> dataset. Please notice that this function is processor intensive and should take a while!
    &nbsp;&nbsp;&nbsp;<?php echo Form_Helper::form_submit(array('name'=>'userBuild', 'id'=>'userBuild', 'value'=>'Build!', 'class'=>'button'));?></p>
</form>
<p>&nbsp;</p>
<?php echo form_open('getUserPreferences/', array('id' => 'userPref'));?>
<p>Get recommended pages based on <b>user preferences</b>. <i>PagesSimilarity</i> and <i>UsersSimilarity</i> datasets must be already built!
    &nbsp;<b>userId</b> (<?php echo Form_Helper::form_input(array('name'=>'userId', 'class'=>'val', 'id'=>'userId', 'value'=>isset($userId)?$userId:'1', 'maxlength'=>'4' ));?>)&nbsp;
    <?php echo Form_Helper::form_submit(array('name'=>'userPreferences', 'id'=>'userPreferences', 'value'=>'Go!', 'class'=>'button'));?></p>
</form>
<?php echo form_open('getUserSimRecs/', array('id' => 'userSimRecs'));?>
<p>Get recommended pages based on <b>user similars</b>. <i>PagesSimilarity</i> and <i>UsersSimilarity</i> datasets must be already built!
    &nbsp;<b>userId</b> (<?php echo Form_Helper::form_input(array('name'=>'userId', 'class'=>'val', 'id'=>'userId', 'value'=>isset($userId)?$userId:'1', 'maxlength'=>'4' ));?>)&nbsp;
    <?php echo Form_Helper::form_submit(array('name'=>'userSimRecomendations', 'id'=>'userSimRecomendations', 'value'=>'Go!', 'class'=>'button'));?></p>
</form>
<?php echo form_open('getUserFriendsRecs/', array('id' => 'userFriendsRecs'));?>
<p>Get recommended pages based on <b>user most similar friends</b>. <i>PagesSimilarity</i> and <i>UsersSimilarity</i> datasets must be already built!
    &nbsp;<b>userId</b> (<?php echo Form_Helper::form_input(array('name'=>'userId', 'class'=>'val', 'id'=>'userId', 'value'=>isset($userId)?$userId:'1', 'maxlength'=>'4' ));?>)&nbsp;
    <?php echo Form_Helper::form_submit(array('name'=>'userFriendsRecomendations', 'id'=>'userFriendsRecomendations', 'value'=>'Go!', 'class'=>'button'));?></p>
</form>
<?php echo form_open('getRecommendations/', array('id' => 'recommendations'));?>
<p>Get recommended pages for user. This is the <b>aggregate and final method</b>. <i>PagesSimilarity</i> and <i>UsersSimilarity</i> datasets must be already built!
    &nbsp;<b>userId</b> (<?php echo Form_Helper::form_input(array('name'=>'userId', 'class'=>'val', 'id'=>'userId', 'value'=>isset($userId)?$userId:'1', 'maxlength'=>'4' ));?>)&nbsp;
    <?php echo Form_Helper::form_submit(array('name'=>'userRecommendations', 'id'=>'userRecommendations', 'value'=>'Go!', 'class'=>'button'));?></p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>
    <?php 
        if(isset($recommendations)) {
            echo ('Last method called for calculate recommendations <b>'.$methodName.'</b> returned:<br/><br/>');
            if(count($recommendations) > 0)
                foreach($recommendations as $key=>$value)
                    echo ( '&nbsp;&nbsp;&nbsp;&nbsp;Page Id <b>'.$key.'</b>   Score <b>'.$value.'</b><br/>');            
        }
    ?>
</p>

</body>
</html> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/recommendations/recommender.php ) -->' . "\n";
} ?>
