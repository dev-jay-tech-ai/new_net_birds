<?php 
  require_once 'includes/header.php'; 
  
  session_start();
 
  if(isset($_SESSION['userId'])) {
    echo "<script>window.location.href=' /newnetbirds/dashboard.php';</script>";
  }
?>

<div class="d-flex justify-content-center row vertical">
  <div class="row col-lg-9 col-sm-12">
    <div class="col-md-12">
      <div class="register-bxlock">
        <form class="form-horizontal" id='submitRegisterForm' action="php_action/createUser.php" method="POST">
          <div class="form-group">
            <label for="username" class="col-lg-2 control-label">Username</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" placeholder="Enter Username" name="username" id="username" autocorrect="off" autocapitalize="off" autocomplete="off">
              <!-- <span class="register-feedback" id="username-notify"></span> -->
              <span class="help-block">A unique username between 2 and 16 characters. Others can mention you with @<span id="yourUsername">username</span>.</span>
            </div>
          </div>
          <div class="form-group">
            <label for="password" class="col-lg-2 control-label">Password</label>
            <div class="col-lg-8">
              <input class="form-control" type="password" placeholder="Enter Password" name="password" id="password">
              <span class="register-feedback" id="password-notify"></span>
              <span class="help-block">Your password's length must be at least 6 characters.</span>
              <!-- <p id="caps-lock-warning" class="text-danger hidden">
                <i class="fa fa-exclamation-triangle"></i> Caps Lock is enabled
              </p> -->
            </div>
          </div>
          <div class="form-group">
            <label for="password-confirm" class="col-lg-2 control-label">Confirm Password</label>
            <div class="col-lg-8">
              <input class="form-control" type="password" placeholder="Confirm Password" name="passwordConfirm" id="passwordConfirm">
              <!-- <span class="register-feedback" id="password-confirm-notify"></span> -->
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8 mt-3">
              <button 
                class="btn btn-primary btn-lg btn-block" id="createAccountBtn" type="submit"
                id="createAccountBtn" data-loading-text="Loading..." autocomplete="off">Create Account</button>
            </div>
          </div>
        </form>  
        
      </div>
    </div>
  </div>
</div>

<script src="custom/js/register.js"></script>
<?php require_once 'includes/footer.php'; ?>