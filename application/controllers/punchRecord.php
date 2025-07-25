<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
class PunchRecord extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('punchRecord_model');

        $this->load->helper('form');
        $this->load->helper('url');

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('ci_smarty'); //載入smarty的配置檔案
        $this->load->library('session'); //載入session的配置檔案
    
        $title = '打卡紀錄介面';
        $this->ci_smarty->assign('title' , $title);
        return;
    }

    public function index(){ //檢核請假主頁面
        if ($this->session->userdata('user_id')and $this->session->userdata('role')=='管理者') {
            // 用戶已登入
            $data['user_logged_in'] = true;
        } else {
            
            redirect('user/account'); 
        }
        $this->ci_smarty->assign($data);
        //照服員選項資料
        $member = $this->punchRecord_model->member_option();
        $this->ci_smarty->assign('member' , $member);
        // 頁首
         $this->ci_smarty->display('be/carer_position/header.html');
        

        //檢查 Session 變數中是否有儲存 $nameData 的值
        if ($this->session->has_userdata('nameData')) {
            //從 Session 中取得 $nameData 的值
            $nameData = $this->session->userdata('nameData');

            //將 $nameData 賦值給模板的變數
            $this->ci_smarty->assign('nameData', $nameData);
        }
        
        $this->ci_smarty->display('be/punch_record/punchRecord_index.html');
        $this->ci_smarty->display('be/punch_record/footer.html');
        return;
    }

    //查詢今日照服員打卡紀錄
    public function searchToday() {
        //查詢結果
        $todayData = $this->punchRecord_model->search_today($this->input->post('id'));

        if($todayData == null) {
            echo 'error';
        }
        else {
            echo json_encode($todayData);
        }
        return;
    }

    //查詢打卡紀錄
    public function searchRecord() {
        $date = date('Y-m-d', strtotime($this->input->post('date')));

        //查詢結果
        $punchData = $this->punchRecord_model->search_record($date, $this->input->post('id'));

        if($punchData == null) {
            echo 'error';
        }
        else {
            echo json_encode($punchData);
        }
        return;
    }
}
?>