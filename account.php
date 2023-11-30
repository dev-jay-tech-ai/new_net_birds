<?php 
  require_once 'includes/header.php';

	if(!isset($_SESSION['userId'])) {
		echo "<script>window.location.href=' /dashboard.php';</script>";
	} else {
    $user_id = $_SESSION['userId'];
    $sql = "SELECT * FROM users WHERE user_id = {$user_id}";
    $query = $connect->query($sql);
    $result = $query->fetch_assoc();
    $initial = strtoupper(mb_substr($result['username'], 0, 1, 'UTF-8'));
  }
?>
<div class='container'>
	<div class="d-flex justify-content-center vertical row col-lg-offset-2 col-lg-8">
		<div class="row col-lg-9 col-sm-12">
			<div class="col-md-12">
				<div class="login-block text-center">
					<div class="form-group d-flex justify-content-center">
						<div class='profile_img'>
							<?= $result['user_image'] ? 
							'<img src="' . $result['user_image'] . '" alt="User Image">' : 
							'<div class="d-flex justify-content-center align-items-center h-100">' . $initial . '</div>'; 
							?>
						</div>
					</div>	
					<div class="form-group">
						<div><b><?= $result['username'] ?></b></div>
						<div style='text-decoration:none !important;'><?= $result['email'] ?></div>
					</div>
					<div class="form-group pt-2">
						<div><b>Credit: </b>&nbsp;<?= $result['credit'] ?></div>
					</div>
					<div class="form-group mt-5">
						<button type="submit" id='delete-account' class="btn btn-danger btn-lg btn-block mt-3" 
							data-user-id="<?= $user_id ?>">Delete account</button>				
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$('#delete-account').click(function() {
		const user_id = $(this).data('user-id');
		if (confirm('Are you sure you want to delete your account?')) {
			$.ajax({
				type: 'POST',
				url: '/php_action/deleteAccount.php',
				data: { user_id: user_id },
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						alert(response.message);
						location.href = '/login.php';
					} else {
						alert(response.message);
					}
				},
				error: function(error) {
					console.error('Ajax request failed:', error);
					alert('Error deleting user.');
				}
			});
		}
	});
});
</script>