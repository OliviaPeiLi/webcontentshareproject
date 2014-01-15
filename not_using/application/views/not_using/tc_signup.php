<div class="container_24">
	<div class="prefix_7 grid_10 suffix_7">

                    <div id="tc_signup_options">
                        <div id="tc_signup_options_title">
                            <div class="tc_signup_col">
                                <div id="tc_signup_title" class="tc_frontPage_title step_title unselectable">
                                    Choose signup method:
                                </div>
                            </div>
                            <div class="header_triangle"> </div>
                        </div>

                        <div id="facebook" class="step_box">
                            <div class="tc_frontPage_subTitle step_title unselectable <? if(!($fb_id)) echo 'step_title_closed' ?>">
                                Sign up using facebook account
                            </div>
                            <div class="step_body" <? if(!($fb_id)) echo 'style="display:none"' ?> >
                                <?
                                if ($fb_id) { ?>
                                    <!-- <a href="<?php echo $logoutUrl; ?>">
                                        <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif" border="0">
                                    </a>-->
                                    <b>Thank you for signing up. Please look for an email from us soon once the alpha version becomes available.</b><br />

                                    <? if(count($fb_friends)>0)
                                    {?>
                                    <a href="#" onclick="return false" onmousedown="javascript:view_hide('fb_friends');">Check your facebook friends on Fandrop</a>


                                    <ul id="fb_friends" style="display:none">
                                        <? foreach($fb_friends as $k => $v)
                                        {
                                            echo '<li>'.$v["fb_firstname"].' '.$v["fb_lastname"].'</li>';
                                        }
                                        ?>
                                    </ul>
                                    <?}?>

                                <?
                                } else{ ?>
                                  <!-- We are currently experiencing issues with facebook connect. Please proceed with email sign-up below. -->
                                  <fb:login-button  perms="email,user_birthday,read_stream,publish_actions,offline_access" on-login="ext_connect_success()">Connect with Facebook</fb:login-button>

                                    <div id="fb-root"></div>
                                    <script>
                                      window.fbAsyncInit = function() {
                                        FB.init({appId: '239846336051336', status: true, cookie: true,
                                                 xfbml: true});
                                      };
                                      (function() {
                                        var e = document.createElement('script');
                                        e.type = 'text/javascript';
                                        e.src = document.location.protocol +
                                          '//connect.facebook.net/en_US/all.js';
                                        e.async = true;
                                        document.getElementById('fb-root').appendChild(e);
                                      }());
                                    </script>

                                        <!--
                                    <a href="#" onclick="login();return false;">
                                        <img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" border="0">
                                    </a>
                                    -->
                                <? } ?>
                                <!-- all time check if user session is valid or not -->
                                <?/*
                                if ($fbme){ */?>
                                    <!-- Data retrived from user profile are shown here -->
                                    <div class="fb_box">
                                        <!--<b>User Information using Graph API</b>-->
                                        <?/*php d($fbme); */?>
                                    </div>
                                    <div class="fb_box">
                                        <!--<b>User's Friends</b>-->
                                         <?/*php d($friends); */?>
                                    </div>
                                <? /* } //end fb */?>
                            </div>
                        </div>
                        <div id="twitter" class="step_box">
                            <div class="tc_frontPage_subTitle step_title unselectable <? if(!($tid)) echo 'step_title_closed' ?> ">
                                Sign up using twitter account
                            </div>
                            <div class="step_body" <? if(!($tid)) echo 'style="display: none"' ?> >
                                <?
                                if (isset($menu)) {
                                    echo $menu;
                                }
                                if (isset($status_text)) {
                                    echo '<h3>'.$status_text.'</h3>';
                                }
                                ?>
                                <?php //print_r($tid); ?>
                                <? //Display the friends_list on twitter ?>
                                <?php //print_r($friends_t); ?>
                                <? if($tid){ ?>
                                    <? if($email_check){ ?>
                                        <b>Thank you for signing up. Please look for an email from us soon once the alpha version becomes available.</b><br />

                                        <? if (count($twitter_friends)>0)
                                        {?>
                                        <a href="#" onclick="return false" onmousedown="javascript:view_hide('twitter_friends');">Check your twitter friends on Fandrop</a>
                                        <ul id="twitter_friends" style="display:none">
                                            <?
                                            foreach($twitter_friends as $k => $v)
                                            {
                                                echo '<li>'.$v['t_screen_name'].'</li>';
                                            }
                                            ?>
                                        </ul>
                                        <?}?>
                                    <?
                                    } else { ?>
                                        <div id="vibe_instructions">
                                             Please enter your Email:
                                        </div>
                                        <?
                                        echo form_open('/signup/twitteremail');
                                        echo Form_Helper::form_input('email', 'Email', 'class="input_placeholder" style="display:inline-block"');
                                        echo '<input type="hidden" name="interests_only" value="1">';
                                        echo '<input type="hidden" name="type" value="twitteremail">';
                                        echo '<input type="hidden" name="twitter_id" value="'.$this->session->userdata('tid').'">';
                                        echo form_submit('submit', 'Submit', 'style="display:inline-block" id="twitter_signup_submit"');
                                        echo form_close();
                                    }
                                }
                                else {
                                    //echo 'We are currently experiencing issues with twitter connect. Please proceed with email sign-up below.';
                                    echo '<div id="twitter_initial" class="twitter_step">';
                                    	echo '<a href="#" onclick="twitter_popup();return false;"><img src="http://si0.twimg.com/images/dev/buttons/sign-in-with-twitter-l.png" alt="Sign in with Twitter"/></a>';
                                	echo '</div>';
                                	
                                	echo '<div id="twitter_final" class="twitter_step" style="display:none;">'; ?>
                                	    <div id="vibe_instructions">
                                             Please enter your Email:
                                        </div>
                                        <?
                                     	echo form_open('/tc_share/twitteremail');
                                        echo Form_Helper::form_input('email', 'Email', 'class="input_placeholder" style="display:inline-block"');
                                        echo '<input type="hidden" name="interests_only" value="1">';
                                        echo '<input type="hidden" name="type" value="twitteremail">';
                                        echo '<input type="hidden" name="twitter_id" value="'.$this->session->userdata('tid').'">';
                                        echo form_submit('submit', 'Submit', 'style="display:inline-block" id="twitter_signup_submit"');
                                        echo form_close();
                                	echo '</div>';
                                }
                                ?>
                            </div>
                       </div>
                        <div id="tc_signup" class="step_box">
                            <div class="tc_frontPage_subTitle step_title unselectable <?if($email_submitted !== '1'){echo 'step_title_closed';}?>">
                                Sign up with your email address
                            </div>

                            <div class="step_body" <?if($email_submitted !== '1'){echo 'style="display: none"';}?>">
                                <? if ($email_submitted === '1') { ?>
                                    <div id="email_signup_submitted">
                                        Thank you for sharing our passion.
                                        Since we have a limited number of alpha accounts, we will notify you as soon as one becomes available.
                                    </div>
                                <? } else { ?>
                                    <ul id="email_signup_step2_form">
                                    	<? echo form_open('/tc_share'); ?>
                                        <li><input type="text" name="first_name" value="First Name" class="input_placeholder signup_textbox" id="signup_fname" /></li>
                                        <div class="clear"></div>
                                        <li><input type="text" name="last_name" value="Last Name" class="input_placeholder signup_textbox" id="signup_lname" /></li>
                                        <div class="clear"></div>
                                        <li><input type="text" name="email_address" value="Email Address" class="input_placeholder signup_textbox" id="signup_email" /></li>
                                        <div class="clear"></div>
                                        <li><input type="submit" name="submit" value="Get Alpha"  /></li>
                                        <? echo form_close(); ?>
                                    </ul>
                                <? } ?>
                            </div>
                        </div>
                    </div>
	</div>
