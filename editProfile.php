<?php 
require_once 'includes/header.php'; 

if(!isset($_SESSION['userId'])) {
	echo "<script>window.location.href=' /dashboard.php';</script>";
} 

$user_id = $_SESSION['userId'];
$sql = "SELECT * FROM users WHERE user_id = {$user_id}";
$query = $connect->query($sql);
$result = $query->fetch_assoc();
$initial = strtoupper(mb_substr($result['username'], 0, 1, 'UTF-8'));

?>
<div class='container'>
	<div class="d-flex justify-content-center row col-lg-offset-2 col-lg-8">
		<div class="row col-lg-9 col-sm-12">
			<div class="col-md-12">
				<div class="login-block">
					<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
            <div class="form-group d-flex justify-content-center">
							<input type='hidden' class="form-control" type="text" name="profile" id="profile" autocorrect="off" autocapitalize="off">
              <div class='profile_img'><?= $initial ?></div>
            </div>	
            <div class="form-group">
							<label for="username">Username</label>
							<input class="form-control" type="text" placeholder="Username" name="username" 
							id="username" value="<?= $result['username'] ?>" autocorrect="off" autocapitalize="off">
						</div>
            <div class="form-group">
							<label for="username">Email</label>
							<input class="form-control" type="text" placeholder="email" name="email" 
							id="email" value="<?= $result['email'] ?>" autocorrect="off" autocapitalize="off">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input class="form-control" type="password" placeholder="Password" name="password" id="password">
							<p id="caps-lock-warning" class="text-danger hidden">
								<i class="fa fa-exclamation-triangle"></i> Caps Lock is enabled
							</p>
						</div>
						<div class="form-group">
							<label for="password">Re-password</label>
							<input class="form-control" type="password" placeholder="Password" name="password" id="password">
							<p id="caps-lock-warning" class="text-danger hidden">
								<i class="fa fa-exclamation-triangle"></i> Caps Lock is enabled
							</p>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-lg btn-block mt-3" type="submit">Update</button>				
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>