<?

class User_admin_request_model extends MY_Model
{    
    protected $_table = 'user_admin_request';
	protected $primary_key = 'id';
    protected $has_many = array(
                              'users' => array('foreign_column'=>'user_id'),
                          );

    protected $before_set = array('_before_set');


    public function _before_set(&$data)
    {        
          
        //if status is aproved change user role to 1
        $user_id = $data['user_id'];
        $role = 0; 
        if(isset($data['status']) && $data['status']=='approved'){
            $role = 1;
        }  
        $this->db->update('users', array('role'=> $role), array('id'=>$user_id));


        //set updated field value
        $data['updated']  = date('Y-m-d H:i:s');  
        
    }

    public function del($id){
        $this->db->where('id', $id);
        $this->db->delete($this->_table);
    }

    
   

}