<?php 
	require_once 'php_action/core.php';
	require_once 'includes/header.php'; 
	require_once 'component/auth_session.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

	$username = $result['username'] ?  $result['username'] : '';
?>

<div class="container">
	<?php include_once 'component/loading.php'; ?>
  <div class="row">
		<div class="col-md-12 mb-3">
			<ol class="breadcrumb">
					<li><a href="dashboard.php">Home</a></li>
					<li class="active"><?= $board_title ?></li>
			</ol>
			<div class="mt-5">
				<form id="uploadForm" enctype="multipart/form-data">
				<div class="mb-2 d-flex gap-2">
					<input id="id_name" class="form-control w-25" type="text" name="username" value="<?= $username ?>" readonly>
				</div>			
				<div>
					<input id='id_sub' type='text' name='subject' class='form-control mb-2' 
						placeholder='Title' autocomplete='off'>
				</div>
				<div class='d-flex flex-row justify-content-between mt-3 mb-3'>
						<input id="fileInput" type="file" name="files[]" multiple>
				</div>
				<div class="form-group">
					<input type="hidden" id="id_content" class="form-control" rows="4" >
				</div>
				<div class="form-group">
					<div id="id_content_editable" name="content" class="form-control" 
						rows="4" contenteditable="true"></div>
				</div>
				<div class="d-flex gap-2 justify-content-end mt-3 mb-3">
						<button type="button" id="btn_submit" class="btn btn-primary">OK</button>
						<button type="button" id="btn_list" class="btn btn-secondary">LIST</button>
					</div>
				</form>
				</div>
			</div>
    </div> <!-- /row -->
	</div>
	<script>
	document.getElementById('id_content_editable').addEventListener('input', function() {
		document.getElementById('id_content').value = this.innerHTML;
	});

	const btn_submit = document.querySelector('#btn_submit');
	const fade_background = document.querySelector('.fade_background');
	const spinner = document.querySelector('.spinner-border');
	btn_submit.addEventListener('click', async (e) => {
		e.preventDefault();
		const id_name = document.querySelector('#id_name');
		const id_sub = document.querySelector('#id_sub');
		const id_content = document.querySelector('#id_content');
		const fileInput = document.querySelector('#fileInput');
		const user_id = <?= json_encode($result['user_id']); ?>;
		if (id_sub.value == '') {
			alert('Input the subject');
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
		formData.append('subject', id_sub.value);
		formData.append('content', id_content.value);
		formData.append('code', 'agent');
		formData.append('user_id', user_id);
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
		const xhr = new XMLHttpRequest();
		xhr.open('POST', './php_action/fetchAgent.php', true);
		xhr.send(formData);
		btn_submit.disabled = true;
		xhr.onload = () => {
			spinner.style.display = 'none';
			if (xhr.status == 200) {
				try {
					const data = JSON.parse(xhr.responseText);
					if (data.result == 'success') {
						alert('Success!');
						self.location.href = '/agent.php?code=agent';
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
<?php require_once 'includes/footer.php'; ?>