</div>

<script type="text/javascript">
$(function() {
	$('#tc_signup_options .step_title').live('click', function () {
		var step = $(this).parent().attr('id');
		if (step === 'external') {
			//alert('step 1');
		}
		else if (step === 'signup') {
			//alert('step 2');
		}
		var step_body = $(this).next();
		if(step_body.css('display') === 'none') {
			$(this).removeClass('step_title_closed');
			step_body.show('blind');
		} else {
			$(this).addClass('step_title_closed');
			step_body.hide('blind');
		}
		//alert('step');
	});

	//Text input enhancement (hides default text on focus)
	var default_values = new Array();
	$('.box_input_placeholder, .input_placeholder').focus(function() {
	    if (!default_values[this.id]) {
			default_values[this.id] = this.value;
		}
		if (this.value == default_values[this.id]) {
			$(this).removeClass('box_input_placeholder');
			$(this).removeClass('input_placeholder');
			this.value = '';
		}
		$(this).blur(function() {
			if (this.value == '') {
				$(this).addClass('box_input_placeholder');
				$(this).addClass('input_placeholder');
				this.value = default_values[this.id];
			}
		});
	});
});
function ext_connect_success() {
	window.location= '<?=base_url()?>tc_share/fb';   
}
function twitter_popup(){
newwindow=window.open('<?=base_url()?>tc_twitter/', 'Twitter', 'menubar=no,width=790,height=360,toolbar=no');
if (window.focus) {newwindow.focus()}
return false;
} 

</script>