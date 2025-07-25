<?php
class PunchRecord_model extends CI_Model { 
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

    //查詢對應姓名的今日紀錄
    public function search_today($id) {
        $data = [];

        $sql = "SELECT punch_record.*, member_profile.name
                FROM punch_record
                LEFT JOIN member_profile
                ON punch_record.uid = member_profile.user_id
                WHERE DATE(punch_record.time) = CURDATE()
                AND punch_record.uid = '$id'";
        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                $time = date('Y/m/d H:i', strtotime($row->time));

                $rowData = [
                    'check_id' => $row->check_id,
                    'uid' => $row->uid,
                    'name' => $row->name,
                    'time' => substr_replace($time, '&nbsp', 10, 0),
                    'lat' => $row->lat,
                    'lng' => $row->lng,
                    'clock_in' => $row->clock_in
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }

    //查詢對應日期及姓名的打卡紀錄
    public function search_record($date, $id) {
        $data = [];

        $sql = "SELECT punch_record.*, member_profile.name
                FROM punch_record
                LEFT JOIN member_profile
                ON punch_record.uid = member_profile.user_id
                WHERE DATE(punch_record.time) = '$date'
                AND punch_record.uid = '$id'";
        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                $time = date('Y/m/d H:i', strtotime($row->time));

                $rowData = [
                    'check_id' => $row->check_id,
                    'uid' => $row->uid,
                    'name' => $row->name,
                    'time' => substr_replace($time, '&nbsp', 10, 0),
                    'lat' => $row->lat,
                    'lng' => $row->lng,
                    'clock_in' => $row->clock_in
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }
}
?>
