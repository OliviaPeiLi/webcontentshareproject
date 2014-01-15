<?php
/**
 * Share drop on a social networ facebook, twitter or pinterest
 * @author radilr, Ray
 */
class Share extends MX_Controller {
	
	function share_email()	{

		$this->load->library('form_validation');

		//$share_name_to = $this->input->post("share_name_to");
		$share_email_to = $this->input->post("share_email_to");
		$share_email_body = strip_tags($this->input->post("share_email_body"));
		$newsfeed_id = $this->input->post("newsfeed_id");

        // field name, error message, validation rules
       // $this->form_validation->set_rules('share_name_to', 'Name To', 'trim|required');
        $this->form_validation->set_rules('share_email_to', 'Email to', 'required|email');
//        $this->form_validation->set_rules('newsfeed_id', 'newsfeed_id', 'trim|required');

        if($this->form_validation->run() == FALSE)
        {
        	die(json_encode(array("success"=>false)));
        }
        elseif($this->user)
        {

        	if ( $this->input->post("folder_id") )	{

        		$folder_id = $this->input->post("folder_id");
				$obj_folder = $this->folder_model->get( $folder_id );
				$folder_title = $obj_folder->folder_name;

				$folder_url = Url_helper::base_url($obj_folder->get_folder_url());

				$obj_user = $this->user_model->get( $this->session->userdata("id") );
				$logged_user_name = $obj_user->first_name . " " . $obj_user->last_name;	

                $rows = $obj_folder->get('newsfeeds')->with_thumbnail()->order_by('newsfeed_id','desc')->limit(1)->get_all();

                if ($rows) {
                    $thumb =  $rows[0]->_img_square;
                }   else {
                    $thumb = Url_helper::s3_url().'images/activity-text.png';
                }

				$subject = "{$logged_user_name} sent you a list \"{$folder_title}\"";
				$body = $this->load->view("share/share_email_folder",array(
					"username"=>$logged_user_name,
					"image"=>$thumb,
					"folder_title"=>$folder_title,
					"folder_url"=>$folder_url,
					"message"=>$share_email_body
				),true);

        	}	else {
        	
    			$newsfeed_id = $this->input->post("newsfeed_id");
				$obj_newsfeed = $this->newsfeed_model->get( $newsfeed_id );
				
				$this->newsfeed_share_model->insert(array(
					'user_id' => $this->user->id,
					'newsfeed_id' => $newsfeed_id,
					'api' => 'email',
				));

				$newsfeed_title = strip_tags($obj_newsfeed->description);
				$newsfeed_url = Url_helper::base_url("drop/".$obj_newsfeed->url);

				$obj_user = $this->user_model->get( $this->session->userdata("id") );
				$logged_user_name = $obj_user->first_name . " " . $obj_user->last_name;				

				$subject = "{$logged_user_name} sent you a drop";
				$body = $this->load->view("share/share_email_newsfeed",array(
					"username"=>$logged_user_name,
					"image"=>$obj_newsfeed->_img_square,
					"newsfeed_title"=>$newsfeed_title,
					"newsfeed_url"=>$newsfeed_url,
					"message"=>$share_email_body
				),true);

        	}
        	
        	$body .= $share_email_body;
        	
        	foreach ($share_email_to[0] as $k => $v) {
				Email_helper::SendEmail( $v, $subject, $body);
        	}
        	
        	die(json_encode(array("status"=>true)));

        }

        die(json_encode(array("status"=>false,"msg"=>"Email not sent")));
    
	}
	
}
