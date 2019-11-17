<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    // public $status;
    public $roles;

    function __construct(){
        parent::__construct();
        $this->load->model('User_model', 'user_model', TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel');
    }

	public function index()
	{
	    //user data from session
	    $data = $this->session->userdata;
	    if(empty($data)){
	        redirect(site_url().'main/login/');
	    }

	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    $data['title'] = "Dashboard Admin";
	    $result = $this->user_model->getAllEmployee();
	    $data['many_employee'] = $result->many_employee;
	    $data['start'] = $result->start_time;
	    $data['out'] = $result->out_time;
	    $data['timezone'] = $result->timezone;

	    $now = new DateTime();
        $now->setTimezone(new DateTimezone($data['timezone'])); //change your city
        $data['nowToday'] =  $now->format('Y-m-d');

	    $data['count_absent_today'] = $this->user_model->getAbsentToday("","","date", $data['nowToday']);
	    $data['count_late_today'] = $this->user_model->getAbsentToday("late_time >", "00:00:00","date", $data['nowToday'] );

        if(empty($this->session->userdata['email'])){
            redirect(site_url().'main/login/');
        }else{
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('index', $data);
            $this->load->view('footer');
        }

	}

	public function users()
	{
	    $data = $this->session->userdata;
	    $data['title'] = "User List";
	    $data['groups'] = $this->user_model->getUserData();

	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    //check is admin or not
	    if($dataLevel == "is_admin"){
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('user', $data);
            $this->load->view('footer');
	    }else{
	        redirect(site_url().'main/');
	    }
	}

	public function changelevel() //level user
	{
        $data = $this->session->userdata;
        //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    $data['title'] = "Change Level Admin";
	    $data['groups'] = $this->user_model->getUserData();

	    //check is admin or not
	    if($dataLevel == "is_admin"){

            $this->form_validation->set_rules('email', 'Your Email', 'required');
            $this->form_validation->set_rules('level', 'User Level', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('navbar', $data);
                $this->load->view('container');
                $this->load->view('changelevel', $data);
                $this->load->view('footer');
            }else{
                $cleanPost['email'] = $this->input->post('email');
                $cleanPost['level'] = $this->input->post('level');
                if(!$this->user_model->updateUserLevel($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating the level user');
                }else{
                    $this->session->set_flashdata('success_message', 'The level user has been updated.');
                }
                redirect(site_url().'main/changelevel');
            }
	    }else{
	        redirect(site_url().'main/');
	    }
	}

	
	public function changeuser() //edit user
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }

        $dataInfo = array(
            'firstName'=> $data['first_name'],
            'id'=>$data['id'],
        );

        $data['title'] = "Change Password";
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'matches[password]');

        $data['groups'] = $this->user_model->getUserInfo($dataInfo['id']);

        $issetPass = $this->input->post('password');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('changeuser', $data);
            $this->load->view('footer');
        }else{
            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);
            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            $cleanPost['user_id'] = $dataInfo['id'];
            $cleanPost['email'] = $this->input->post('email');
            $cleanPost['firstname'] = $this->input->post('firstname');
            $cleanPost['lastname'] = $this->input->post('lastname');
            if($issetPass){
                unset($cleanPost['passconf']);
                if(!$this->user_model->updateProfile($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
                }else{
                    $this->session->set_flashdata('success_message', 'Your profile has been updated.');
                }
            }else{
                 if(!$this->user_model->updateProfileUser($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
                }else{
                    $this->session->set_flashdata('success_message', 'Your profile has been updated.');
                }
            }
            redirect(site_url().'main/changeuser/');
        }
    }

    public function profile()
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }

        $data['title'] = "Profile";
        $this->load->view('header', $data);
        $this->load->view('navbar', $data);
        $this->load->view('container');
        $this->load->view('profile', $data);
        $this->load->view('footer');

    }

    public function deleteuser($id)
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    //check is admin or not
	    if($dataLevel == "is_admin"){
	        $getDelete = $this->user_model->deleteUser($id);

            if($getDelete == false ){
               $this->session->set_flashdata('flash_message', 'Error, cant delete the user!');
            }
            else if($getDelete == true ){
               $this->session->set_flashdata('success_message', 'Delete user was successful.');
            }else{
                $this->session->set_flashdata('flash_message', 'Someting Error!');
            }
            redirect(site_url().'main/users/');
	    }else{
	        redirect(site_url().'main/');
	    }
    }

    
    

    public function successresetpassword()
    {
        $data['title'] = "Success Reset Password";
        $this->load->view('header', $data);
        $this->load->view('container');
        $this->load->view('reset-pass-info');
        $this->load->view('footer');
    }

    protected function _islocal()
    {
        return strpos($_SERVER['HTTP_HOST'], 'local');
    }

    public function complete()
    {
        $token = base64_decode($this->uri->segment(4));
        $cleanToken = $this->security->xss_clean($token);

        $user_info = $this->user_model->isTokenValid($cleanToken); //either false or array();

        if(!$user_info){
            $this->session->set_flashdata('flash_message', 'Token is invalid or expired');
            redirect(site_url().'main/login');
        }
        $data = array(
            'firstName'=> $user_info->first_name,
            'email'=>$user_info->email,
            'user_id'=>$user_info->id,
            'token'=>$this->base64url_encode($token)
        );

        $data['title'] = "Set the Password";

        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('container');
            $this->load->view('complete', $data);
            $this->load->view('footer');
        }else{

            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);

            $cleanPost = $this->security->xss_clean($post);

            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            unset($cleanPost['passconf']);
            $userInfo = $this->user_model->updateUserInfo($cleanPost);

            if(!$userInfo){
                $this->session->set_flashdata('flash_message', 'There was a problem updating your record');
                redirect(site_url().'main/login');
            }

            unset($userInfo->password);

            foreach($userInfo as $key=>$val){
                $this->session->set_userdata($key, $val);
            }
            redirect(site_url().'main/');

        }
    }

    public function login()
    {
        $data = $this->session->userdata;
        if(!empty($data['email'])){
	        redirect(site_url().'main/');
	    }else{
	        $this->load->library('curl');
            // $this->load->library('recaptcha');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            //recaptcha
            $result = $this->user_model->getAllEmployee();
            // $recaptcha = $result->recaptcha;
            $data['recaptcha'] = $result->recaptcha;
            $data['title'] = "Welcome Back!";

            if($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('container');
                $this->load->view('login');
                $this->load->view('footer');
            }else{
                $post = $this->input->post();
                $clean = $this->security->xss_clean($post);
                $userInfo = $this->user_model->checkLogin($clean);

                // recaptcha
                // check if recaptcha is on
                if($recaptcha == 1){
                  $recaptchaResponse = $this->input->post('g-recaptcha-response');
                  $userIp = $_SERVER['REMOTE_ADDR'];
                  $key = $this->recaptcha->secret;
                  $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$key."&response=".$recaptchaResponse."&remoteip=".$userIp; //link
                  $response = $this->curl->simple_get($url);
                  $status= json_decode($response, true);

                  if(!$userInfo){
                      $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                      redirect(site_url().'main/login');                  
                  }elseif($status['success'] && $userInfo){
                      foreach($userInfo as $key=>$val){
                      $this->session->set_userdata($key, $val);
                      }
                      redirect(site_url().'main/');
                  }else{
                      //recaptcha failed
                      $this->session->set_flashdata('flash_message', 'Error...! Google Recaptcha UnSuccessful!');
                      redirect(site_url().'main/login/');
                      exit;
                  }
                // check if recaptcha is off
                }else{
                  if(!$userInfo){
                      $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                      redirect(site_url().'main/login');
                  }elseif($userInfo){ 
                      foreach($userInfo as $key=>$val){
                      $this->session->set_userdata($key, $val);
                      }
                      redirect(site_url().'main/');
                  }
                }
            }
	    }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url().'main/login/');
    }

       
    public function base64url_encode($data)
    {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data)
    {
      return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    
    public function settings() //edit user
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
        $this->load->helper('url');
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

        $data['title'] = "Settings";
        $this->form_validation->set_rules('start_time', 'Start', 'required');
        $this->form_validation->set_rules('date', 'date', 'required');
        $this->form_validation->set_rules('offence', 'offences[]', 'required');
        
        
        $result = $this->user_model->getAllEmployee();
        $data['id'] = $result->id;
        $data['offence'] = $result->offence;
        $data['start'] = $result->start_time;
        $data['date'] = $result->date;
        

	   
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('navbar', $data);
                $this->load->view('container');
                $this->load->view('settings', $data);
                $this->load->view('footer');
            }else{
                $post = $this->input->post(NULL, TRUE);
                $cleanPost = $this->security->xss_clean($post);
                $cleanPost['id'] = $this->input->post('id');
                $cleanPost['start_time'] = $this->input->post('start_time');
                $cleanPost['date'] = $this->input->post('date');
                $cleanPost['offence'] = $this->input->post('offence');
                

                if(!$this->user_model->settings($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your data!');
                }else{
                    $this->session->set_flashdata('success_message', 'Your data has been updated.');
                }
                redirect(site_url().'main/settings/');
            }
	    
    }

        
	public function generateqr()
	{
	    $data = $this->session->userdata;

	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        // if($dataLevel == "is_admin"){

            $data['title'] = "Generate QR Code";

            $userDetails = $this->input->post('user-details');

            // generate the qr with user details
            if(!empty($userDetails) && $userDetails == 1){

                $this->form_validation->set_rules('firstname', 'First Name', 'required');
                $this->form_validation->set_rules('lastname', 'Last Name', 'required');
                $this->form_validation->set_rules('s_mcard', 'Matric Card', 'required');
                $this->form_validation->set_rules('s_ic', 'IC', 'required');
                $this->form_validation->set_rules('s_program', 'Program', 'required');
                $this->form_validation->set_rules('s_type', 'Type of Vehicle', 'required');
                $this->form_validation->set_rules('s_plate', 'Plate', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('role', 'role', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

                $this->form_validation->set_rules('qr', 'Your Employee Full Name');

                if($this->user_model->isDuplicate($this->input->post('email'))){
                    $this->session->set_flashdata('flash_message', 'User email already exists');
                    redirect(site_url().'main/generateqr');
                }else{
                    $this->load->library('password');
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $cleanPost['qr'] = $this->input->post('firstname')." ".$this->input->post('lastname');
                    $hashed = $this->password->create_hash($cleanPost['password']);
                    $cleanPost['email'] = $this->input->post('email');
                    $cleanPost['role'] = $this->input->post('role');
                    $cleanPost['firstname'] = $this->input->post('firstname');
                    $cleanPost['lastname'] = $this->input->post('lastname');
                    $cleanPost['s_mcard'] = $this->input->post('s_mcard');
                    $cleanPost['s_ic'] = $this->input->post('s_ic');
                    $cleanPost['s_program'] = $this->input->post('s_program');
                    $cleanPost['s_type'] = $this->input->post('s_type');
                    $cleanPost['s_plate'] = $this->input->post('s_plate');
                    $cleanPost['password'] = $hashed;
                    unset($cleanPost['passconf']);

                    $cleanPost['qr'] = $this->input->post('firstname').' '.$this->input->post('lastname');
                    //insert to database
                    if(!$this->user_model->addUser($cleanPost)){
                        $this->session->set_flashdata('flash_message', 'There was a problem saving the QR.');
                        redirect(site_url().'main/generateqr');
                    }else{
                        if(!$this->user_model->insertQr($cleanPost)){
                            $this->session->set_flashdata('flash_message', 'There was a problem saving the QR.');
                            redirect(site_url().'main/generateqr');
                        }else{
                            $this->load->view('header', $data);
                            $this->load->view('navbar', $data);
                            $this->load->view('container');
                            $this->load->view('generateqr', $data, $cleanPost);
                            $this->load->view('footer');
                        }
                    }
                }

            }else{
                $this->form_validation->set_rules('qr', 'Your Employee Full Name');
                if (empty($_POST)) {
                        $this->load->view('header', $data);
                        $this->load->view('navbar', $data);
                        $this->load->view('container');
                        $this->load->view('generateqr', $data);
                        $this->load->view('footer');
                }else{
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $cleanPost['qr'] = $this->input->post('qr');

                    if(!$this->user_model->insertQr($cleanPost)){
                        $this->session->set_flashdata('flash_message', 'There was a problem saving the QR, and generate the QR.');
                        redirect(site_url().'main/generateqr');
                    }else{
                        $this->load->view('header', $data);
                        $this->load->view('navbar', $data);
                        $this->load->view('container');
                        $this->load->view('generateqr', $data, $cleanPost);
                        $this->load->view('footer');
                    }
                }
            }
        // } // check user level
	}

    public function historyqr()
    {
        $data = $this->session->userdata;
        $data['title'] = "History QR";
        $data['groups'] = $this->user_model->getHistoryQrData();
        $data['count'] = count($data['groups']);

        //check user level
        if(empty($data['role'])){
            redirect(site_url().'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if($dataLevel == "is_admin"){
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('historyqr', $data);
            $this->load->view('footer');
        }else{
            redirect(site_url().'main/');
        }
    }

    public function deletehistoryqr($id)
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
            redirect(site_url().'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if($dataLevel == "is_admin"){
            $getDelete = $this->user_model->deleteHistoryQr($id);

            $alldata = $this->user_model->getHistoryQrData();
            $dataCount = count($alldata);
            if($getDelete == false && $dataCount > 0){
               $this->session->set_flashdata('flash_message', 'Error, cant delete the user!');
            }
            else if($getDelete == true && $dataCount > 0){
               $this->session->set_flashdata('success_message', 'Delete user was successful.');
            }else if($dataCount > 0){
                $this->session->set_flashdata('flash_message', 'Someting Error!');
            }
            redirect(site_url().'main/historyqr/');
        }else{
            redirect(site_url().'main/');
        }
    }

        public function register()
        {
            $data['title'] = "Register to Admin";
            $this->load->library('curl');

            $this->form_validation->set_rules('firstname', 'First Name', 'required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required');
            $this->form_validation->set_rules('s_mcard', 'Matric Card', 'required');
            $this->form_validation->set_rules('s_ic', 'IC', 'required');
            $this->form_validation->set_rules('s_program', 'Program', 'required');
            $this->form_validation->set_rules('s_type', 'Type of Vehicle', 'required');
            $this->form_validation->set_rules('s_plate', 'Plate', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('role', 'role');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');


            if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('container');
            $this->load->view('register');
            $this->load->view('footer');
            }
            else
            {
                if($this->user_model->isDuplicate($this->input->post('email'))){
                $this->session->set_flashdata('flash_message', 'User email already exists');
                redirect(site_url().'main/login');
            }else{
                $clean = $this->security->xss_clean($this->input->post(NULL, TRUE));

        }
    
    
}
        }
    }

        