<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_link_model extends MY_Model
{

    protected $_table = "user_links";
    protected $primary_key = "id"; //id is default value, may be comented

    //Relations
    protected $belongs_to = array(
                                'user'
                            );

    //table fields: id, user_id, label, url

    /*	usage: call these functions from controller
    *	$data = array('user_id'=>$user_id, 'label'=>$label, 'url'=>$url);
    *
    *	//insert call from your controller, $id returned is the insert_id()
    *	$id = $this->user_link_model->insert($data);
    *
    *	//update, $id - primary value
    *	$this->user_link_model->update($id, $data);
    *
    *	//delete, $id - primary value
    *	$this->user_link_model->delete($id);
    *
    *	//get all user links
    *	$this->user_link_model->get_many_by('user_id', $user_id);
    *
    *	//get single record, $id - primary value
    *	$this->user_link_model->get($id)
    *
    */


    /*
     * same result to call from controller
     * $this->user_link_model->insert(array("user_id"=>$user_id, "label"=>$label, "url"=>$url));
    */
    function insert_user_link($user_id, $label, $url)
    {
        $data = array("user_id"=>$user_id, "label"=>$label, "url"=>$url);
        return $this->insert($data);
    }

    /*
    *	same result to call from controller
    * 	$this->user_link_model->update($id, array("label"=>$label, "url"=>$url));
    */
    function update_user_link($id, $label, $url)
    {

        $data = array("label"=>$label, "url"=>$url);
        return $this->update($id, $data);
    }

    /*
    *	same result to call from controller
    * 	$this->user_link_model->get_many_by('user_id', $user_id);
    */
    function get_user_links($user_id)
    {
        return $this->get_many_by('user_id', $user_id);
    }

    /*
    *	same result to call from controller
    * 	$this->user_link_model->delete($user_id);
    */
    function delete_user_link($user_id)
    {
        return $this->delete($user_id);
    }


}
?>