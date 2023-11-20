<?php
require_once 'includes/header.php';

session_start();

$errors = [];
if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        $errors[] = "Username and Password are required.";
    } else {
        // Implement proper SQL prepared statements instead of direct concatenation
        $stmt = $connect->prepare("SELECT user_id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $hashedPassword = $user['password'];
            // Use password_verify() instead of md5()
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['userId'] = $user['user_id'];
								$errors[] = $user['user_id'];

                header('location: /newnetbirds/dashboard.php');
                // exit;
            } else {
                $errors[] = "Incorrect username/password combination";
            }
        } else {
            $errors[] = "Username does not exist";
        }
    }
}
?>

<div class="row vertical">
	<div class="row col-lg-9 col-sm-12">
		<div class="col-md-12">
			<div class="login-block">
				<div class="messages">
					<?php if($errors) {
						foreach ($errors as $key => $value) {
							echo '<div class="alert alert-warning" role="alert">
							<i class="glyphicon glyphicon-exclamation-sign"></i>
							'.$value.'</div>';										
							}
						} ?>
				</div>

				<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
					<div class="form-group">
						<label for="username" class="col-lg-2 control-label">Username / Email</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" placeholder="Username / Email" name="username" id="username" autocorrect="off" autocapitalize="off">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-lg-2 control-label">Password</label>
						<div class="col-lg-10">
							<input class="form-control" type="password" placeholder="Password" name="password" id="password">
							<p id="caps-lock-warning" class="text-danger hidden">
								<i class="fa fa-exclamation-triangle"></i> Caps Lock is enabled
							</p>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember" id="remember" checked=""> Remember Me?
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
							<span>Don't have an account? <a href="register.php">Register</a></span>
							&nbsp; <a id="reset-link" href="/reset">Forgot Password?</a>
						</div>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>

<?php require_once 'includes/footer.php'; ?>