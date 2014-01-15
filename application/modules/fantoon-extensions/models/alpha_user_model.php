<?php

class Alpha_user_model extends MY_Model
{
    protected $primary_key = 'beta_id';

    //Relations
    protected $belongs_to = array(
                                'user'
                            );

    function get_alpha_users($id)
    {
        $this->db->select('*');
        if($id)
        {
            $this->db->where('beta_id', $id);
        }
        $this->db->from('alpha_users');
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    function get_all_users()
    {
        $this->db->select('email');
        $this->db->from('users');
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }


    function email_sent_update($email)
    {
        $update = array('check'=>'1');
        $this->db->where('signup_email', $email);
        $this->db->update('alpha_users', $update);
    }

    function get_userid($email)
    {
        $this->db->select('beta_id');
        $this->db->where('signup_email', $email);
        $this->db->from('alpha_users');
        $query = $this->db->get();
        $row = $query->result_array();
				if ( count($row) > 0 ) {
        	return $row[0]['beta_id'];
				} else {
					return false;
				}
    }

    function insert_key($id,$key)
    {
        $alpha_key = array('alpha_key' => $key);
        $this->db->where('beta_id', $id);
        $this->db->update('alpha_users', $alpha_key);
    }

    function get_user_key($id)
    {
        $this->db->select('alpha_key');
        $this->db->from('alpha_users');
        $this->db->where('beta_id', $id);
        if($this->db->count_all_results()>0)
        {
            $query = $this->db->get('alpha_users');
            $row = $query->result_array();
            return $row[0]['alpha_key'];
        }
    }

    function get_user_invited_emails($user_id)
    {
        $query = $this->db->select('signup_email')                        
                        ->where('user_id',$user_id)
                        ->get($this->_table);

        $rows = $query->result();

        $result = array();
        foreach($rows as $row){
            $result[] = $row->signup_email;
        }
        return $result;
    }
                            
}
?>
