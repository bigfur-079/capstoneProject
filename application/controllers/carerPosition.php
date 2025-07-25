<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
class CarerPosition extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('carerPosition_model');

        $this->load->helper('form');
        $this->load->helper('url');

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('ci_smarty'); //載入smarty的配置檔案
        $this->load->library('session'); //載入session的配置檔案
        $this->isUserLoggedIn = $this->session->userdata('isUserLoggedIn'); 
        $title = '照服員位置顯示介面';
        $this->ci_smarty->assign('title' , $title);
        return;
    }

    public function index(){ // 主頁面
        if ($this->session->userdata('user_id') and $this->session->userdata('role')=='管理者') {
            // 用戶已登入
            $data['user_logged_in'] = true;
        } else {
            // 用戶未登入
           
            redirect('user/account'); 
        }
        
        $this->ci_smarty->assign($data);
        // 頁首
         $this->ci_smarty->display('be/carer_position/header.html');
        //照服員選項資料
        $member = $this->carerPosition_model->member_option();
        $this->ci_smarty->assign('member' , $member);
        

        $this->ci_smarty->display('be/carer_position/carerPosition_index.html');
        $this->ci_smarty->display('be/carer_position/footer.html');
        return;
    }
    
    //查詢位置紀錄
    public function searchRecord() {
        $date = date('Y-m-d', strtotime($this->input->post('date')));

        //查詢結果
        $recordData = $this->carerPosition_model->search_record($date, $this->input->post('id'));

        if($recordData == null) {
            echo 'error';
        }
        else {
            echo json_encode($recordData);
        }
        return;
    }

    //新增mqtt接收的資料
    public function mqttAdd(){
        $data = array(
            'uid' => $this->input->post('uid'),
            'time' => $this->input->post('time'),
            'lat' => $this->input->post('lat'),
            'lng' => $this->input->post('lng'),
            'track_status' => $this->input->post('track_status')
        );

        $this->carerPosition_model->mqtt_add($data);
        echo 'success';
    }
}
?>