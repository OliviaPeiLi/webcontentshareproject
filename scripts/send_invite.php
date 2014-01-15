<?php
define('BASEPATH', __DIR__.'/../system/');
if (!defined('ENVIRONMENT')) {
	if (strpos(__DIR__, '/fandrop/') !== false) {
		define('ENVIRONMENT', 'production');
	} elseif (strpos(__DIR__, '/test.fandrop/') !== false) {
		define('ENVIRONMENT', 'staging');
	} else {
		define('ENVIRONMENT', 'development');
	}
}
include(BASEPATH.'../scripts/db.php');

$limit = 20; //How many users to send
$interval = 20; //each $interval seconds

//get latest newsletter
$newsletter->subject = 'Your Fandrop invitation has arrived!';
$newsletter->msg = '

<html>
<head>
    
</head>
<body style="background-color: #FAFAFA; width: 100%; margin: 0;">
    <div id="email_body">
	<table width="100%" cellpadding="10">
	    <tr style="background: #F5F5F5;">
		<td colspan="2"><a href="http://www.fandrop.com"><font face="lucida grande, helvetica, arial, sans-serif" size="6" color="#313232"><strong><img src="http://www.fandrop.com/images/fandropHeaderLogo_new.png" alt="Fandrop" title="Go To Fandrop" border="0" height="51" style="display: block;"/></strong></font></a></td>
	    </tr> 

	    <tr>
		<td>
		    <table cellspacing="10" style="color: #656565;font: 12px lucida grande, helvetica, arial, sans-serif;">
			<tr>
			    <td></td>
			    <td>
			    	<p>
				        Welcome to Fandrop!
				    </p>
				    <p>
				        We are super excited to invite you to join Fandrop, a place where you discover and collect the coolest things around the web. We can&#39;t wait to have you join our community.
				    </p>
				    <p>
				    	<strong style="font-size: 14px;">Fandrop rules:</strong><br><br><strong>1st rule:</strong> you drop cool stuff with Fandrop<br><strong>2nd rule:</strong> you drop COOL stuff with Fandrop<br><strong>Last rule:</strong> if it&#39;s your first time using Fandrop, use our bookmarklet<br>
				    </p>
				    <p>
				    	If you have any questions, we&#39;d love to hear from you. Email us at <a href="mailto:team@fandrop.com?Subject=Suggestions" style="color: #3366CC">team@fandrop.com</a> or follow us on twitter <a href="https://twitter.com/#!/fandrop" style="color: #3366CC">@fandrop</a>
				    </p>
			    </td>
			</tr>
			<tr>
			    <td></td>
			    <td></td>
			</tr>
			<tr>
			    <td></td>
			    <td>Drop it like it\'s hot!</td>
			</tr>
			<tr>
			    <td></td>
			    <td>the Fandrop team</td>
			</tr>
			<tr>
				<td></td> 
			    <td style="background: #C9D9F9; padding: 10px 15px; border: 1px solid #B9C9F0;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
				<a href="http://www.fandrop.com/index.php/signup?a=access&b=b87jgzfke5" style="color: #3366CC">
				    Activate my Fandrop account!
				</a>
			    </td>
			</tr>

			<tr height="150">
			    <td></td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr style="background: #EDEDED;">
		<td style="color: #656565; font: 12px "lucida grande",tahoma,helvetica,arial,sans-serif;text-shadow: 1px 1px #FFFFFF;">
		    <center>Copyright &copy;&nbsp; 2011 - 2012 . Fandrop - a product of <a href="http://www.fantoon.com" style="color: #3366CC">Fantoon Labs</a></center>
		</td>
	    </tr>
	</table>
    </div>
</body>
</html> 

';

function SendEmail($to, $subject, $message)
{
	require_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/aws_sdk/sdk.class.php'); 

    $amazonSes = new AmazonSES(AWS_KEY, AWS_SECRET_KEY);
    $response = $amazonSes->send_email('"Fandrop" <team@fandrop.com>',
        array('ToAddresses' => $to),
        array(
            'Subject.Data' => $subject,
            'Body.Html.Data' => $message,
        )
    );
    
	
    if (!$response->isOK())
    {
        //print_r($response);
        echo "\r\nFail on ".$to."\r\n";
    }
    else
    {
    	echo "\r\nSent Email to ".$to."\r\n";
    	return TRUE;
    }
}

while (1) {
	echo "123Load $limit users at ".date('H:i:s Y-m-d')." ... ";
	
	//This is WHERE condition it should be, need Radil to review
	//WHERE newsletter_time < $newsletter->newsletter_time							
	$res = mysql_pquery("SELECT beta_id, signup_email
							FROM alpha_users 
							WHERE alpha_users.check = '0'
							ORDER BY beta_id ASC
							LIMIT 20
						");
											
	SendEmail('raymond202@gmail.com', $newsletter->subject, $newsletter->msg);
	//SendEmail('kenzi@fantoon.com', $newsletter->subject, $newsletter->msg);
	//SendEmail('alexi@fantoon.com', $newsletter->subject, $newsletter->msg);
	//ideally select 20 users in 20 seconds, need Radil to confirm
/*	
	while ($row = mysql_fetch_object($res)) {
		
		if($row->signup_email != ''){
			$email = $row->signup_email;
		}
		//if($row->email != ''){
		//	$email = $row->email;
		//}
		
		//cadfafdaSendEmail($email, $newsletter->subject, $newsletter->msg);

		mysql_pquery("UPDATE alpha_users SET alpha_users.check = '1' WHERE beta_id = {$row->beta_id} ");
	}
*/	
	echo "One load Done \r\n";
	sleep(20);
}