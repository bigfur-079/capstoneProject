<!DOCTYPE html>
<html>
<head>
    <title>長照管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,intial-scale=1">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<body>
    <nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand mr-auto" href="#">長照管理</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    
                <?php if ($this->session->userdata('user_id') and $this->session->userdata('role')=='管理者') : // 檢查用戶是否已登入 ?>
                    <!-- 這裡放登入後需要顯示的連結 -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>manage_user">會員管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>schedule">排班表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>salary">薪水計算</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>checkLeave">檢核請假</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>punchrecord">打卡紀錄</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>carerPosition">照服員位置顯示</a>
                    </li>
                <?php endif; ?>
                <?php if ($this->session->userdata('user_id') and $this->session->userdata('role')=='照服員') :?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>schedule/care_schedule">查詢班表</a>
                    </li>
                <?php endif; ?>
                <!-- 案主班表-->
                <?php if ($this->session->userdata('user_id') and $this->session->userdata('role')=='案主') :?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>schedule/case_schedule">案主班表</a>
                    </li>
                <?php endif; ?>
                
                

                <?php if ($this->session->userdata('user_id')):?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>user/logout">登出</a>
                    </li>
                
                <?php else: ?>
                    <!-- 這裡放未登入時顯示的連結 -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>user/login">登入</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>user/register">註冊</a>
                    </li>
                <?php endif; ?>
                    
                </ul>
            </div>
        </div>
    </nav>
