
<?php

class Schedule_model extends CI_Model
{
 function fetch_all_event(){
  $this->db->order_by('schedule_id');
  return $this->db->get('schedule');
 }

 function insert_event($data)
 {
  $this->db->insert('schedule', $data);
 }

 function update_event($data, $id)
 {
  $this->db->where('schedule_id', $id);
  $this->db->update('schedule', $data);
 }

 function delete_event($id)
 {
  $this->db->where('schedule_id', $id);
  $this->db->delete('schedule');
 }
 function fetch_get_month($month) {
   // 您的自定義 SQL 查詢
   $sql = "SELECT `uid`,`case_id`,`service_name`,TIMEDIFF(`service_time_end`, `service_time_start`) AS time_difference 
   FROM schedule WHERE MONTH(service_time_start) = $month;";

   // 使用 $this->db->query() 執行 SQL 查詢
   $query = $this->db->query($sql);

   // 檢查查詢是否成功
   if ($query) {
      // 返回結果集作為陣列
      return $query->result_array();
   } else {
      // 查詢失敗，返回空陣列或其他錯誤處理
      return array();
   } 
   }
   function get_care_schedule($id,$month){
      // 您的自定義 SQL 查詢
      $sql = "SELECT `uid`,`case_id`,`service_name`,`service_time_start`,`service_time_end`,TIMEDIFF(`service_time_end`, `service_time_start`) AS time_difference 
      FROM schedule WHERE DATE_FORMAT(`service_time_start`, '%Y-%m') = '$month' AND `uid`=$id;";

      // 使用 $this->db->query() 執行 SQL 查詢
      $query = $this->db->query($sql);

      // 檢查查詢是否成功
      if ($query) {
         // 返回結果集作為陣列
         return $query->result_array();
      } else {
         // 查詢失敗，返回空陣列或其他錯誤處理
         return array();
   } 
   }

   function get_case_schedule($id,$month){
      // 您的自定義 SQL 查詢
      $sql = "SELECT `uid`,`case_id`,`service_name`,`service_time_start`,`service_time_end`,TIMEDIFF(`service_time_end`, `service_time_start`) AS time_difference 
      FROM schedule WHERE  DATE_FORMAT(`service_time_start`, '%Y-%m') = '$month' AND `case_id`=$id;";

      // 使用 $this->db->query() 執行 SQL 查詢
      $query = $this->db->query($sql);

      // 檢查查詢是否成功
      if ($query) {
         // 返回結果集作為陣列
         return $query->result_array();
      } else {
         // 查詢失敗，返回空陣列或其他錯誤處理
         return array();
   } 
   }
}

?>