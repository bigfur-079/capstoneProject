<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class User extends CI_Controller { 
     
    function __construct() { 
        parent::__construct(); 
         
        // Load form validation ibrary & user model 
        $this->load->library('form_validation'); 
        $this->load->model('user_model'); 
         
        // User login status 
        $this->isUserLoggedIn = $this->session->userdata('isUserLoggedIn'); 
    } 
     
    public function index(){ 
        if($this->isUserLoggedIn){ 
            redirect('user/account'); 
        }else{ 
            redirect('user/login'); 
        } 
    } 
 
    public function account(){ 
        $data = array(); 
        if($this->isUserLoggedIn){ 
            $con = array( 
                'user_id' => $this->session->userdata('user_id') 
            ); 
            $data['user'] = $this->user_model->getRows($con); 
             
            // Pass the user data and load view 
            $this->load->view('be/header', $data); 
            $this->load->view('be/account', $data); 
            //$this->load->view('be/footer'); 
        }else{ 
            redirect('user/login'); 
        } 
    } 
 
    public function login(){ 
        $data = array(); 
         
        // Get messages from the session 
        if($this->session->userdata('success_msg')){ 
            $data['success_msg'] = $this->session->userdata('success_msg'); 
            $this->session->unset_userdata('success_msg'); 
        } 
        if($this->session->userdata('error_msg')){ 
            $data['error_msg'] = $this->session->userdata('error_msg'); 
            $this->session->unset_userdata('error_msg'); 
        } 
         
        // If login request submitted 
        if($this->input->post('loginSubmit')){ 
            $this->form_validation->set_rules('account', 'account', 'required'); 
            $this->form_validation->set_rules('password', 'password', 'required'); 
             
            if($this->form_validation->run() == true){ 
                $con = array( 
                    'returnType' => 'single', 
                    'conditions' => array( 
                        'account'=> $this->input->post('account'), 
                        'password' => md5($this->input->post('password')) 
                        //'status' => 1 
                    ) 
                ); 
                $checkLogin = $this->user_model->getRows($con); 
                if($checkLogin){ 
                    $this->session->set_userdata('isUserLoggedIn', TRUE); 
                    $this->session->set_userdata('user_id', $checkLogin['user_id']); 
                    $this->session->set_userdata('role', $checkLogin['role']);
                    redirect('user/account'); 
                }else{ 
                    $data['error_msg'] = 'Wrong email or password, please try again.'; 
                } 
            }else{ 
                $data['error_msg'] = 'Please fill all the mandatory fields.'; 
            } 
        } 
         
        // Load view 
        $this->load->view('be/header', $data); 
        $this->load->view('be/login', $data); 
        //$this->load->view('be/footer'); 
    } 
 
    public function register(){ 
        $data = $userData = array(); 
         
        // If registration request is submitted 
        if($this->input->post('signupSubmit')){ 
            $this->form_validation->set_rules('account', 'account', 'required|callback_account_check'); 
            $this->form_validation->set_rules('password', 'password', 'required'); 
            $this->form_validation->set_rules('conf_password', 'confirm password', 'required|matches[password]'); 
            $this->form_validation->set_rules('name', '', 'required'); 
            $userData = array(  
                'account' => strip_tags($this->input->post('account')), 
                'name' => strip_tags($this->input->post('name')), 
                'password' => md5($this->input->post('password')), 
                'gender' => $this->input->post('gender'), 
                'role' => $this->input->post('role'), 
                'phone_one' => strip_tags($this->input->post('phone')) 
            ); 
 
            if($this->form_validation->run() == true){ 
                $insert = $this->user_model->insert($userData); 
                if($insert){ 
                    $this->session->set_userdata('success_msg', 'Your account registration has been successful. Please login to your account.'); 
                    redirect('user/login'); 
                }else{ 
                    $data['error_msg'] = 'Some problems occured, please try again.'; 
                } 
            }else{ 
                $data['error_msg'] = '請確認是否有欄位輸錯'; 
            } 
        } 
         
        // Posted data 
        $data['user'] = $userData; 
         
        // Load view 
        $this->load->view('be/header', $data); 
        $this->load->view('be/register', $data); 
        //$this->load->view('be/footer'); 
    } 
     
    public function logout(){ 
        $this->session->unset_userdata('isUserLoggedIn'); 
        $this->session->unset_userdata('user_id'); 
        $this->session->unset_userdata('role'); 
        $this->session->sess_destroy(); 
        redirect('user/login/'); 
    } 
     
     
    // Existing email check during validation 
    public function account_check($str){ 
        $con = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'account' => $str 
            ) 
        ); 
        $check_account= $this->user_model->getRows($con); 
        if($check_account > 0){ 
            $this->form_validation->set_message('account_check', '此帳號已存在'); 
            return FALSE; 
        }else{ 
            return TRUE; 
        } 
    } 
}