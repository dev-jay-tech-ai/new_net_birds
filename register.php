<?php 
  require_once 'includes/header.php'; 
  
  session_start();
 
  if(isset($_SESSION['userId'])) {
    echo "<script>window.location.href=' /dashboard.php';</script>";
    exit();
  }
  
?>
<div class="alert alert-success" style="display: none;">
    <strong>Success!</strong> Your account has been successfully created.
</div>

<div class="d-flex justify-content-center row vertical">
  <div class="row col-lg-9 col-sm-12">
    <div class="col-lg-offset-2 col-lg-8">
      <div class="register-bxlock col-lg-offset-2 col-lg-8">
        <form class="form-horizontal" id='submitRegisterForm' action="php_action/createUser.php" method="POST">
          <div class="form-group">
            <label for="username">Username</label>
              <input class="form-control" type="text" placeholder="Enter Username" name="username" id="username" autocorrect="off" autocapitalize="off" autocomplete="off">
              <!-- <span class="register-feedback" id="username-notify"></span> -->
              <span class="help-block">A unique username between 2 and 16 characters.
          </div>
          <div class="form-group">
            <label for="email">Email</label>
              <input class="form-control" type="text" placeholder="Enter Email" name="email" id="email" autocorrect="off" autocapitalize="off" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
              <input class="form-control" type="password" placeholder="Enter Password" name="password" id="password">
              <span class="register-feedback" id="password-notify"></span>
              <span class="help-block">Your password's length must be at least 6 characters.</span>
          </div>
          <div class="form-group">
            <label for="password-confirm">Confirm Password</label>
            <input class="form-control" type="password" placeholder="Confirm Password" name="passwordConfirm" id="passwordConfirm">
            <!-- <span class="register-feedback" id="password-confirm-notify"></span> -->
          </div>
          <div class="form-group">
            <button 
              class="btn btn-primary btn-lg btn-block" id="createAccountBtn" type="submit"
              id="createAccountBtn" data-loading-text="Loading..." autocomplete="off">Create Account</button>
          </div>
        </form>  
        
      </div>
    </div>
  </div>
</div>

<script src="custom/js/register.js"></script>
<?php require_once 'includes/footer.php'; ?>