<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_User extends CI_Model {

    public function insertUser($userid)
    {
        $arr = array('user_id' => $userid,'role'=>'user' );
        return $this->db->insert('user', $arr);
    }

    public function getUserForSendingFlex($id)
    {
        return $this->db->query("select * from `user` where `user_id` != '".$id."'")->result();
        
    }
    public function getAdmin()
    {
        return $this->db->where('role','admin')->get('user')->result();
    }

    public function checkUser($id_user)
    {
        return $this->db->where('user_id',$id_user)->get('user')->num_rows()>0;
        
    }
}
?>