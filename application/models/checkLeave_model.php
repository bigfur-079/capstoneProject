<?php
class CheckLeave_model extends CI_Model { 
    public function __construct() {
        $this->load->database();  // 載入資料庫
        $this->load->helper('url');
    }

    //取得所有請假資料
    /*public function all_leave() {
        $data = [];
        $sql = "SELECT check_leave.*, member_profile.name
                FROM check_leave
                LEFT JOIN member_profile
                ON check_leave.uid=member_profile.user_id";
        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                $start = date('Y/m/d H:i', strtotime($row->start_time));
                $end = date('Y/m/d H:i', strtotime($row->end_time));

                $rowData = [
                    'leave_id' => $row->leave_id,
                    'uid' => $row->uid,
                    'name' => $row->name,
                    'start_time' => substr_replace($start, '&nbsp', 10, 0),
                    'end_time' => substr_replace($end, '&nbsp', 10, 0),
                    'category' => $row->category,
                    'reason' => $row->reason,
                    'check' => $row->check
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }*/

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

    //搜尋有無符合輸入條件的照服員id
    public function search_id($id) {
        $sql = "SELECT * FROM member_profile
                WHERE user_id='$id'";
        $data = $this->db->query($sql)->row_array();
        
        if($data != NULL)
            return $data['user_id'];
        else
            return NULL;
    }

    //新增請假資料至資料庫
    public function add_leave($data) {
        $this->db->insert('check_leave', $data);
        return;
    }

    //修改請假資料
    public function modify_leave($data, $id) {
        //根據 id 來更新資料
        $this->db->where('leave_id', $id);
        $this->db->update('check_leave', $data);
        return;
    }

    //刪除請假資料
    public function delete_leave($id) {
        //根據 id 來刪除資料
        $this->db->where('leave_id', $id);
        $this->db->delete('check_leave');
        return;
    }

    //依日期查詢資料
    public function searchDate_leave($date) {
        $data = [];

        $sql = "SELECT check_leave.*, member_profile.name
                FROM check_leave
                LEFT JOIN member_profile
                ON check_leave.uid=member_profile.user_id
                WHERE DATE(check_leave.start_time) <= '$date' 
                AND DATE(check_leave.end_time) >= '$date'";
        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                $start = date('Y/m/d H:i', strtotime($row->start_time));
                $end = date('Y/m/d H:i', strtotime($row->end_time));

                $rowData = [
                    'leave_id' => $row->leave_id,
                    'uid' => $row->uid,
                    'name' => $row->name,
                    'start_time' => substr_replace($start, '&nbsp', 10, 0),
                    'end_time' => substr_replace($end, '&nbsp', 10, 0),
                    'category' => $row->category,
                    'reason' => $row->reason,
                    'check' => $row->check
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }

    //依照服員uid查詢資料
    public function searchName_leave($id) {
        $data = [];

        $sql = "SELECT check_leave.*, member_profile.name
                FROM check_leave
                LEFT JOIN member_profile
                ON check_leave.uid=member_profile.user_id
                WHERE check_leave.uid='$id'";
        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                $start = date('Y/m/d H:i', strtotime($row->start_time));
                $end = date('Y/m/d H:i', strtotime($row->end_time));

                $rowData = [
                    'leave_id' => $row->leave_id,
                    'uid' => $row->uid,
                    'name' => $row->name,
                    'start_time' => substr_replace($start, '&nbsp', 10, 0),
                    'end_time' => substr_replace($end, '&nbsp', 10, 0),
                    'category' => $row->category,
                    'reason' => $row->reason,
                    'check' => $row->check
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }

    //查詢全部請假資料總數
    public function all_count() {
        $sql = "SELECT COUNT(*) AS C FROM `check_leave`";
        $rs = $this->db->query($sql);
        $data = $rs->row_array();

        return $data['C'];
    }

    //依照分頁從資料庫選擇資料
    public function all_page($offset, $size) {
        $data = [];
        $sql = "SELECT check_leave.*, member_profile.name
                FROM check_leave
                LEFT JOIN member_profile
                ON check_leave.uid=member_profile.user_id
                LIMIT $offset, $size";
        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                $start = date('Y/m/d H:i', strtotime($row->start_time));
                $end = date('Y/m/d H:i', strtotime($row->end_time));

                $rowData = [
                    'leave_id' => $row->leave_id,
                    'uid' => $row->uid,
                    'name' => $row->name,
                    'start_time' => substr_replace($start, '&nbsp', 10, 0),
                    'end_time' => substr_replace($end, '&nbsp', 10, 0),
                    'category' => $row->category,
                    'reason' => $row->reason,
                    'check' => $row->check
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }
}
?>
