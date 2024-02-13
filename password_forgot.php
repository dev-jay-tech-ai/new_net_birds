<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; 

require_once 'includes/header.php';
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$_EMAIL = $_ENV['EMAIL'];
$_PASSWORD = $_ENV['PASSWORD'];

if(isset($_REQUEST['pwdrst'])) {
  $email = $_REQUEST['email'];
  $check_email = mysqli_query($connect, "SELECT email FROM users WHERE email = '$email'");
  $res = mysqli_num_rows($check_email);
  if($res>0) {
    $message = '<div>
    <p><b>Hello!</b></p>
    <p>You are recieving this email because we recieved a password reset request for your account.</p>
    <br>
    <p><a href="https://newnetbirds.com/password_reset.php?secret='.base64_encode($email).'">Reset Password</a></p>
    <br>
    <p>If you did not request a password reset, no further action is required.</p>
    <br>
    <p>- Newnetbirds -</p>
    </div>';
    $email = $email; 
    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->SMTPAuth = true;                 
    $mail->SMTPDebug = 0;     
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->Username = $_EMAIL;   //Enter your username/emailid
    $mail->Password = $_PASSWORD;   //Enter your password
    $mail->setFrom('info@newnetbirds.com', 'Newnetbirds');
    $mail->AddAddress($email);
    $mail->Subject = "Reset Password";
    $mail->isHTML( TRUE );
    $mail->Body =$message;
    if($mail->send()) {
      $msg = "We have e-mailed your password reset link!"; 
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  } else {
    $msg = "We can't find a user with that email address";
  }
}
?>

<div class="container">  
  <div class="d-flex justify-content-center row vertical">
		<div class="row col-lg-9 col-sm-12">
			<div class="col-lg-offset-2 col-lg-8">
      <div class="table-responsive col-lg-offset-2 col-lg-8 border-0">  
      <h3 class="center">忘记密码</h3><br/>
      <div class="box">
      <div class="messages">
      <?php if(!empty($msg)){ echo '<div class="alert alert-warning" role="alert">
        <i class="glyphicon glyphicon-exclamation-sign"></i>
        '.$msg.'</div>'; } ?>
			</div>
      <form id="validate_form" method="post" >  
        <div class="form-group">
        <label for="email">电子邮件</label>
        <input type="text" name="email" id="email" placeholder="Enter Email" required 
        data-parsley-type="email" data-parsley-trigg
        er="keyup" class="form-control" />
        </div>
        <div class="form-group">
        <input type="submit" id="login" name="pwdrst" value="发送密码重置链接" class="btn btn-primary btn-lg btn-block" />
        </div>
      </form>
      </div>
      </div>
     </div>
    </div>
  </div>  
</div>