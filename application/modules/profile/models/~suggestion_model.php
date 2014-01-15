<?php

class Suggestion_model extends CI_Model
{


    /**********************************************************
    function: get all connection relates to userid
    input: userid
    output: result of all connections that relates to userid
    **********************************************************/
    function get_connection($user_id)
    {
        $this->db->where('user1_id', $user_id);
        //$this->db->or_where('user2_id', $user_id);
        $this->db->join('users','user2_id');
        $this->db->where('status', '1');
        $query = $this->db->get('connections');
        return $query->result();
    }

    /**********************************************************
    function: get profile entries of userid
    input: userid
    table: user_schools
    output: result of all entries that has userid = inputted userid
    **********************************************************/
    function get_profile($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_schools');
        return $query->result();
    }

    /**********************************************************
    function: get profile entries that match with inputted
    				year/major/schoolid field
    input: year, major, schoolid
    output: result of all entries that match with inputted info
    **********************************************************/
    function get_profile_specific($year, $major, $school_id)
    {
        $this->db->where('year', $year);
        $this->db->where('major', $major);
        $this->db->where('school_id', $school_id);
        $query = $this->db->get('user_schools');
        return $query->result();
    }

}