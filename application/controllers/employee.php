<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
class Employee extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('employee_model');

        $this->load->helper('form');
        $this->load->helper('url');

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('ci_smarty'); //載入smarty的配置檔案
    
        $title = '員工資料';
        $this->ci_smarty->assign('title' , $title);
        $this->ci_smarty->display('be/header.html'); // 頁首
        return;
    }

    function __destruct() { // 解構子
        $this->ci_smarty->display('be/footer.html');
    }

    public function index(){ // 主頁面(顯示員工資料)
        $config['base_url'] = base_url('index.php/employee/index');
        //資料庫有多少資料
        $config['total_rows'] = $this->employee_model->count();
        //每頁顯示10筆數據
        $config['per_page'] = 10;

        $this->pagination->initialize($config);
        //分頁的連結
        $links = $this->pagination->create_links();
        $this->ci_smarty->assign('links' , $links);

        //取網址第三的值
        $offset = intval($this->uri->segment(3));
        //依照分頁控制要輸出哪幾筆資料
        $employee_row = $this->employee_model->page($offset, $config['per_page']);
        $this->ci_smarty->assign('employee_row' , $employee_row);

        //瀏覽連結
        $this->ci_smarty->assign('scan_link' , base_url().'index.php/employee/scan/');
        //修改連結
        $this->ci_smarty->assign('modify_link' , base_url().'index.php/employee/modify/');
        //刪除連結
        $this->ci_smarty->assign('delete_link' , base_url().'index.php/employee/delete/');

        $this->ci_smarty->display('be/employee_index.html');
        return;
    }

    public function add() { //顯示新增頁面
        $this->ci_smarty->display('be/addEmployee.html');
        return;
    }

    public function create() { //新增
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('sex', 'sex', 'required');
        $this->form_validation->set_rules('birthday', 'birthday', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('phone', 'phone', 'required');
    
        if ($this->form_validation->run() === FALSE)
        {
            $this->ci_smarty->display('be/employee_index.html');
            echo "<script type='text/javascript'>
                alert('沒有資料新增!');
                window.location.href='http://localhost/CI/index.php/employee';
            </script>";
        }
        else
        {
            $this->employee_model->add_employee();
            echo "<script type='text/javascript'>
                alert('已新增員工資料!');
                window.location.href='http://localhost/CI/index.php/employee';
            </script>";
        }
        return;
    }

    public function delete($id) { //刪除
        $this->employee_model->delete_employee($id);
        echo "<script type='text/javascript'>
            alert('已刪除員工資料!');
            window.location.href='http://localhost/CI/index.php/employee';
        </script>";
    }

    public function modify($id){ //顯示修改頁面
        $query = $this->employee_model->where_array($id);
        $this->ci_smarty->assign('query' , $query);

        $this->ci_smarty->display('be/modifyEmployee.html');
        return;
    }

    public function modifyEm($id) { //修改
        $this->employee_model->modify_employee($id);
        echo "<script type='text/javascript'>
            alert('已修改員工資料!');
            window.location.href='http://localhost/CI/index.php/employee';
        </script>";
    }

    public function inquire() { //顯示查詢結果介面
        $inq = $this->employee_model->where_inquire();
        $this->ci_smarty->assign('inq' , $inq);

        //瀏覽連結
        $this->ci_smarty->assign('scan_link' , base_url().'index.php/employee/scan/');
        //修改連結
        $this->ci_smarty->assign('modify_link' , base_url().'index.php/employee/modify/');
        //刪除連結
        $this->ci_smarty->assign('delete_link' , base_url().'index.php/employee/delete/');

        $this->ci_smarty->display('be/inquireEmployee.html');
        //var_dump($inq);
        //exit;
        return;
    }

    public function scan($id) { //顯示瀏覽介面
        $query = $this->employee_model->where_array($id);
        $this->ci_smarty->assign('query' , $query);

        $this->ci_smarty->display('be/scanEmployee.html');
        return;
    }
}
?>