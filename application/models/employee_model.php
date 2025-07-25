<?php
class Employee_model extends CI_Model { // 宣告一個employee_model類別並繼承CI_Model。
    public function __construct() {
        $this->load->database();  // 載入資料庫
        $this->load->helper('url');
    }
    
    public function get_all_employees() { // 取得所有書籍資料
        $data = NULL;
        $sql = "SELECT *,
                    CASE em02
                        WHEN '1' THEN '男生'
                        WHEN '2' THEN '女生'
                        WHEN '3' THEN '其他'
                    END AS em02_str
                FROM `employee`";
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                foreach ($row as $fd_name => $v){
                    $data[$row->id][$fd_name] = $row->$fd_name;
                }
            }
        }
        return $data;
    }

    public function add_employee() { //新增資料至資料庫
        $data = array(
            'em01' => $this->input->post('name'),
            'em02' => $this->input->post('sex'),
            'em03' => $this->input->post('birthday'),
            'em04' => $this->input->post('email'),
            'em05' => $this->input->post('phone'),
            'em06' => $this->input->post('else')
        );
    
        return $this->db->insert('employee', $data);
    }

    public function delete_employee($id) { //刪除此id的資料
        $data = array('id' => $id);

        return $this->db->delete('employee', $data);
    }

    public function where_array($id){ //搜尋此id的員工
        $data = NULL;
        $sql = "SELECT *,
                    CASE em02
                        WHEN '1' THEN '男生'
                        WHEN '2' THEN '女生'
                        WHEN '3' THEN '其他'
                    END AS em02_str
                FROM `employee` WHERE `id`=$id";
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                foreach ($row as $fd_name => $v){
                    $data[$row->id][$fd_name] = $row->$fd_name;
                }
            }
        }
        return $data;
    }

    public function modify_employee($id) { //修改資料庫資料
        $data = array(
            'em01' => $this->input->post('name'),
            'em02' => $this->input->post('sex'),
            'em03' => $this->input->post('birthday'),
            'em04' => $this->input->post('email'),
            'em05' => $this->input->post('phone'),
            'em06' => $this->input->post('else')
        );

        return $this->db->update('employee', $data, "id = $id");
    }

    public function where_inquire() { //搜尋此姓名的員工，修改用
        $data = NULL;
        $name = $this->input->post('name');
        $sql = "SELECT *, CASE em02
                    WHEN '1' THEN '男生'
                    WHEN '2' THEN '女生'
                    WHEN '3' THEN '其他'
                    END AS em02_str
                FROM `employee` WHERE `em01`='$name'";
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                foreach ($row as $fd_name => $v){
                    $data[$row->id][$fd_name] = $row->$fd_name;
                }
            }
        }
        else {
            echo "<script type='text/javascript'>
            alert('沒有此員工資料!');
            window.location.href='.';
            </script>";
        }
        return $data;
    }

    public function count() { //查詢資料庫資料總數
        $sql = "SELECT COUNT(*) AS C FROM `employee`";
        $rs = $this->db->query($sql);
        $data = $rs->row_array();

        return $data['C'];
    }

    public function page($offset, $size) { //依照分頁從資料庫選擇資料
        $data = NULL;
        $sql = "SELECT *,
                    CASE em02
                        WHEN '1' THEN '男生'
                        WHEN '2' THEN '女生'
                        WHEN '3' THEN '其他'
                    END AS em02_str
                FROM `employee` LIMIT $offset, $size";
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                foreach ($row as $fd_name => $v){
                    $data[$row->id][$fd_name] = $row->$fd_name;
                }
            }
        }
        return $data;
    }
}
?>
