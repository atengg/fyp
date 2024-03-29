<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public $status;
    public $roles;

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->banned_users = $this->config->item('banned_users');
    }

    public function insertUser($d)
    {
            $string = array(
                'first_name'=>$d['firstname'],
                'last_name'=>$d['lastname'],
                'email'=>$d['email'],
                'role'=>$this->roles[0],
                'status'=>$this->status[0],
                // 'banned_users'=>$this->banned_users[0],
            );
            $q = $this->db->insert_string('users',$string);
            $this->db->query($q);
            return $this->db->insert_id();
    }

    public function isDuplicate($email)
    {
        $this->db->get_where('users', array('email' => $email), 1);
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function insertToken($user_id)
    {
        $token = substr(sha1(rand()), 0, 30);
        $date = date('Y-m-d');

        $string = array(
                'token'=> $token,
                'user_id'=>$user_id,
                'created'=>$date
            );
        $query = $this->db->insert_string('tokens',$string);
        $this->db->query($query);
        return $token . $user_id;

    }

    public function isTokenValid($token)
    {
       $tkn = substr($token,0,30);
       $uid = substr($token,30);

        $q = $this->db->get_where('tokens', array(
            'tokens.token' => $tkn,
            'tokens.user_id' => $uid), 1);

        if($this->db->affected_rows() > 0){
            $row = $q->row();

            $created = $row->created;
            $createdTS = strtotime($created);
            $today = date('Y-m-d');
            $todayTS = strtotime($today);

            if($createdTS != $todayTS){
                return false;
            }

            $user_info = $this->getUserInfo($row->user_id);
            return $user_info;

        }else{
            return false;
        }

    }

    public function getUserInfo($id)
    {
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if($this->db->affected_rows() > 0){
            $row = $q->row();
            return $row;
        }else{
            error_log('no user found getUserInfo('.$id.')');
            return false;
        }
    }

    //---------getUserName
    public function getUserAllData($email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email );
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row;
        }
        else
        {
            error_log('no user found getUserAllData('.$email.')');
            return false;
        }
    }

    public function updateUserInfo($post)
    {
        $data = array(
               'password' => $post['password'],
               'last_login' => date('Y-m-d h:i:s A'),
               'status' => $this->status[1]
            );
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', $data);
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if(!$success){
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            $user_info = $this->getUserInfo($post['user_id']);
            return $user_info;
        }else{
            $user_info = $this->getUserInfo($post['user_id']);
            return $user_info;
        }

        
    }

    public function checkLogin($post)
    {
        $this->load->library('password');
        $this->db->select('*');
        $this->db->where('email', $post['email']);
        $query = $this->db->get('users');
        $userInfo = $query->row();
        $count = $query->num_rows();

        if($count == 1){
            if(!$this->password->validate_password($post['password'], $userInfo->password))
            {
                error_log('Unsuccessful login attempt('.$post['email'].')');
                return false;
            }else{
                $this->updateLoginTime($userInfo->id);
            }
        }else{
            error_log('Unsuccessful login attempt('.$post['email'].')');
            return false;
        }

        unset($userInfo->password);
        return $userInfo;
    }

    public function updateLoginTime($id)
    {
        $this->db->where('id', $id);
        $this->db->update('users', array('last_login' => date('Y-m-d h:i:s A')));
        return;
    }

    public function getUserInfoByEmail($email)
    {
        $q = $this->db->get_where('users', array('email' => $email), 1);
        if($this->db->affected_rows() > 0){
            $row = $q->row();
            return $row;
        }else{
            error_log('no user found getUserInfo('.$email.')');
            return false;
        }
    }

    public function updatePassword($post)
    {
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', array('password' => $post['password']));
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if(!$success){
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }
        return true;
    }

    //add user login
    public function addUser($d)
    {
            $string = array(
                'first_name'=>$d['firstname'],
                'last_name'=>$d['lastname'],
                'email'=>$d['email'],
                'password'=>$d['password'],
                'role'=>$d['role'],
                'status'=>$this->status[1],
                'banned_users'=>$this->banned_users[0]
            );
            $q = $this->db->insert_string('users',$string);
            $this->db->query($q);
            return $this->db->insert_id();
    }

    //update profile user
    public function updateProfile($post)
    {
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', array('password' => $post['password'], 'email' => $post['email'], 'first_name' => $post['firstname'], 'last_name' => $post['lastname']));
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if(!$success){
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }
        return true;
    }

    //update user level
    public function updateUserLevel($post)
    {
        $this->db->where('email', $post['email']);
        $this->db->update('users', array('role' => $post['level']));
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if(!$success){
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }
        return true;
    }

    //update user ban
    public function updateUserban($post)
    {
        $this->db->where('email', $post['email']);
        $this->db->update('users', array('banned_users' => $post['banuser']));
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if(!$success){
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }
        return true;
    }

    //get email user
    public function getUserData()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    //delete user
    public function deleteUser($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('users');

        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    //how many employee
    public function getAllEmployee()
    {
        $this->db->select('*');
        $this->db->from('settings');
        return $this->db->get()->row();

    }

    //how many employee
    public function getAllSummons()
    {
        $this->db->select('*');
        $this->db->from('summons');
        return $this->db->get()->row();

    }

    //get count absent today
    public function getAbsentToday($cola, $wherea, $colb, $whereb)
    {
        $where = array();

        if ($wherea != '') $where[$cola] = $wherea;
        if ($whereb != '') $where[$colb] = $whereb;


        if (empty($where))
        {
        return array(); // ... or NULL
        }
        else
        {
        $query = $this->db->get_where('absent', $where);
        $count = $query->num_rows();
        return $count;
        }

    }

    //get employees
    public function getEmployees($col, $where)
    {
        $this->db->select('*');
        $this->db->from('absent');
        $this->db->where($col, $where);
        $query = $this->db->get();
        return $query->result();
    }

    //get data absent
    public function getDataAbsent($cola, $wherea, $colb, $whereb)
    {
        $this->db->select('*');
        $this->db->from('absent');
        $this->db->where($cola, $wherea);
        $this->db->where($colb, $whereb);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row["in_time"];
        }
        else
        {
            return false;
        }

    }

    public function insertAbsent($alldata)
    {
        $string = array(
            'name'=> $alldata['name'],
            'date'=> $alldata['date'],
            'late_time'=> $alldata['late_time'],
            'in_time'=> $alldata['in_time'],
            'in_location'=> $alldata['in_location']
        );
        $q = $this->db->insert_string('absent',$string);
        $this->db->query($q);
        $check = $this->db->insert_id();

        if ($check){
            return true;
        }else{
            return false;
        }

    }

    public function updateAbsent($alldata)
    {
        $name = $alldata['name'];
        $date = $alldata['date'];

        $string = array(
            'out_location'=> $alldata['out_location'],
            'out_time'=> $alldata['out_time'],
            'work_hour'=> $alldata['work_hour'],
            'over_time'=> $alldata['over_time'],
            'early_out_time'=> $alldata['early_out_time']
        );
        $this->db->where('name', $name);
        $this->db->where('date', $date);
        $this->db->update('absent', $string);
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if ($success){
            return true;
        }else{
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }
    }

    //settings
    public function settings($post)
    {
        $date = date('Y-m-d');

        $this->db->where('id', $post['id']);
        $this->db->update('settings',
            array(
                'start_time' => $post['start_time'],
                'date' => $post['date'],
                'offence' => $post['offence']
                
            )
        );
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if(!$success){
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }else{
            return true;
        }
        
    }

    //update profile user
    public function updateProfileUser($post)
    {
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', array('email' => $post['email'], 'first_name' => $post['firstname'], 'last_name' => $post['lastname']));
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if(!$success){
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }
        return true;
    }

    public function insertQr($alldata)
    {
        $string = array(
            'name'=> $alldata['qr'],
        );
        $q = $this->db->insert_string('history_qr',$string);
        $this->db->query($q);
        $check = $this->db->insert_id();

        if ($check){
            return true;
        }else{
            return false;
        }

    }
    //get history qr by ID
    public function getHistoryQrDataByID($id)
    {
        $this->db->where('id',$id);
        return $query->result();
    }
    
    //get history qr
    public function getHistoryQrData()
    {
        $query = $this->db->get('history_qr');
        return $query->result();
    }

    //delete history qr
    public function deleteHistoryQr($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('history_qr');

        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    

    public function registeruser($firstName,$lastName,$s_mcard,$s_ic,$s_program,$s_type,$s_plate,$email,$password){
    $data=array(
                'firstName' => $firstName,
                'lastName' => $lastName,
                's_mcard' => $s_mcard,
                's_ic' => $s_ic,
                's_program' => $s_program,
                's_type' => $s_type,
                's_plate' => $s_plate,
                'email' => $email,
                'password' => $password,
                'passconf' => $passconf,
            );
                $sql_query=$this->db->insert('users',$data);
                if($sql_query)
                {
                        $this->session->set_flashdata('success', 'Registration successful');
                        redirect('main/register');
                }
                    else{
                        $this->session->set_flashdata('error', 'Something went wrong. Error!!');
                        redirect('main/register');
                    }

        }


    
}

