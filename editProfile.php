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
				<div class="spinner-border" role="status">
					<span class="visually-hidden">Loading...</span>
				</div>
				<div class="login-block">
					<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
            <div class="form-group d-flex justify-content-center">
						<input type='hidden' class="form-control" type="text" name="profile" id="profile" autocorrect="off" autocapitalize="off">
              <div class='profile_img'><?= $initial ?></div>
            </div>	
            <div class="form-group">
							<input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION['userId'] ?>">
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
							<input class="form-control" type="password" placeholder="Re-password" name="passwordConfirm" id="passwordConfirm">
							<p id="caps-lock-warning" class="text-danger hidden">
								<i class="fa fa-exclamation-triangle"></i> Caps Lock is enabled
							</p>
						</div>
						<div class="form-group">
							<button id='btn_submit' class="btn btn-primary btn-lg btn-block mt-3" type="submit">Update</button>				
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	const btn_submit = document.querySelector('#btn_submit');
	const spinner = document.querySelector('.spinner-border');
	btn_submit.addEventListener('click', async(e) => {
		e.preventDefault();
		const userName = document.querySelector('#username').value;
		const email = document.querySelector('#email').value;
		const password = document.querySelector('#password').value;
		const passwordConfirm = document.querySelector('#passwordConfirm').value;
		const fileInput = document.querySelector('#fileInput');
		if(userName == "") {
			$("#userName").after('<p class="text-danger">Username field is required</p>');
			$('#userName').closest('.form-group').addClass('has-error');
		} else {
			$("#username").find('.text-danger').remove();
		}
		let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if(email == "") {
			$("#email").after('<p class="text-danger">Email field is required</p>');
			$('#email').closest('.form-group').addClass('has-error');
		} else if (!emailRegex.test(email)) {
			$("#email").after('<p class="text-danger">Please enter a valid email address</p>');
			$('#email').closest('.form-group').addClass('has-error');
		} else {
			$("#email").find('.text-danger').remove();
		}
		if(password == "") {
			$("#password").after('<p class="text-danger">Password field is required</p>');
			$('#password').closest('.form-group').addClass('has-error');
		} else {
			$("#password").find('.text-danger').remove();  	
		}
		if(passwordConfirm == "") {
			$("#passwordConfirm").after('<p class="text-danger">Confirm Password field is required</p>');
			$('#passwordConfirm').closest('.form-group').addClass('has-error');
		} else {
			$("#passwordConfirm").find('.text-danger').remove(); 	
		}
    if (password !== passwordConfirm) {
			$("#passwordConfirm").after('<p class="text-danger">Passwords do not match</p>');
			$('#passwordConfirm').closest('.form-group').addClass('has-error');
		} else {
			// Remove error text field
			$("#passwordConfirm").find('.text-danger').remove();
		}
		spinner.style.display = 'inline-block';
		const formData = new FormData();
		formData.append('username', userName);
		formData.append('email', email);
		formData.append('password', password);
		// const files = [];
		// for (const file of fileInput.files) {
		// 	if (file.type.includes('image')) {
		// 		const compressedImage = await compressImage(file);
		// 		files.push(compressedImage);
		// 	} 
		// }
		// for (const file of files) {
		// 	formData.append('files[]', file, file.name);
		// } 

		const xhr = new XMLHttpRequest();
		xhr.open('POST', './php_action/fetchEditProfile.php', true);
		xhr.send(formData);
		btn_submit.disabled = true;
		xhr.onload = () => {
			spinner.style.display = 'none';
			if (xhr.status == 200) {
				try {
					const data = JSON.parse(xhr.responseText);
					console.log(data.result);
					if (data.success) {
						alert('Success!');
						self.location.href = '/account.php?code=private';
					} else {
						alert('Failed: ' + data.message); // Display the error message
					}
				} catch (error) {
					console.error('Error parsing JSON:', error);
				}
			} else {
				alert('Error: ' + xhr.status);
			}
		}; 
	});
</script>
<script src="custom/js/function.js"></script>