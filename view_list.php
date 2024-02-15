<?php 
	require_once 'php_action/core.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	$code = (isset($_GET['code']) && $_GET['code'] !== '') ? $_GET['code'] : '';
	include 'component/config.php'; 
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
	$sql = "UPDATE $board SET hit=hit+1 WHERE idx=?";
	$stmt = $connect->prepare($sql);
	$stmt->bind_param('i', $idx);
	$stmt->execute();
	
  $sql = "SELECT * FROM $board WHERE idx=?";
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
				<li><a href="dashboard.php">主页</a></li>		  
				<li class="active"><?= $board_title ?></li>
			</ol>
			<div class="container border border-1 vstack">
				<div class='p-3'>
					<span class='h3 fw-bolder'><?= $row['subject'] ?></span>
					<div>
					<?php
						if($code == 'review') {
							$rating = $row['rate']; // Assuming $row['rate'] contains the rating value
							echo "<p class='fs-10'>";
							for ($i = 1; $i <= 5; $i++) {
								$class = ($i <= $rating) ? 'text-warning' : '';
								echo '<i class="fas fa-star submit_star mr-1 star-light ' . $class . '" id="submit_star_' . $i . '" data-rating="' . $i . '"></i>';
							}
							echo '</p>';
						}
					?>
					</div>
				</div>
				<div class='d-flex px-3 border border-top-0 border-start-0 border-end-0 border-bottom-1'>
					<span class='fs-12'><?= $row['name'] ?></span>
					<span class='ms-5 me-auto fs-12'><i class="fa-regular fa-eye"></i>&nbsp;&nbsp;<?= $row['hit'] ?></span>
					<span class='rdate'><?= $row['rdate'] ?></span>
				</div>
				<div id='bbs_content' class='p-3'>
					<?= $row['content'] ?><br>
				</div>
				<div class="mt-3 d-flex gap-2 p-3 justify-content-center">
					<button id='btn_list' class="btn btn-secondary">返回菜单</button>
					<?php 
					if(isset($_SESSION['userId'])) {
						if($user_result['username'] == $row['name'] || $user_result['status'] == 1) {
							if($user_result['status'] != 0) {
								echo "<button id='btn_edit' class='btn btn-primary'>更新中</button>
								<button id='btn_delete' class='btn btn-danger'>删除</button>";
							}
						} 
					}?>
				</div>
				<div class="mt-3 mb-5 gap-1 d-flex justify-content-center">	
					<?php 
						if(!$row['is_pinned']) {
							include 'component/btn_prenext.php'; 
						}
					?>
				</div>
				<?php
					include 'write_comment.php';
					include 'view_comment.php';		
				?>
			</div>
		</div>
		<script>
		const code = '<?php echo $code; ?>';
		const splited = window.location.search.replace('?','').split(/[=?&]/);
		let param = {};
		for(let i=0; i<splited.length; i++) {
			param[splited[i]] = splited[++i];
		}
		const fetchView = (mode) => {
			const xhr = new XMLHttpRequest();
			xhr.open('POST', '/php_action/fetchViewList.php', true);
			const formData = new FormData();
			formData.append('idx', param['idx']);
			formData.append('mode', mode);
			formData.append('code', code);
			xhr.send(formData);
			xhr.onload = () => {
				if(xhr.status == 200) {
					const data = JSON.parse(xhr.responseText);
					if (data.result == 'edit_success') self.location.href = './editList.php?code='+code+'&idx='+param['idx'];
					else if (data.result == 'delete_success') {
							alert('Deleted');
							self.location.href = './list.php?code='+code;
					} 
				} else alert(xhr.status);
			}
		}
		const btn_list = document.querySelector('#btn_list');
		btn_list && btn_list.addEventListener('click', () => {
			self.location.href='./list.php?code='+code;
		})
		const btn_edit = document.querySelector('#btn_edit'); 
		btn_edit && btn_edit.addEventListener('click', () => {
			fetchView('edit');
		})
		const btn_delete = document.querySelector('#btn_delete'); 
		btn_delete && btn_delete.addEventListener('click', () => {
			alert('您确定要删除吗？');
			fetchView('delete');
		})

		const btn_comment = document.querySelector('#btn_comment');
		btn_comment.addEventListener('click',() => {
			const comment_content = document.querySelector('#comment_content');
			if(comment_content.value == '') {
				alert('댓글 내용을 입력 바랍니다'); // 중국어 변역 필요
				comment_content.focus();
				return false;
			}
			const user_id = <?= json_encode($_SESSION['userId']); ?>;
			const formData = new FormData();
			formData.append('user_id', user_id);
			formData.append('idx', param['idx']);
			formData.append('content', comment_content.value);
			const xhr = new XMLHttpRequest();
			xhr.open('post', './php_action/fetchComment.php', true);
			xhr.send(formData)
			xhr.onload = () => {
				if(xhr.status == 200) {
					try {	
						const data = JSON.parse(xhr.responseText)
						if(data.result == 'success') {
							alert('Success!')
							self.location.href = '/list.php?code='+code;
						} else alert('Failed'); // alert('Failed'+ data.message); // Display the error message
					} catch(error) {
						console.error('Error parsing JSON:', error);
					}
				} else alert('Error: ' + xhr.status);
			}
		})



		</script>  	
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->
<?php require_once 'includes/footer.php'; ?>