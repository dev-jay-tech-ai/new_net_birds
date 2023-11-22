<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

  error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

	if (isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
    $sql = "SELECT * FROM users WHERE user_id = {$user_id}";
    $query = $connect->query($sql);
    $user_result = $query->fetch_assoc();
  }

  $idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx']) ? $_GET['idx'] : '');
  if($idx == '') {
    exit('Not allow to the abnormal access');
  }

	$sql = "UPDATE pboard SET hit=hit+1 WHERE idx=?";
	$stmt = $connect->prepare($sql);
	$stmt->bind_param('i', $idx);
	$stmt->execute();

  $sql = "SELECT * FROM pboard WHERE idx=?";
  $stmt = $connect->prepare($sql);
  $stmt->bind_param('i', $idx); // 'i' represents the data type of the parameter (integer)
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();  
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="dashboard.php">Home</a></li>		  
				<li class="active"><?= $board_title ?></li>
			</ol>

			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Bullet Board Write</div>
				</div> <!-- /panel-heading -->
				<div class="panel-body">
					<div class="container border border-1 w-50 vstack">
						<div class='p-3'>
							<span class='h3 fw-bolder'><?= $row['subject'] ?></span>
						</div>
						<div class='d-flex px-3 border border-top-0 border-start-0 border-end-0 border-bottom-1'>
							<span><?= $row['name'] ?></span>
							<span class='ms-5 me-auto'><?= $row['hit'] ?></span>
							<span><?= $row['rdate'] ?></span>
						</div>
						<div id='bbs_content' class='p-3'>
							<?= $row['content'] ?><br>
						</div>
						<div class="mt-3 d-flex gap-2 p-3">
							<button id='btn_list' class="btn btn-secondary me-auto">List</button>
							<?php 
							if($user_result['username'] == $row['name'] || $user_result['status'] == 1) {
								echo "<button id='btn_edit' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modal'>Update</button>
								<button id='btn_delete' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modal'>Delete</button>";
							} ?>

						</div>
					</div>

					<!-- Modal -->
					<div id='modal' class="modal" tabindex="-1">
						<div class="modal-dialog">
							<form method='post' name='modal_form' action='./php_action/fetchView.php'>
								<input type='hidden' name='mode' value=''>
								<div class="modal-content">
									<div class="modal-header">
										<h5 id='modal_title' class="modal-title">Modal title</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<input id='password' type='password' name='password' class='form-control' placeholder='Input your password' >

									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
										<button id='btn_pw_check' type="button" class="btn btn-primary">Check</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
	
			<?php
			// Assuming $row['rdate'] is in the format 'Y-m-d H:i:s'
			$rdate = $row['rdate'];
			$currentTimestamp = time();
			$postTimestamp = strtotime($rdate);
			$timeDifference = $currentTimestamp - $postTimestamp;

			// Define the editing window duration (24 hours in seconds)
			$editingWindowDuration = 24 * 60 * 60; // 24 hours in seconds

			// Check if the post is within the 24-hour editing window
			$editAllowed = ($timeDifference <= $editingWindowDuration);
			?>

			<script>
				const splited = window.location.search.replace('?', '').split(/[=?&]/);
				let param = {};
				for (let i = 0; i < splited.length; i++) {
						param[splited[i]] = splited[++i];
				}

				const btn_list = document.querySelector('#btn_list');
				btn_list && btn_list.addEventListener('click', () => {
						self.location.href = './private.php?code=' + param['code'];
				});

				const btn_edit = document.querySelector('#btn_edit'); 
				btn_edit && btn_edit.addEventListener('click', () => {
					const modal_title = document.querySelector('#modal_title'); 
					modal_title.textContent = 'Edit';
					document.modal_form.mode.value = 'edit';
				})

				const btn_delete = document.querySelector('#btn_delete'); 
				btn_delete && btn_delete.addEventListener('click', () => {
					const modal_title = document.querySelector('#modal_title'); 
					modal_title.textContent = 'Delete';
					document.modal_form.mode.value = 'delete';
				})

				if (<?php echo json_encode($editAllowed); ?>) {
						btn_edit && btn_edit.removeAttribute('disabled');
						btn_delete && btn_delete.removeAttribute('disabled');
				} else {
						btn_edit && btn_edit.setAttribute('disabled', true);
						btn_delete && btn_delete.setAttribute('disabled', true);
				}

				const btn_pw_check = document.querySelector('#btn_pw_check');
				btn_pw_check && btn_pw_check.addEventListener('click', () => {
						const password = document.querySelector('#password');
						if (password.value == '') {
								alert('Input your password');
								password.focus();
								return false;
						}

						// 비밀번호, code, 게시물 번호등을 가지고 비밀 번호 비교
						const xhr = new XMLHttpRequest();
						xhr.open('POST', './php_action/fetchView.php', true);
						const f1 = new FormData(document.modal_form);
						f1.append('idx', param['idx']);
						f1.append('code', param['code']);
						xhr.send(f1);
						xhr.onload = () => {
								if (xhr.status == 200) {
										const data = JSON.parse(xhr.responseText);
										if (data.result == 'edit_success') self.location.href = './edit.php?idx=' + param['idx'] + '&code=' + param['code'];
										else if (data.result == 'delete_success') {
												alert('Deleted');
												self.location.href = './private.php?code=' + param['code'];
										} else if (data.result == 'wrong_password') {
												alert('Wrong password');
												password.value = '';
												password.focus();
										}
								} else alert(xhr.status);
						}
				});

			</script>
  
			</div> <!-- /panel-body -->
		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->

<?php require_once 'includes/footer.php'; ?>