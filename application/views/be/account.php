<div class="container">
    <h2>歡迎 <?php echo $user['name']; ?>!</h2>
    <a href="<?php echo base_url('user/logout'); ?>" class="logout">Logout</a>
    <div class="regisFrm">
        <p><b>帳號id: </b><?php echo $user['user_id']; ?></p>
        <p><b>姓名: </b><?php echo $user['name']; ?></p>
        <p><b>權限: </b><?php echo $user['role']; ?></p>
    </div>
</div>