<?php 
  require_once 'includes/header.php'; 
  
  session_start();
 
  if(isset($_SESSION['userId'])) {
    echo "<script>window.location.href='/dashboard.php';</script>";
    exit();
  }
?>
<div class="alert alert-success" style="display: none;">
  会员申请成功 请登录
</div>
<div class="d-flex justify-content-center row vertical">
  <div class="row col-lg-9 col-sm-12">
    <div class="col-lg-offset-2 col-lg-8">
      <div class="register-bxlock col-lg-offset-2 col-lg-8">
        <form class="form-horizontal" id='submitRegisterForm' action="php_action/createAccount.php" method="POST">
          <div class="form-group">
            <label for="username">用户名</label>
              <input class="form-control" type="text" placeholder="Enter Username" name="username" id="username" autocorrect="off" autocapitalize="off" autocomplete="off">
              <span class="help-block">请使用2-16位汉字或者字母</span>
          </div>
          <div class="form-group">
            <label for="email">电子邮箱</label>
              <input class="form-control" type="text" placeholder="Enter Email" name="email" id="email" autocorrect="off" autocapitalize="off" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="password">密码</label>
              <input class="form-control" type="password" placeholder="Enter Password" name="password" id="password">
              <span class="register-feedback" id="password-notify"></span>
              <span class="help-block">请输入密码2-16位字母</span>
          </div>
          <div class="form-group">
            <label for="password-confirm">请确认密码</label>
            <input class="form-control" type="password" placeholder="Confirm Password" name="passwordConfirm" id="passwordConfirm">
            <!-- <span class="register-feedback" id="password-confirm-notify"></span> -->
          </div>
          <div class="form-group">
            <button 
              class="btn btn-primary btn-lg btn-block" id="createAccountBtn" type="submit"
              id="createAccountBtn" data-loading-text="Loading..." autocomplete="off">请确认密码</button>
          </div>
        </form>  
        
      </div>
    </div>
  </div>
</div>

<script src="custom/js/register.js"></script>
<?php require_once 'includes/footer.php'; ?>