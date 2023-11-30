<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

  error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

	if(isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
    $sql = "SELECT * FROM users WHERE user_id = {$user_id}";
    $query = $connect->query($sql);
    $user_result = $query->fetch_assoc();
  }

  $idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx']) ? $_GET['idx'] : '');
  if($idx == '') {
    exit('Not allow to the abnormal access');
  }

  $sql = "SELECT * FROM rboard WHERE idx=?";
  $stmt = $connect->prepare($sql);
  $stmt->bind_param('i', $idx); // 'i' represents the data type of the parameter (integer)
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();  
?>

<div class="container">
	<div class="row">
		<div class="col-md-12 mb-3">
			<ol class="breadcrumb">
				<li><a href="dashboard.php">Home</a></li>		  
				<li class="active"><?= $board_title ?></li>
			</ol>
			<div class="container border border-1 vstack">
				<div class='p-3'>
					<span class='h3 fw-bolder'><?= $row['subject'] ?></span>
					<div>
					<?php
						$rating = $row['rate']; // Assuming $row['rate'] contains the rating value
						echo "<p class='fs-10'>";
						for ($i = 1; $i <= 5; $i++) {
								$class = ($i <= $rating) ? 'text-warning' : '';
								echo '<i class="fas fa-star submit_star mr-1 star-light ' . $class . '" id="submit_star_' . $i . '" data-rating="' . $i . '"></i>';
						}
						echo '</p>';
					?>
					</div>
				</div>
				<div class='d-flex px-3 border border-top-0 border-start-0 border-end-0 border-bottom-1'>
					<span class='fs-12'><?= $row['name'] ?></span>
					<span class='ms-5 me-auto fs-12'></span>
					<span class='rdate'><?= $row['rdate'] ?></span>
				</div>
				<div id='bbs_content' class='p-3'>
					<?= $row['content'] ?><br>
				</div>
				<div class="mt-3 d-flex gap-2 p-3">
					<button id='btn_list' class="btn btn-secondary me-auto">List</button>
					<?php 
					if(isset($_SESSION['userId'])) {
						if($user_result['username'] == $row['name'] || $user_result['status'] == 1) {
							echo "<button id='btn_edit' class='btn btn-primary''>Update</button>
							<button id='btn_delete' class='btn btn-danger'>Delete</button>";
						} 
					}?>
				</div>
			</div>
		</div>
		<script>
		const splited = window.location.search.replace('?','').split(/[=?&]/);
		let param = {};
		for(let i=0; i<splited.length; i++) {
			param[splited[i]] = splited[++i];
		}
		const fetchView = (mode) => {
			const xhr = new XMLHttpRequest();
			xhr.open('POST', './php_action/fetchViewReview.php', true);
			const f1 = new FormData();
			f1.append('idx', param['idx']);
			f1.append('mode', mode);
			xhr.send(f1);
			xhr.onload = () => {
				if (xhr.status == 200) {
					const data = JSON.parse(xhr.responseText);
					if (data.result == 'edit_success') self.location.href = './editReview.php?idx=' + param['idx'];
					else if (data.result == 'delete_success') {
							alert('Deleted');
							self.location.href = './review.php';
					} 
				} else alert(xhr.status);
			}
		}
		const btn_list = document.querySelector('#btn_list');
		btn_list && btn_list.addEventListener('click', () => {
			self.location.href='./review.php';
		})
		const btn_edit = document.querySelector('#btn_edit'); 
		btn_edit && btn_edit.addEventListener('click', () => {
			fetchView('edit');
		})
		const btn_delete = document.querySelector('#btn_delete'); 
		btn_delete && btn_delete.addEventListener('click', () => {
			alert('Are you sure you want to delete this?');
			fetchView('delete');;
		})
		</script>
		<!-- /table -->   	
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->

<?php require_once 'includes/footer.php'; ?>