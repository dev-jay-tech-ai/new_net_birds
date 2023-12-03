<?php 
require_once 'php_action/db_connect.php';
require_once 'includes/header.php'; 
require_once 'component/auth_session.php'; 
include 'component/config.php'; 

$idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx'])) ? $_GET['idx'] : '';
if($idx == '') die('<script>alert('. $_GET['idx'] . '); history.go(-1);</script>');
$sql = "SELECT * FROM pboard WHERE idx=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param('i', $idx);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>
<div class='container'>
<?php include_once 'component/loading.php'; ?>
<div class="row">
	<div class="col-md-12 mb-3">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active">Private</li>
		</ol>
		<div class="mb-3">
			<form id="uploadForm" enctype="multipart/form-data">
				<div class="mb-2 d-flex gap-2">
					<input id="id_name" class="form-control w-25" type="text" name="username" value="<?= $row['name'] ?>" readonly>
				</div>
				<div class="form-group">
				<select id='id_location' name="Location" class="form-control">
					<option value="-1">- Select Your Location -</option> <!-- Set a default value, like -1 -->
					<option value="0" <?php if($row['location'] == 0) echo 'selected'; ?>>London</option>
					<option value="1" <?php if($row['location'] == 1) echo 'selected'; ?>>Manchester</option>
					<option value="2" <?php if($row['location'] == 2) echo 'selected'; ?>>Glasgow</option>
					<option value="3" <?php if($row['location'] == 3) echo 'selected'; ?>>Nottingham</option>
					<option value="4" <?php if($row['location'] == 4) echo 'selected'; ?>>Birmingham</option>
					<option value="5" <?php if($row['location'] == 5) echo 'selected'; ?>>others</option>
				</select>
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
					<button id='btn_list' class='btn btn-secondary'>LIST</button>
					<button id='btn_submit' class='btn btn-primary'>OK</button>
				</div>
			</form>	
		</div>
		<script>
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
				const id_location = document.querySelector('#id_location');
				const id_content = document.querySelector('#id_content');
				const fileInput = document.querySelector('#fileInput');
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
				const formData = new FormData();
				formData.append('name', id_name.value);
				formData.append('title', id_sub.value);
				formData.append('content', id_content.value);
				formData.append('code', 'private');
				formData.append('location', id_location.value);
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
				xhr.open('POST', './php_action/fetchEditPrivate.php', 'true')
				xhr.send(formData);
				btn_submit.disabled = true
				xhr.onload = () => {
					if(xhr.status == 200) {
						const data = JSON.parse(xhr.responseText)
						if(data.result == 'success') {
							alert('Success!')
							self.location.href = '/view_private.php?code=private' + '&idx=' + param['idx'];
						} else if(data.result == 'denied') {
							alert('No permission to edit');
							self.location.href = '/private.php?code=private';
						} else alert('Failed')
					} else alert(xhr.status)
				}
			})
			const btn_list = document.querySelector('#btn_list');
			btn_list.addEventListener('click', (e) => {
				e.preventDefault();
				self.location.href='./private.php?code=' + param['code'];
			})
		</script>
		<script src="custom/js/function.js"></script>
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->
</div>
<?php require_once 'includes/footer.php'; ?>