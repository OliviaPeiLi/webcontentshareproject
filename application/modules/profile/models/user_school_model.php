<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_school_model extends MY_Model
{

    protected $_table = 'user_schools';
    protected $primary_key = "id";

    //Relations
    protected $belongs_to = array(
                                'user',
                                'school' => array(
                                    'foreign_model' => 'school',
                                    'foreign_column' => 'school_id'
                                )
                            );


    //table fields: id, user_id, school_id, year, major

    /*
    *	usage: call these functions from controller
    *	$data = array('user_id'=>$user_id, 'school_id'=>$school_id, 'year'=>$year, 'major'=>$major);
    *
    *	//for insert call from your controller, $id returned is the insert_id()
    *	$id = $this->user_school_model->insert($data);
    *
    *	//update, $id - primary value
    *	$this->user_school_model->update($id, $data);
    *
    *	//delete, $id - primary value
    *	$this->user_school_model->delete($id);
    *
    *	//get all user schools
    *	$this->user_school_model->get_many_by('user_id', $user_id);
    *
    *	//get single record, $id - primary value
    *	$this->user_school_model->get($id)
    *
    */

}

//end of the file