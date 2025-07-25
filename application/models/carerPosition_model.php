<?php
class CarerPosition_model extends CI_Model {
    public function __construct() {
        $this->load->database();  // 載入資料庫
        $this->load->helper('url');
    }
    
    //取得所有照服員的姓名及id
    public function member_option() {
        $data = [];
        $sql = "SELECT `user_id`, `name` FROM `member_profile`
                WHERE `role`='照服員'";
        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                $rowData = [
                    'user_id' => $row->user_id,
                    'name' => $row->name
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }
    
    //查詢對應日期及姓名的紀錄
    public function search_record($date, $id) {
        $data = [];

        $sql = "SELECT track_record.*, member_profile.name
                FROM track_record
                LEFT JOIN member_profile
                ON track_record.uid = member_profile.user_id
                WHERE DATE(track_record.time) = '$date'
                AND track_record.uid = '$id'";
        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                $time = date('Y/m/d H:i', strtotime($row->time));

                $rowData = [
                    'track_id' => $row->track_id,
                    'uid' => $row->uid,
                    'name' => $row->name,
                    'time' => substr_replace($time, '&nbsp', 10, 0),
                    'lat' => $row->lat,
                    'lng' => $row->lng,
                    'track_status' => $row->track_status
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }

    //新增mqtt資料到追蹤紀錄資料表
    public function mqtt_add($data){
        $this->db->insert('track_record', $data);
        return;
    }
}
?>
