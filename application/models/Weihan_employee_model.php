<?php
class Weihan_employee_model extends CI_Model {
  public $db_crypt_key2 = DB_CRYPT_KEY2; // 敏感個人資料加密key
  public $aes_fd = array('em01','em02','em03','em09','em10'); // 加密欄位
  public function __construct() {
    $this->load->database();
  }
  // **************************************************************************
  //  函數名稱: get_one()
  //  函數功能: 取得單筆資料
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function get_one($s_num) {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $tbl_account = $this->zi_init->chk_tbl_no_lang('sys_account'); // 登入帳號
    $s_num = (int)$this->db->escape_like_str($s_num);
    $row = NULL;
    $sql = "select {$tbl_weihan_employee}.*,
                   sys_acc.acc_name as b_acc_name,
                   sys_acc2.acc_name as e_acc_name,
                   case {$tbl_weihan_employee}.is_available
                     when '0' then '停用'
                     when '1' then '啟用'
                   end as is_available_str,
                   case {$tbl_weihan_employee}.em04
                     when 'M' then '男;F'
                   end as em04_str
                   {$this->_aes_fd()}
            from {$tbl_weihan_employee}
            left join {$tbl_account} sys_acc on sys_acc.s_num = {$tbl_weihan_employee}.b_empno
            left join {$tbl_account} sys_acc2 on sys_acc2.s_num = {$tbl_weihan_employee}.e_empno
            where {$tbl_weihan_employee}.d_date is null
                  and {$tbl_weihan_employee}.s_num = ?
            order by {$tbl_weihan_employee}.s_num desc
           ";
    //u_var_dump($sql);
    $rs = $this->db->query($sql, array($s_num));
    if($rs->num_rows() > 0) { // 有資料才執行
      $row = $rs->row();
      // 遮罩欄位處理 Begin //
      foreach ($this->aes_fd as $k => $v) {
        list($fd_name,$fd_val) = $this->_symbol_text($row,$v); // 遮罩欄位處理
        $row->$fd_name = $fd_val;
      } 
      // 遮罩欄位處理 End //
    }
    return $row;
  }
  // **************************************************************************
  //  函數名稱: chk_duplicate()
  //  函數功能: 檢查重複
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function chk_duplicate($fd_name) {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $fd_name = $this->db->escape_like_str($fd_name);
    $row = NULL;
    $sql = "select {$tbl_weihan_employee}.*
                   {$this->_aes_fd()}
            from {$tbl_weihan_employee}
            where {$tbl_weihan_employee}.d_date is null
                  and {$tbl_weihan_employee}.fd_name = ?
            order by {$tbl_weihan_employee}.s_num desc
           ";
    //u_var_dump($sql);
    $rs = $this->db->query($sql, array($fd_name));
    if($rs->num_rows() > 0) { // 資料重複
      $row = $rs->row(); 
      // 遮罩欄位處理 Begin //
      foreach ($this->aes_fd as $k => $v) {
        list($fd_name,$fd_val) = $this->_symbol_text($row,$v);
        $row->$fd_name = $fd_val;
      }
      // 遮罩欄位處理 End //
    }
    return $row;
  }
  // **************************************************************************
  //  函數名稱: get_all()
  //  函數功能: 取得所有資料
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function get_all() {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $data = NULL;
    $sql = "select {$tbl_weihan_employee}.*
                   {$this->_aes_fd()}
            from {$tbl_weihan_employee}
            where {$tbl_weihan_employee}.d_date is null
            order by {$tbl_weihan_employee}.s_num desc
           ";
    //u_var_dump($sql);
    $rs = $this->db->query($sql, array($sql));
    if($rs->num_rows() > 0) { // 有資料才執行
      foreach ($rs->result() as $row){
        // 將所有欄位讀取到 $data
        foreach ($row as $fd_name => $v){
          $data[$row->s_num][$fd_name] = $row->$fd_name;
          // 遮罩欄位處理 Begin //
          list($fd_name,$fd_val) = $this->_symbol_text($row,$fd_name); // 遮罩欄位處理
          $data[$row->s_num][$fd_name] = $fd_val;
          // 遮罩欄位處理 End //
        }
      }
    }
    return $data;
  }
  // **************************************************************************
  //  函數名稱: get_all_is_available()
  //  函數功能: 取得所有已經啟用的資料
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function get_all_is_available() {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $data = NULL;
    $sql = "select {$tbl_weihan_employee}.*
                   {$this->_aes_fd()}
            from {$tbl_weihan_employee}
            where {$tbl_weihan_employee}.d_date is null
                  and {$tbl_weihan_employee}.is_available = 1 /* 啟用 */
            order by {$tbl_weihan_employee}.s_num desc
           ";
    //u_var_dump($sql);
    $rs = $this->db->query($sql, array($sql));
    if($rs->num_rows() > 0) { // 有資料才執行
      foreach ($rs->result() as $row){
        // 將所有欄位讀取到 $data
        foreach ($row as $fd_name => $v){
          $data[$row->s_num][$fd_name] = $row->$fd_name;
          // 遮罩欄位處理 Begin //
          list($fd_name,$fd_val) = $this->_symbol_text($row,$fd_name);
          $data[$row->s_num][$fd_name] = $fd_val;
          // 遮罩欄位處理 End //
        }
      }
    }
    return $data;
  }
  // **************************************************************************
  //  函數名稱: get_que()
  //  函數功能: 取得查詢資料
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function get_que($q_str=NULL,$pg=NULL) {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $where = " {$tbl_weihan_employee}.d_date is null ";
    $order = " {$tbl_weihan_employee}.s_num desc ";
    $get_data = $this->input->get();
    if(!empty($get_data['que_str'])) { // 全文檢索
      $que_str = $get_data['que_str'];
      $where .= " and (AES_DECRYPT({$tbl_weihan_employee}.em01,'{$this->db_crypt_key2}') like '%{$que_str}%' /* 中文姓 */                       
                         or AES_DECRYPT({$tbl_weihan_employee}.em02,'{$this->db_crypt_key2}') like '%{$que_str}%' /* 中文名 */                         or AES_DECRYPT({$tbl_weihan_employee}.em03,'{$this->db_crypt_key2}') like '%{$que_str}%' /* 身份證字號 */                        or {$tbl_weihan_employee}.em04 like '%{$que_str}%' /* 性別-OPT(M=男;F=女;) */                       
                        or {$tbl_weihan_employee}.em05 like '%{$que_str}%' /* 員工編號 */                       
                        or {$tbl_weihan_employee}.em_pwd like '%{$que_str}%' /* 登入密碼 */                       
                        or {$tbl_weihan_employee}.em06 like '%{$que_str}%' /* Email Address */                       
                        or BINARY {$tbl_weihan_employee}.em07_start like BINARY '%{$que_str}%' /* 到職日 */                       
                        or BINARY {$tbl_weihan_employee}.em07_end like BINARY '%{$que_str}%' /* 離職日 */                       
                        or {$tbl_weihan_employee}.em08 like '%{$que_str}%' /* 年資 */                       
                         or AES_DECRYPT({$tbl_weihan_employee}.em09,'{$this->db_crypt_key2}') like '%{$que_str}%' /* 手機門號 */                         or AES_DECRYPT({$tbl_weihan_employee}.em10,'{$this->db_crypt_key2}') like '%{$que_str}%' /* 家裡電話 */                        or {$tbl_weihan_employee}.em11 like '%{$que_str}%' /* 員工照片 */                       
                        or {$tbl_weihan_employee}.em99 like '%{$que_str}%' /* 備註 */                       
                        or {$tbl_weihan_employee}.em_login_cnt like '%{$que_str}%' /* 登入次數 */                       
                        or BINARY {$tbl_weihan_employee}.em_login_last_date like BINARY '%{$que_str}%' /* 最後登入日期時間 */                       
                        or {$tbl_weihan_employee}.em_login_last_ip like '%{$que_str}%' /* 最後登入IP */
                      )
                ";
    }

