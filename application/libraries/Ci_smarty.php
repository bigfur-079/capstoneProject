<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH. "libraries/smarty-4.2.1/libs/Smarty.class.php");
class Ci_smarty extends Smarty {
  function __construct(){
    parent::__construct();
    // 設定 Smarty 參數
    $this->left_delimiter = '{{';
    $this->right_delimiter = '}}';
    $this->template_dir = APPPATH . 'views'; // 設定所有樣板檔放置位置
    $this->compile_dir = APPPATH . 'cache/templates_c'; // 設定Smarty編譯過的樣板檔放置位置
    $this->cache_dir = APPPATH . 'cache/scache'; // 設定快取檔放置位置
    $this->compile_check = true; // 檢查樣板檔從上次編譯後，是否有被修改過
    $this->force_compile = true; // true=每次都重新編譯
    $this->caching = false; // 是否建立快取
    // $this->cache_lifetime = 86400; // 設定快取檔案的過期時間
  }
}
?>
