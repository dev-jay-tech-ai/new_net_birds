<?php 
require_once 'includes/header.php'; 
require_once 'component/auth_session.php'; 

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
							 <div class='profile_img'>
								<?= $result['user_image'] ? 
								'<img id="uploaded_img" src="' . $result['user_image'] . '" alt="User Image">' : 
								'<div class="d-flex justify-content-center align-items-center h-100 profile_initial">' . $initial . '</div>'; 
								?>
							</div>
            </div>	
						<div class="form-group mb-3">
							<div class='d-flex justify-content-between align-items-center'>
								<input id="fileInput" type="file" name="file" accept="image/*" style='width: 90%'>
								<div><div id='btn_delete' class='round'><i class='fa fa-times'></i></div></div>
							</div>
						</div>
            <div class="form-group">
							<label for="username">Username</label>
							<input class="form-control" type="text" placeholder="Username" name="username" 
							id="username" value="<?= $result['username'] ?>" autocorrect="off" autocapitalize="off" autocomplete="username">
						</div>
            <div class="form-group">
							<label for="username">Email</label>
							<input class="form-control" type="text" placeholder="email" name="email" 
							id="email" value="<?= $result['email'] ?>" autocorrect="off" autocapitalize="off">
						</div>
						<div class="form-group">
							<label for="change_password">New Password</label>
							<input class="form-control" type="password" placeholder="new password" name="change_password" id="password" autocomplete="new-password">
							<p id="caps-lock-warning" class="text-danger hidden">
								<i class="fa fa-exclamation-triangle"></i> Caps Lock is enabled
							</p>
						</div>
						<div class="form-group">
							<label for="password"> Confirm new password</label>
							<input class="form-control" type="password" placeholder="Confirm new password" name="passwordConfirm" id="passwordConfirm" autocomplete="new-password">
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
	const fileInput = document.querySelector('#fileInput');
	const profile_img = document.querySelector('.profile_img');
	const btn_delete = document.querySelector('#btn_delete'); 
	fileInput.addEventListener('change', async() => {
		const imgElement = document.createElement('img');
		const firstChild = profile_img.firstElementChild;
		if(firstChild) {
			profile_img.removeChild(firstChild);
		}
		imgElement.src = URL.createObjectURL(fileInput.files[0]);
		imgElement.addClass ='preview_img';
		imgElement.alt = 'User Image';
		profile_img.appendChild(imgElement);

	});
	btn_delete.addEventListener('click', async(e) => {
		e.preventDefault();
		const imgTag = profile_img.querySelector('img');
		if (imgTag) {
			profile_img.removeChild(imgTag);
		}
		fileInput.value = null;
	});
	const btn_submit = document.querySelector('#btn_submit');
	const spinner = document.querySelector('.spinner-border');
	btn_submit.addEventListener('click', async(e) => {
		e.preventDefault();
		const userName = document.querySelector('#username').value;
		const email = document.querySelector('#email').value;
		const password = document.querySelector('#password').value;
		const passwordConfirm = document.querySelector('#passwordConfirm').value;
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
			return false;
		} else if (!emailRegex.test(email)) {
			$("#email").after('<p class="text-danger">Please enter a valid email address</p>');
			$('#email').closest('.form-group').addClass('has-error');
			return false;
		} else {
			$("#email").find('.text-danger').remove();
		}
		if(password == "") {
			$("#password").after('<p class="text-danger">Password field is required</p>');
			$('#password').closest('.form-group').addClass('has-error');
			return false;
		} else {
			$("#password").find('.text-danger').remove();  	
		}
		if(passwordConfirm == "") {
			$("#passwordConfirm").after('<p class="text-danger">Confirm Password field is required</p>');
			$('#passwordConfirm').closest('.form-group').addClass('has-error');
			return false;
		} else {
			$("#passwordConfirm").find('.text-danger').remove(); 	
		}
    if (password !== passwordConfirm) {
			$("#passwordConfirm").after('<p class="text-danger">Passwords do not match</p>');
			$('#passwordConfirm').closest('.form-group').addClass('has-error');
			return false;
		} else {
			$("#passwordConfirm").find('.text-danger').remove();
		}
		spinner.style.display = 'inline-block';
		const formData = new FormData();
		formData.append('username', userName);
		formData.append('email', email);
		formData.append('password', password);
		formData.append('file', fileInput.files[0]);
		const xhr = new XMLHttpRequest();
		xhr.open('POST', './php_action/fetchEditProfile.php', true);
		xhr.send(formData);
		btn_submit.disabled = true;
		xhr.onload = () => {
			spinner.style.display = 'none';
			if (xhr.status == 200) {
				try {
					const data = JSON.parse(xhr.responseText);
					if(data.success) {
						alert('Success!');
						self.location.href = '/account.php?code=private';
					} else {
						alert('Failed: ' + data.messages); // Display the error message
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