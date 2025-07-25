<?php

class Service_items_model extends CI_Model
{
   function fetch_all_service(){
      $query = $this->db->get('service_items'); // your_table 是您的資料表名稱
      return $query->result_array();
   }

 
}
