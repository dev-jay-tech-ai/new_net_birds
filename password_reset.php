<?php 

require_once 'includes/header.php'; 
include_once('core.php');

error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

if(isset($_REQUEST['pwdrst'])) {
  $email = $_REQUEST['email'];
  $password = $_REQUEST['pwd'];
  $confirmPassword = $_REQUEST['cpwd'];
  if($password == $confirmPassword) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $reset_pwd = mysqli_query($connect,"UPDATE users SET password='$hashedPassword' WHERE email='$email'");
    if($reset_pwd > 0) {
      $msg = 'Your password updated successfully <a href="/login.php">Click here</a> to login';
    } else {
      $msg = "Error while updating password.";
    }
  } else {
    $msg = "Password and Confirm Password do not match";
  }
}

if($_GET['secret']) {
  $email = base64_decode($_GET['secret']);
  $check_details = mysqli_query($connect,"SELECT email from users where email='$email'");
  $res = mysqli_num_rows($check_details);
  if($res>0)
{ ?>

<div class="container">  
<div class="d-flex justify-content-center row vertical">
  <div class="row col-lg-9 col-sm-12">
		<div class="col-lg-offset-2 col-lg-8">
    <div class="table-responsive">  
      <h3 align="center">Reset Password</h3><br/>
      <div class="box">
      <div class="messages">
      <?php if(!empty($msg)){ echo '<div class="alert alert-warning" role="alert">
        <i class="glyphicon glyphicon-exclamation-sign"></i>
        '.$msg.'</div>'; } ?>
			</div>
      <form id="validate_form" method="post" >  
        <input type="hidden" name="email" value="<?php echo $email; ?>"/>
        <div class="form-group">
        <label for="pwd">Password</label>
        <input type="password" name="pwd" id="pwd" placeholder="Enter Password" required 
        data-parsley-type="pwd" data-parsley-trigg
        er="keyup" class="form-control"/>
        </div>
        <div class="form-group">
        <label for="cpwd">Confirm Password</label>
        <input type="password" name="cpwd" id="cpwd" placeholder="Enter Confirm Password" required data-parsley-type="cpwd" data-parsley-trigg
        er="keyup" class="form-control"/>
        </div>
        <div class="form-group">
        <input type="submit" id="login" name="pwdrst" value="Reset Password" class="btn btn-primary btn-lg btn-block" />
        </div>
      </form>
      </div>
     </div>
    </div>
    </div>
   </div>  
  </div>

<?php } } ?>