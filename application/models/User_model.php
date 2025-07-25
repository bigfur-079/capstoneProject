<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class User_model extends CI_Model{ 
    function __construct() { 
        // Set table name 
        $this->table = 'member_profile'; 
    } 
     
    /* 
     * Fetch user data from the database 
     * @param array filter data based on the passed parameters 
     */ 
    function getRows($params = array()){ 
        $this->db->select('*'); 
        $this->db->from($this->table); 
         
        if(array_key_exists("conditions", $params)){ 
            foreach($params['conditions'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        }
         
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        }else{ 
            if(array_key_exists("user_id", $params) || $params['returnType'] == 'single'){ 
                if(!empty($params['user_id'])){ 
                    $this->db->where('user_id', $params['user_id']); 
                } 
                $query = $this->db->get(); 
                $result = $query->row_array(); 
            }else{ 
                $this->db->order_by('user_id', 'desc'); 
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 
                 
                $query = $this->db->get(); 
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
            } 
        } 
         
        // Return fetched data 
        return $result; 
    } 
     
    /* 
     * Insert user data into the database 
     * @param $data data to be inserted 
     */ 
    public function insert($data = array()) { 
        if(!empty($data)){ 
            // Add created and modified date if not included 
            if(!array_key_exists("created", $data)){ 
                $data['login_success_time'] = date("Y-m-d H:i:s"); 
            } 
            if(!array_key_exists("modified", $data)){ 
                $data['login_fail_time'] = date("Y-m-d H:i:s"); 
            } 
             
            // Insert member data 
            $insert = $this->db->insert($this->table, $data); 
             
            // Return the status 
            return $insert?$this->db->insert_id():false; 
        } 
        return false; 
    } 
    //獲得照護員資料
    function fetch_all_care(){
        $sql = "select * from member_profile where role='照服員'";
        $query = $this->db->query($sql); // your_table 是您的資料表名稱
        return $query->result_array();
    }
    //get case data 
    function fetch_all_case(){
        $sql = "select * from member_profile where role='案主'";
        $query = $this->db->query($sql); // your_table 是您的資料表名稱
        return $query->result_array();
    }
    //獲取所有user的資料
    function all_count() {
        $sql = "SELECT COUNT(*) AS C FROM `member_profile`";
        $rs = $this->db->query($sql);
        $data = $rs->row_array();

        return $data['C'];
    }

    //依照分頁從資料庫選擇資料
    public function all_page($offset, $size) {
        $data = [];
        $sql = "SELECT *
                FROM member_profile
                LIMIT $offset, $size";
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                

                $rowData = [
                    'user_id' => $row->user_id,
                    'name' => $row->name,
                    'role' => $row->role,
                    'gender' => $row->gender,
                    'birthday' => $row->birthday,
                    'phone_one' => $row->phone_one,
                    'phone_two' => $row->phone_two,
                    'email' => $row->email,
                    'address' => $row->address,
                    'account' => $row->account
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }

    public function modify_user($data, $id) {
        //根據 id 來更新資料
        $this->db->where('user_id', $id);
        $this->db->update('member_profile', $data);
        return;
    }

    //刪除會員資料
    public function delete_user($id) {
        //根據 id 來刪除資料
        $this->db->where('user_id', $id);
        $this->db->delete('member_profile');
        return;
    }

    //新增會員資料
    public function add_user($data) {
       
        $this->db->insert('member_profile',$data);
        return;
    }
    public function member_option() {
        $data = [];
        $sql = "SELECT `user_id`, `name` FROM `member_profile`";
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

    public function search_id($id) {
        $sql = "SELECT * FROM member_profile
                WHERE user_id='$id'";
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0) { // 有資料才執行
            foreach ($rs->result() as $row){ // 將所有欄位讀取到 $data
                //轉換日期格式
                

                $rowData = [
                    'user_id' => $row->user_id,
                    'name' => $row->name,
                    'role' => $row->role,
                    'gender' => $row->gender,
                    'birthday' => $row->birthday,
                    'phone_one' => $row->phone_one,
                    'phone_two' => $row->phone_two,
                    'email' => $row->email,
                    'address' => $row->address,
                    'account' => $row->account
                ];
                $data[] = $rowData;
            }
        }
        return $data;
    }
}