    if(!empty($get_data['que_em01'])) { // 中文姓
      $que_em01 = $get_data['que_em01'];
      $que_em01 = $this->db->escape_like_str($que_em01);
      $where .= " and AES_DECRYPT({$tbl_weihan_employee}.em01,'{$this->db_crypt_key2}') = '{$que_em01}'  /* 中文姓 */ ";
    }
    if(!empty($get_data['que_em02'])) { // 中文名
      $que_em02 = $get_data['que_em02'];
      $que_em02 = $this->db->escape_like_str($que_em02);
      $where .= " and AES_DECRYPT({$tbl_weihan_employee}.em02,'{$this->db_crypt_key2}') = '{$que_em02}'  /* 中文名 */ ";
    }
    if(!empty($get_data['que_em05'])) { // 員工編號
      $que_em05 = $get_data['que_em05'];
      $que_em05 = $this->db->escape_like_str($que_em05);
      $where .= " and {$tbl_weihan_employee}.em05 = '{$que_em05}'  /* 員工編號 */ ";
    }

    if(''<>$_SESSION[$q_str]['que_order_fd_name']) { // 排序欄位
      $que_order_fd_name = $_SESSION[$q_str]['que_order_fd_name'];
      $que_order_kind = $this->db->escape_like_str($_SESSION[$q_str]['que_order_kind']);
      $order = " {$que_order_fd_name} {$que_order_kind} ";
    }
    
