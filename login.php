<?php
require_once 'includes/header.php';

if(isset($_SESSION['userId'])) {
	echo "<script>window.location.href=' /dashboard.php';</script>";
}

$errors = [];
if ($_POST) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	if (empty($username) || empty($password)) {
		$errors[] = "请输入用户名和密码";
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
						echo "<script>alert('Login successful.');</script>";
						echo "<script>window.location.href=' /dashboard.php'</script>";
						exit;
				} else {
						$errors[] = "不正确的用户名/密码";
				}
		} else {
				$errors[] = "用户名不存在";
		}
	}
}
?>
<div class='container'>
	<div class="d-flex justify-content-center row vertical col-lg-offset-2 col-lg-8">
		<div class="row col-lg-9 col-sm-12">
			<div class="col-md-12">
				<div class="messages">
					<?php if($errors) {
						foreach ($errors as $key => $value) {
							echo '<div class="alert alert-warning" role="alert">
							<i class="glyphicon glyphicon-exclamation-sign"></i>
							'.$value.'</div>';										
							}
						} ?>
				</div>
				<div class="login-block">
					<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
						<div class="form-group">
							<label for="username">用户名</label>
							<input class="form-control" type="text" placeholder="Username" name="username" id="username" autocorrect="off" autocapitalize="off">
						</div>
						<div class="form-group">
							<label for="password">密码</label>
							<input class="form-control" type="password" placeholder="Password" name="password" id="password">
							<p id="caps-lock-warning" class="text-danger hidden">
								<i class="fa fa-exclamation-triangle"></i> 大写已开启
							</p>
						</div>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember" id="remember" checked=""> 记住我的登录?
								</label>
							</div>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-lg btn-block" type="submit">会员登录</button>
							<div class='mt-3'>
								<span>如果你还没有加入会员？ <a href="register.php" style='color: #ffa105;'>注册会员</a></span>
								<br><a id="reset-link" href="/password_forgot.php">如果忘记密码?</a>
							</div>					
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once 'includes/footer.php'; ?>