<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
//header("Access-Control-Allow-Origin: http://localhost");
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {

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
  $data['options'] = $this->service_items_model->fetch_all_service();
  $data['cares'] = $this->user_model->fetch_all_care();
  $data['cases'] = $this->user_model->fetch_all_case();
  if($this->isUserLoggedIn and $this->session->userdata('role')=='管理者'){ 
    $this->load->view('be/header',$data); 
    $this->load->view('be/Schedule',$data);
    
  }else{ 
    redirect('user/account'); 
  } 
  
 }

 function load()
 {
  $event_data = $this->schedule_model->fetch_all_event();
  foreach($event_data->result_array() as $row)
  {
   $data[] = array(
    'id' => $row['schedule_id'],
    'title' => '照服員:'.$row['uid'].'個案姓名:'.$row['case_id'].'服務項目:'.$row["service_name"],
    'start' => $row['service_time_start'],
    'end' => $row['service_time_end']
   );
  }
  echo json_encode($data);
 }

 function insert()
 {
  if($this->input->post('uid'))
  {
    $service_name =  $this->input->post('service_name');
    //$service_num = sub_str($service_name,0,3);
    $data = array(
    'uid'  => $this->input->post('uid'),
    'case_id'  => $this->input->post('case_id'),
    'service_name'  =>$service_name,
    'service_time_start'=> $this->input->post('start'),
    'service_time_end' => $this->input->post('end'),
    'service_condition'  => '0'
    
   );
   $this->schedule_model->insert_event($data);
  }
  //echo $service_num;
 }

 function update()
 {
  if($this->input->post('id'))
  {
   $data = array(
    'schedule_id' => $this->input->post('id'),

    'service_time_start'=> $this->input->post('start'),
    'service_time_end' => $this->input->post('end'),
   );

   $this->schedule_model->update_event($data, $this->input->post('id'));
  }
 }

 function delete()
 {
  if($this->input->post('id'))
  {
    $this->schedule_model->delete_event($this->input->post('id'));
  }
 }
 //查詢照服員班表
 function care_schedule(){
  $data['care_schedule'] = NULL;
  
  $role = $this->session->userdata('role');
  $id = $this->session->userdata('user_id');
  
  if($this->isUserLoggedIn and $role=='照服員' ){ 
    
    $this->load->view('be/header',$data); 
    $this->load->view('be/care_schedule',$data);
    
  }else{ 
    redirect('user/account'); 
  } 
 }
 function get_care_schedule(){
  $role = $this->session->userdata('role');
  $id = $this->session->userdata('user_id');
  $month = $this->input->post('month');
  //$month = substr($month,6);
  //
  $data['cares'] = $this->user_model->fetch_all_care();
  $data['cases'] = $this->user_model->fetch_all_case();
  //get care_id  and month schedule 
  $data['care_schedule'] = $this->schedule_model->get_care_schedule($id,$month);
  //找care name
  foreach ($data['care_schedule'] as &$schedule) {
    foreach ($data['cares'] as $care) {
       if ($schedule['uid'] == $care['user_id']){
          $schedule['care_name'] = $care['name'];
       }
       
    }
    
 }

 //找case name
 foreach ($data['care_schedule'] as &$schedule) {
    foreach ($data['cases'] as $case) {
       if ($schedule['case_id'] == $case['user_id']){
          $schedule['case_name'] = $case['name'];
       }
    }
 }
  if($this->isUserLoggedIn and $role=='照服員' ){ 
    
    $this->load->view('be/header',$data); 
    $this->load->view('be/care_schedule',$data);
    
  }else{ 
    redirect('user/account'); 
  } 
 }
 //查詢個案班表
 function case_schedule(){
  $data['care_schedule'] = NULL;
  
  $role = $this->session->userdata('role');
  $id = $this->session->userdata('user_id');
  
  if($this->isUserLoggedIn and $role=='案主' ){ 
    
    $this->load->view('be/header',$data); 
    $this->load->view('be/case_schedule',$data);
    
  }else{ 
    redirect('user/account'); 
  } 
 }
 function get_case_schedule(){
  $role = $this->session->userdata('role');
  $id = $this->session->userdata('user_id');
  $month = $this->input->post('month');
  echo $month;
  //$month = substr($month,6);
  //
  $data['cares'] = $this->user_model->fetch_all_care();
  $data['cases'] = $this->user_model->fetch_all_case();
  //get care_id  and month schedule 
  $data['care_schedule'] = $this->schedule_model->get_case_schedule($id,$month);
  
  //找care name
  foreach ($data['care_schedule'] as &$schedule) {
    foreach ($data['cares'] as $care) {
       if ($schedule['uid'] == $care['user_id']){
          $schedule['care_name'] = $care['name'];
       }
       
    }
    
 }

 //找case name
 foreach ($data['care_schedule'] as &$schedule) {
    foreach ($data['cases'] as $case) {
       if ($schedule['case_id'] == $case['user_id']){
          $schedule['case_name'] = $case['name'];
       }
    }
 }
  if($this->isUserLoggedIn and $role=='案主' ){ 
    
    $this->load->view('be/header',$data); 
    $this->load->view('be/case_schedule',$data);
    
  }else{ 
    redirect('user/account'); 
  } 
 }

}

?>