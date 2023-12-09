<?php 
require_once 'php_action/db_connect.php';

error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

require_once 'includes/header.php'; 
$code = (isset($_GET['code']) && $_GET['code'] !== '') ? $_GET['code'] : '';
include 'component/config.php'; 
require_once 'component/auth_session.php'; 
$idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx'])) ? $_GET['idx'] : '';
if($idx == '') {
	die('<script>alert("Not any number"); history.go(-1);</script>');
}
$sql = "SELECT * FROM $board WHERE idx=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param('i', $idx); 
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>
<div class='container'>
<?php include_once 'component/loading.php'; ?>
<div class="row">
	<div class="col-md-12 mb-5">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active"><?= $board_title ?></li>
		</ol>
		<div class="mb-3">
			<form id="uploadForm" enctype="multipart/form-data">
				<div class="mb-2 d-flex gap-2">
					<input id="id_name" class="form-control w-25" type="text" name="username" value="<?= $row['name'] ?>" readonly>
				</div>
				<?php
				if ($code !== 'agent') {
					echo '<div class="form-group">
					<select id="id_location" name="Location" class="form-control">
						<option value="-1">- Select Your Location -</option>
						<option value="0"' . ($row['location'] == 0 ? ' selected' : '') . '>London</option>
						<option value="1"' . ($row['location'] == 1 ? ' selected' : '') . '>Manchester</option>
						<option value="2"' . ($row['location'] == 2 ? ' selected' : '') . '>Glasgow</option>
						<option value="3"' . ($row['location'] == 3 ? ' selected' : '') . '>Nottingham</option>
						<option value="4"' . ($row['location'] == 4 ? ' selected' : '') . '>Birmingham</option>
						<option value="5"' . ($row['location'] == 5 ? ' selected' : '') . '>others</option>
					</select>
				</div>';
				}
				if($code == 'review') {
					echo '<div>Rating: </div>';
					$rating = $row['rate']; // Assuming $row['rate'] contains the rating value
					echo '<h4 class="text-center m-auto">';
					for ($i = 1; $i <= 5; $i++) {
							$class = ($i <= $rating) ? 'text-warning' : '';
							echo '<i class="fas fa-star submit_star mr-1 star-light ' . $class . '" id="submit_star_' . $i . '" data-rating="' . $i . '"></i>';
					}
					echo '</h4>';
				}
			?>
			<input type="hidden" id="id_rate" name="rating" value="">
			</div>
			<div>
				<input id='id_sub' type='text' name='subject' class='form-control mb-2' 
				value="<?=  $row['subject'] ?>" placeholder='Title' autocomplete='off'>
			</div>
			<div class='d-flex flex-row justify-content-between mt-3 mb-3'>
					<input id="fileInput" type="file" name="files[]">
				</div>
				<div class="form-group">
				<input type="hidden" id="id_content" class="form-control" rows="4" value="<?= htmlspecialchars($row['content']) ?>">
				</div>
				<div class="form-group">
					<div id="id_content_editable" name="content" class="form-control" 
						rows="4" contenteditable="true"><?= $row['content'] ?></div>
				</div>
				<div class="mt-2 d-flex gap-2 justify-content-end">
					<button id='btn_submit' class='btn btn-primary'>OK</button>
					<button id='btn_list' class='btn btn-secondary'>LIST</button>
				</div>
			</div>
			<script>
			const code = '<?php echo $code; ?>';
			document.getElementById('id_content_editable').addEventListener('input', function() {
				document.getElementById('id_content').value = this.innerHTML;
			});

			const aa = window.location.search.replace('?','').split(/[=?&]/)
			let param = {};
			for(let i=0; i<aa.length; i++) param[aa[i]] = aa[++i];

			const btn_submit = document.querySelector('#btn_submit');
			const fade_background = document.querySelector('.fade_background');
			const spinner = document.querySelector('.spinner-border');
			btn_submit.addEventListener('click', async(e) => {
				e.preventDefault();
				const id_name = document.querySelector('#id_name');
				const id_sub = document.querySelector('#id_sub');
				if(code == 'review') {
					const id_location = document.querySelector('#id_location');
				}
				const id_content = document.querySelector('#id_content');
				const fileInput = document.querySelector('#fileInput')
				if(id_sub.value == '') {
					alert('Input the subject')
					id_sub.focus();
					return false;
				}
				if (id_content.value.trim() === '') {
					alert('Input the content');
					return false;
				}
				fade_background.style.display = 'inline-block';
				spinner.style.display = 'inline-block';
				const formData = new FormData()
				formData.append('name', id_name.value)
				formData.append('title', id_sub.value)
				formData.append('content', id_content.value);
				formData.append('rate', id_rate.value)
				if(code == 'review') {
					formData.append('location', id_location.value);
				}
				formData.append('code', code);
				formData.append('idx', param['idx'])
				const files = [];
				for (const file of fileInput.files) {
					if (file.type.includes('image')) {
						const compressedImage = await compressImage(file);
						files.push(compressedImage);
					} else if (file.type.includes('video')) {
						files.push(file);
					}
				}
				for (const file of files) {
					formData.append('files[]', file, file.name);
				}
				const xhr = new XMLHttpRequest()
				xhr.open('POST', './php_action/fetchEditList.php', true)
				xhr.send(formData)
				btn_submit.disabled = true
				xhr.onload = () => {
					if(xhr.status == 200) {
						const data = JSON.parse(xhr.responseText)
						if(data.result == 'success') {
							alert('Success!')
							self.location.href = '/view_list.php?code=' + code + '&idx=' + param['idx'];
						} else if(data.result == 'denied') {
							alert('No permission to edit');
							self.location.href = '/list?code='+code+'.php?';
						} else alert('Failed')
					} else alert(xhr.status)
				}
			})
			const btn_list = document.querySelector('#btn_list');
			btn_list.addEventListener('click', () => {
				self.location.href='/list?code='+code+'.php?';
			})
		</script>
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->
</div>
<script src="custom/js/function.js"></script>
<script src="custom/js/review.js"></script>
<?php require_once 'includes/footer.php'; ?>