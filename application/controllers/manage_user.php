<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
class Manage_user extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('checkLeave_model');
        $this->load->model('User_model');

        $this->load->helper('form');
        $this->load->helper('url');

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('ci_smarty'); //載入smarty的配置檔案
        $this->load->library('session'); //載入session的配置檔案
    
        $title = '使用者管理介面';
        $this->ci_smarty->assign('title' , $title);
        return;
    }

    public function index(){ //使用者管理
        if ($this->session->userdata('user_id')and $this->session->userdata('role')=='管理者') {
            // 用戶已登入
            $data['user_logged_in'] = true;
        } else {
            // 用戶未登入
            
            redirect('user/account'); 
        }
        $this->ci_smarty->assign($data);
        
        // 頁首
        $this->ci_smarty->display('be/manage_user/header.html');

        //全部請假資料的分頁
        $config['base_url'] = site_url('manage_user/index');
        //資料庫有多少資料
        $config['total_rows'] = $this->User_model->all_count();
        //每頁顯示10筆數據
        $config['per_page'] = 10;

        $this->pagination->initialize($config);
        //分頁的連結
        $allLinks = $this->pagination->create_links();
        $this->ci_smarty->assign('allLinks' , $allLinks);

        //取網址第三的值
        $offset = intval($this->uri->segment(3));
        //依照分頁控制要輸出哪幾筆所有請假資料
        $user_data = $this->User_model->all_page($offset, $config['per_page']);
        $this->ci_smarty->assign('user_data' , $user_data);

        //照服員選項資料
        $member = $this->User_model->member_option();
        $this->ci_smarty->assign('member' , $member);

        //檢查 Session 變數中是否有儲存 $nameData 的值
        if ($this->session->has_userdata('nameData')) {
            //從 Session 中取得 $nameData 的值
            $nameData = $this->session->userdata('nameData');

            //將 $nameData 賦值給模板的變數
            $this->ci_smarty->assign('nameData', $nameData);
        }

        $this->ci_smarty->display('be/manage_user/index.html');
        $this->ci_smarty->display('be/manage_user/footer.html');
        
        return;
    }
    //
    public function account_check($str){ 
        $con = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'account' => $str 
            ) 
        ); 
        $check_account= $this->User_model->getRows($con); 
        if($check_account > 0){ 
            
            return FALSE; 
        }else{ 
            return TRUE; 
        } 
    }
    //新增會員資料
    public function add(){ 
        
        if ($this->account_check($this->input->post('account')) == FALSE){
            $response = array('status' => 'error', 'message' => '帳號重複');
            echo json_encode($response);
            return;
        }
        if ($this->mail_check($this->input->post('email')) == FALSE){
            $response = array('status' => 'error', 'message' => '信箱重複');
            echo json_encode($response);
            return;
        }
        
        $data = array(
            'name' => $this->input->post('name'),
            'role' => $this->input->post('role'),
            'gender' => $this->input->post('gender'),
            'birthday' => $this->input->post('birthday'),
            'phone_one' => $this->input->post('phone_one'),
            'phone_two' => $this->input->post('phone_two'),
            'email' => $this->input->post('email'),
            'address' => $this->input->post('address'),
            'account' => $this->input->post('account'),
            'password'=> md5($this->input->post('password'))
        );
        
        // 插入資料的程式碼
        $this->User_model->insert($data);
        
        $success_response = array('status' => 'success');
        echo ($success_response);
        return;
        
    } 

    //修改會員資料
    public function modify() {
        

        $data = array(
            'name' => $this->input->post('name'),
            'role' => $this->input->post('role'),
            'gender' => $this->input->post('gender'),
            'birthday' => $this->input->post('birthday'),
            'phone_one' => $this->input->post('phone_one'),
            'phone_two' => $this->input->post('phone_one'),
            'email' => $this->input->post('email'),
            'address' => $this->input->post('address'),
            'account' => $this->input->post('account'),
        );
        echo $data;
        $this->User_model->modify_user($data, $this->input->post('user_id'));
        echo 'success';
        return;
    }

    //刪除請假資料
    public function delete() {
        $this->User_model->delete_user($this->input->post('user_id'));
        echo 'success';
        return;
    }

    //依日期查詢請假資料
    public function searchDate() {
        $date = date('Y-m-d', strtotime($this->input->post('date')));
        //查詢結果
        $nameData = $this->checkLeave_model->searchDate_leave($date);

        if($nameData == null) {
            // 清空 Session 中的 $nameData 值
            $this->session->unset_userdata('nameData');
            echo 'error';
        }
        else {
            // 將 $nameData 儲存到 Session 變數中
            $this->session->set_userdata('nameData', $nameData);
            echo 'success';
        }
        return;
    }

    //依照會員 uid 查詢請假資料
    public function searchName() {
        //查詢結果
        $nameData = $this->User_model->search_id($this->input->post('id'));

        if($nameData == null) {
            // 清空 Session 中的 $nameData 值
            $this->session->unset_userdata('nameData');
            echo 'error';
        }
        else {
            // 將 $nameData 儲存到 Session 變數中
            $this->session->set_userdata('nameData', $nameData);
            echo 'success';
        }
        return;
    }

     // Existing account check during validation 
    

    // Existing mail check during validation 
    public function mail_check($str){ 
        $con = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'email' => $str 
            ) 
        ); 
        $check_account= $this->User_model->getRows($con); 
        if($check_account > 0){ 
            $this->form_validation->set_message('account_check', '此信箱已存在'); 
            return FALSE; 
        }else{ 
            return TRUE; 
        } 
    }
    

}

?>