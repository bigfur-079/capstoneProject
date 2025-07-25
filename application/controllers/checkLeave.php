<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
class CheckLeave extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('checkLeave_model');

        $this->load->helper('form');
        $this->load->helper('url');

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('ci_smarty'); //載入smarty的配置檔案
        $this->load->library('session'); //載入session的配置檔案
    
        $title = '檢核請假介面';
        $this->ci_smarty->assign('title' , $title);
        return;
    }

    public function index(){ //檢核請假主頁面
        if ($this->session->userdata('user_id')and $this->session->userdata('role')=='管理者') {
            // 用戶已登入
            $data['user_logged_in'] = true;
        } else {
            // 用戶未登入
            
            redirect('user/account'); 
        }
        $this->ci_smarty->assign($data);
        
        // 頁首
        $this->ci_smarty->display('be/check_leave/header.html');

        //全部請假資料的分頁
        $config['base_url'] = base_url('checkLeave/index');
        //資料庫有多少資料
        $config['total_rows'] = $this->checkLeave_model->all_count();
        //每頁顯示10筆數據
        $config['per_page'] = 10;

        $this->pagination->initialize($config);
        //分頁的連結
        $allLinks = $this->pagination->create_links();
        $this->ci_smarty->assign('allLinks' , $allLinks);

        //取網址第三的值
        $offset = intval($this->uri->segment(3));
        //依照分頁控制要輸出哪幾筆所有請假資料
        $leaveData = $this->checkLeave_model->all_page($offset, $config['per_page']);
        $this->ci_smarty->assign('leaveData' , $leaveData);

        //照服員選項資料
        $member = $this->checkLeave_model->member_option();
        $this->ci_smarty->assign('member' , $member);

        //檢查 Session 變數中是否有儲存 $nameData 的值
        if ($this->session->has_userdata('nameData')) {
            //從 Session 中取得 $nameData 的值
            $nameData = $this->session->userdata('nameData');

            //將 $nameData 賦值給模板的變數
            $this->ci_smarty->assign('nameData', $nameData);
        }

        $this->ci_smarty->display('be/check_leave/checkLeave_index.html');
        $this->ci_smarty->display('be/check_leave/footer.html');
        return;
    }

    //新增請假資料
    public function add() {
        $user = $this->checkLeave_model->search_id($this->input->post('uid'));
        if($user != NULL) {
            $start = date('Y-m-d H:i:s', strtotime($this->input->post('startDatetime')));
            $end = date('Y-m-d H:i:s', strtotime($this->input->post('endDatetime')));

            $data = array(
                'uid' => $this->input->post('uid'),
                'start_time' => $start,
                'end_time' => $end,
                'category' => $this->input->post('category'),
                'reason' => $this->input->post('reason'),
                'check' => $this->input->post('check')
            );

            $this->checkLeave_model->add_leave($data);
            echo 'success';
        }
        else {
            echo 'error';
        }
        return;
    }

    //修改請假資料
    public function modify() {
        $start = date('Y-m-d H:i:s', strtotime($this->input->post('startDatetime')));
        $end = date('Y-m-d H:i:s', strtotime($this->input->post('endDatetime')));

        $data = array(
            'start_time' => $start,
            'end_time' => $end,
            'category' => $this->input->post('category'),
            'reason' => $this->input->post('reason'),
            'check' => $this->input->post('check')
        );

        $this->checkLeave_model->modify_leave($data, $this->input->post('id'));
        echo 'success';
        return;
    }

    //刪除請假資料
    public function delete() {
        $this->checkLeave_model->delete_leave($this->input->post('id'));
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

    //依照服務員 uid 查詢請假資料
    public function searchName() {
        //查詢結果
        $nameData = $this->checkLeave_model->searchName_leave($this->input->post('id'));

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
}
?>