    // 計算查詢條件後的總筆數
    $sql_cnt = "select {$tbl_weihan_employee}.s_num
                from {$tbl_weihan_employee}
                where $where
                group by {$tbl_weihan_employee}.s_num
                order by {$tbl_weihan_employee}.s_num
               ";
    //u_var_dump($sql_cnt);
    $rs_cnt = $this->db->query($sql_cnt, array($sql_cnt));
    $row_cnt = $rs_cnt->num_rows();
    
    $data = NULL;
    $limit_s = (($pg-1)*PG_QTY);
    $limit_e = PG_QTY;
    $limit = "limit {$limit_s},{$limit_e}";
    $sql = "select {$tbl_weihan_employee}.*,
                   case {$tbl_weihan_employee}.em04
                     when 'M' then '男;F'
                   end as em04_str
                   {$this->_aes_fd()}
            from {$tbl_weihan_employee}
            where {$where}
            order by {$order}
            {$limit}
           ";
    //u_var_dump($sql);
    $rs = $this->db->query($sql);
    if($rs->num_rows() > 0) { // 有資料才執行
      foreach ($rs->result() as $row){
        // 將所有欄位讀取到 $data
        foreach ($row as $fd_name => $v){
          $data[$row->s_num][$fd_name] = $row->$fd_name;
          // 遮罩欄位處理 Begin //
          list($fd_name,$fd_val) = $this->_symbol_text($row,$fd_name);
          $data[$row->s_num][$fd_name] = $fd_val;
          // 遮罩欄位處理 End //
        }
      }
    }
    return(array($data,$row_cnt));
  }
  // **************************************************************************
  //  函數名稱: save_add()
  //  函數功能: 新增儲存資料
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function save_add() {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $rtn_msg = 'ok';
    $post_data = $this->input->post();
    // 加密欄位處理 Begin //
    foreach ($post_data as $k_fd_name => $v_data) {
      if(in_array($k_fd_name,$this->aes_fd)) { // 加密欄位
        $this->db->set($k_fd_name, "AES_ENCRYPT('{$v_data}','{$this->db_crypt_key2}')", FALSE);
        unset($post_data[$k_fd_name]);
      }
    }
    // 加密欄位處理 End //
    $post_data['b_empno'] = $_SESSION['acc_s_num'];
    $post_data['b_date'] = date('Y-m-d H:i:s');
    
    if(!$this->db->insert($tbl_weihan_employee, $post_data)) {
      $rtn_msg = $this->lang->line('add_ng'); // 新增失敗!!
    }
    echo $rtn_msg;
    return;
  }
  // **************************************************************************
  //  函數名稱: save_upd()
  //  函數功能: 修改儲存資料
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function save_upd() {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $rtn_msg = 'ok';
    $post_data = $this->input->post();
    // 加密欄位處理 Begin //
    foreach ($post_data as $k_fd_name => $v_data) {
      if(in_array($k_fd_name,$this->aes_fd)) { // 加密欄位
        $this->db->set($k_fd_name, "AES_ENCRYPT('{$v_data}','{$this->db_crypt_key2}')", FALSE);
        unset($post_data[$k_fd_name]);
      }
    } 
    // 加密欄位處理 End //
    $post_data['e_empno'] = $_SESSION['acc_s_num'];
    $post_data['e_date'] = date('Y-m-d H:i:s');
    
    $this->db->where('s_num', $post_data['s_num']);
    if(!$this->db->update($tbl_weihan_employee, $post_data)) {
      $rtn_msg = $this->lang->line('upd_ng'); // 修改失敗!!
    }
    echo $rtn_msg;
    return;
  }
  // **************************************************************************
  //  函數名稱: save_is_available()
  //  函數功能: 儲存上下架
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function save_is_available() {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $rtn_msg = 'ok';
    $f_s_num = $_POST['f_s_num'];
    $f_is_available = $_POST['f_is_available'];
    $data['is_available'] = $f_is_available;
    $data['e_empno'] = $_SESSION['acc_s_num'];
    $data['e_date'] = date('Y-m-d H:i:s');
    
    $this->db->where('s_num', $f_s_num);
    if(!$this->db->update($tbl_weihan_employee, $data)) {
      $rtn_msg = $this->lang->line('upd_ng'); // 修改失敗!!
    }
    echo $rtn_msg;
    return;
  }
  // **************************************************************************
  //  函數名稱: del()
  //  函數功能: 刪除資料
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  public function del() {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $data = NULL;
    $v = $this->input->post();
    $rtn_msg = 'ok';
    $data['d_empno'] = $_SESSION['acc_s_num'];
    $data['d_date']  = date('Y-m-d H:i:s');
    
    $this->db->where('s_num', $v['s_num']);
    if(!$this->db->update($tbl_weihan_employee, $data)) {
      $rtn_msg = $this->lang->line('del_ng'); // 刪除失敗
    }
    echo $rtn_msg;
    return;
  }
  // **************************************************************************
  //  函數名稱: _aes_fd()
  //  函數功能: 將加密欄位解密
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  private function _aes_fd() {
    $tbl_weihan_employee = $this->zi_init->chk_tbl_no_lang('weihan_employee'); // 員工基本資料檔
    $aes_fd = "";
    foreach ($this->aes_fd as $k_fd_name => $v_fd_name) {
      $aes_fd .= ",AES_DECRYPT({$tbl_weihan_employee}.{$v_fd_name},'{$this->db_crypt_key2}') as {$v_fd_name}\n";
    }
    return($aes_fd);
  }
  // **************************************************************************
  //  函數名稱: _symbol_text()
  //  函數功能: 顯示遮罩資料
  //  程式設計: wei
  //  設計日期: 2023-10-01
  // **************************************************************************
  private function _symbol_text($row,$fd_name) {
    $fd_name_mask = '';
    $fd_val = NULL;
    if(isset($row->$fd_name)) {
      $fd_val = $row->$fd_name;
    }
    switch($fd_name) { // 檢查要遮罩的欄位名稱
      case 'em01': // 中文姓
        $fd_name_mask = "{$fd_name}_mask";
        $fd_val = replace_symbol_text($row->$fd_name,'*',1,1);
        break;
      case 'em02': // 中文名
        $fd_name_mask = "{$fd_name}_mask";
        $fd_val = replace_symbol_text($row->$fd_name,'*',1,1);
        break;
      case 'em03': // 身份證字號
        $fd_name_mask = "{$fd_name}_mask";
        $fd_val = replace_symbol_text($row->$fd_name,'*',1,1);
        break;
      case 'em10': // 家裡電話
        $fd_name_mask = "{$fd_name}_mask";
        $fd_val = replace_symbol_text($row->$fd_name,'*',1,1);
        break;
    }
    if('' != $fd_name_mask) {
      $fd_name = $fd_name_mask;
    }
    return(array($fd_name,$fd_val));
  }
}
?>