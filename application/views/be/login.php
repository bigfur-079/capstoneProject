<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <h3 class="card-header text-center">登入</h3>
                    <div class="card-body">
                    <form action="" method="post">
                            
                            <div class="form-group mb-3">
                                <input type="text" placeholder="輸入帳號" id="account" class="form-control" name="account" required
                                    autofocus>
                                <?php echo form_error('email','<p class="help-block">','</p>'); ?>
                                
                                
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" placeholder="輸入密碼" id="password" class="form-control" name="password" required>
                                <?php echo form_error('password','<p class="help-block">','</p>'); ?>
                            </div>
                            <div class="form-group mb-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> 記住我
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-dark btn-block"  name="loginSubmit" value="LOGIN">登入</button>
                            </div>
                            
                        </form>
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
    </div>
</main>
</body>
</html>