<?php
require_once 'includes/header.php';

if(isset($_SESSION['userId'])) {
	echo "<script>window.location.href=' /newnetbirds/dashboard.php';</script>";
}

$errors = [];
if ($_POST) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	if (empty($username) || empty($password)) {
		$errors[] = "Username and Password are required.";
	} else {
		$stmt = $connect->prepare("SELECT user_id, password FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows == 1) {
				$user = $result->fetch_assoc();
				$hashedPassword = $user['password'];
				// Use password_verify() instead of md5()
				if (password_verify($password, $hashedPassword)) {
						$_SESSION['userId'] = $user['user_id'];
						// $errors[] = $user['user_id'];
						echo "<script>window.location.href=' /newnetbirds/dashboard.php';</script>";
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
<div class='container'>
	<div class="d-flex justify-content-center row vertical col-lg-offset-2 col-lg-8">
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
							<label for="username">Username</label>
							<input class="form-control" type="text" placeholder="Username" name="username" id="username" autocorrect="off" autocapitalize="off">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input class="form-control" type="password" placeholder="Password" name="password" id="password">
							<p id="caps-lock-warning" class="text-danger hidden">
								<i class="fa fa-exclamation-triangle"></i> Caps Lock is enabled
							</p>
						</div>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember" id="remember" checked=""> Remember Me?
								</label>
							</div>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
							<div class='mt-3'>
								<span>Don't have an account? <a href="register.php">Register</a></span>
								<br><a id="reset-link" href="/newnetbirds/password_forgot.php">Forgot Password?</a>
							</div>					
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once 'includes/footer.php'; ?>