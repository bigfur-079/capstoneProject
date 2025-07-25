

<main class="signup-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <h3 class="card-header text-center">註冊帳號</h3>
                    <div class="card-body">
                    <form action="" method="post">
                            
                            <div class="form-group mb-3">
                                <input type="text" placeholder="輸入帳號" id="account" class="form-control" name="account"
                                    required autofocus>
                                
                                    <?php echo form_error('account','<p class="help-block">','</p>'); ?>
                                
                            </div>
                            
                            <div class="form-group mb-3">
                                <input type="text" placeholder="輸入姓名" id="name" class="form-control" name="name"
                                    required autofocus>
                                
                                    <?php echo form_error('name','<p class="help-block">','</p>'); ?>
                                
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="輸入密碼" id="password" class="form-control"
                                    name="password" required>
                                
                                
                                
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="確認密碼" id="conf_password" class="form-control"
                                    name="conf_password" required>
                                
                                
                                
                            </div>

                            
                            <div class="form-group mb-3">
                                <label>性別: </label>
                                <?php 
                                    if(!empty($user['gender']) && $user['gender'] == '男'){ 
                                        $fcheck = 'checked="checked"'; 
                                        $mcheck = ''; 
                                    }else{ 
                                        $mcheck = 'checked="checked"'; 
                                        $fcheck = ''; 
                                    } 
                                    ?>
                                    <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="男" <?php echo $mcheck; ?>>
                                    男
                                </label>
                                <label>
                                    <input type="radio" name="gender" value="女" <?php echo $fcheck; ?>>
                                    女
                                </label>

                            </div>

                            <div class="form-group mb-3">
                                <label>身分: </label>
                                    
                                    <div class="radio">
                                <label>
                                    <input type="radio" name="role" value="管理者"  ?>
                                    管理者
                                </label>
                                <label>
                                    <input type="radio" name="role" value="照服員"  ?>
                                    照服員
                                </label>
                                <label>
                                    <input type="radio" name="role" value="案主"  ?>
                                    案主
                                </label>
                            </div>
                                  

                            <div class="form-group mb-3">
                                <input type="text" name="phone" placeholder="手機或電話" value="<?php echo !empty($user['phone'])?$user['phone']:''; ?>">
                                <?php echo form_error('phone','<p class="help-block">','</p>'); ?>
                            </div >
                            
                            <div class="d-grid mx-auto">
                                <button type="submit" name="signupSubmit"class="btn btn-dark btn-block" value="CREATE ACCOUNT">註冊</button>
                            </div>
                    </form>
                    <!-- Status message -->
                    <?php  
                        if(!empty($success_msg)){ 
                            echo '<p class="status-msg success">'.$success_msg.'</p>'; 
                        }elseif(!empty($error_msg)){ 
                            echo '<p class="status-msg error">'.$error_msg.'</p>'; 
                        } 
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>