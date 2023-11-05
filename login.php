<?php require_once 'includes/header.php'; ?>

<div class="row vertical">
	<div class="row col-lg-9 col-sm-12">
		<div class="col-md-12">
			<div class="login-block">
				<div class="alert alert-danger" id="login-error-notify" style="display: none;">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>Login Unsuccessful</strong>
					<p></p>
				</div>
				<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
					<div class="form-group">
						<label for="username" class="col-lg-2 control-label">Username / Email</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" placeholder="Username / Email" name="username" id="username" autocorrect="off" autocapitalize="off" value="">
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
					<input type="hidden" name="_csrf" value="9e94f21bfcdaddb65307323f3992e6aa5fe1357fa2309160f6414e341082dc5a724fda6ddb1ccf606ea124ad23b2fdcc5603dff2cf7bd416118bd92a363d89f5">
					<input type="hidden" name="noscript" id="noscript" value="false">
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button class="btn btn-primary btn-lg btn-block" id="login" type="submit">Login</button>
							<span>Don't have an account? <a href="register.php">Register</a></span>
							&nbsp; <a id="reset-link" href="/reset">Forgot Password?</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="custom/js/login.js"></script>

<?php require_once 'includes/footer.php'; ?>


