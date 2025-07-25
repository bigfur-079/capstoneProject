<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
//header("Access-Control-Allow-Origin: http://localhost");
//defined('BASEPATH') OR exit('No direct script access allowed');

class Salary extends CI_Controller {

 public function __construct()
 {
  parent::__construct();
  $this->load->model('schedule_model');
  $this->load->model('service_items_model');
  $this->load->library('form_validation'); 
  $this->load->model('user_model');
  $this->isUserLoggedIn = $this->session->userdata('isUserLoggedIn'); 
 }

 function index()
 {
   $data['schedules'] = NULL;
   $data['cares'] = NULL;
   if($this->isUserLoggedIn and $this->session->userdata('role')=='管理者'){ 
      $this->load->view('be/header',$data); 
      $this->load->view('be/salary',$data);
      
    }else{ 
      redirect('user/account'); 
    } 
    
   
  
 }
  function calculate() {
   // 使用 $this->input->post() 獲取前端 POST 請求中的資料
   $input_data = $this->input->post('start');
   $hours_money = $this->input->post('hours_money');
   $month = substr($input_data,6);
   $data['schedules'] = $this->schedule_model->fetch_get_month($month);
   $data['cares'] = $this->user_model->fetch_all_care();
   $data['cases'] = $this->user_model->fetch_all_case();
   $data['hours_money'] = $hours_money;
   foreach ($data['schedules'] as &$row) {
      
      

      
      if ($row['time_difference']=='00:30:00'){
         $row['time_difference'] = 0.5;
      }
      else{
         $row['time_difference'] = substr($row['time_difference'],1,1);
      }
      
   };
   

   foreach ($data['schedules'] as &$schedule) { 
      $schedule['care_name'] = '';
      $schedule['case_name'] = '';
   }
   //找care name
   foreach ($data['schedules'] as &$schedule) {
      foreach ($data['cares'] as $care) {
         if ($schedule['uid'] == $care['user_id']){
            $schedule['care_name'] = $care['name'];
         }
         
      }
      
   }

   //找case name
   foreach ($data['schedules'] as &$schedule) {
      foreach ($data['cases'] as $case) {
         if ($schedule['case_id'] == $case['user_id']){
            $schedule['case_name'] = $case['name'];
         }
      }
   }
   foreach ($data['cares'] as &$care) { 
      $care['salary'] = 0;
   }
   //算時數
   foreach ($data['cares'] as &$care) {
      foreach ($data['schedules'] as $row) {
         if ($care['user_id'] == $row['uid']){
            $care['salary'] = $care['salary']+$row['time_difference'];
         }
      }
   }
   

   $this->load->view('be/header',$data); 
    $this->load->view('be/salary',$data);
}
 

}

?>
