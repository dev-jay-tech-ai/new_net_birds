<?php
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

	error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

	$username = $result['username'] ?  $result['username'] : '';

	if (isset($_FILES['files'])) {
		$folder = "assets/images/test/";
		$names = $_FILES['files']['name'];
		$tmp_names = $_FILES['files']['tmp_name'];
		$upload_data = array_combine($tmp_names, $names);

		foreach ($upload_data as $temp_folder => $file) {
				if (move_uploaded_file($temp_folder, $folder . $file)) {
						echo $file . ' has been uploaded successfully.';
				} else {
						echo 'Error moving file ' . $file . '. Error code: ' . $_FILES['files']['error'];
				}
		}
	}
?>

<style>
	.spinner-border {
			display: none;
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-12">

			<ol class="breadcrumb">
				<li><a href="dashboard.php">Home</a></li>		  
				<li class="active"><?= $board_title ?></li>
			</ol>
			<div class="mb-2 d-flex gap-2">
				<input id="id_name" class="form-control w-25" type="text" name="username" value="<?= $username ?>" readonly>
				<input id='id_pw' type='password' name='password' class='form-control w-25' 
					value='12345' placeholder='Password' autocomplete='off'>
			</div>

			<div id="editor">
				<div class="container mt-5">

				<div class="spinner-border" role="status">
					<span class="visually-hidden">Loading...</span>
				</div>


					<!-- Upload form -->
					<form action="" method="post" enctype="multipart/form-data">

						<div class="mb-2 d-flex gap-2">
							<input id="id_name" class="form-control w-25" type="text" name="username" value="<?= $username ?>" readonly>
							<input id='id_pw' type='password' name='password' class='form-control w-25' 
								value='12345' placeholder='Password' autocomplete='off'>
						</div>				
						<div>
							<input id='id_sub' type='text' name='subject' class='form-control mb-2' 
								placeholder='Title' autocomplete='off'>
						</div>
						
						<div class='d-flex flex-row justify-content-between mt-3 mb-3'>
							<input type="file" name="files[]" multiple >
							<button type="submit" name="upload" class='btn btn-primary'>Upload files</button>
						</div>

						<!-- Textarea for content -->
						<div class="form-group">
							<textarea id="content" name="content" class="form-control" rows="4" placeholder="Enter your content here"></textarea>
						</div>


					</form>

					</div>
				</div>	
				
				<script>
					document.getElementById('uploadForm').addEventListener('submit', function () {
							document.getElementById('loadingSpinner').style.display = 'block';
					});
				</script>

				<div class="mt-2 d-flex gap-2 justify-content-end">
					<button id='btn_submit' class='btn btn-primary'>OK</button>
					<button id='btn_list' class='btn btn-secondary'>LIST</button>
				</div>
				
				<script>
					const aa = window.location.search.replace('?','').split(/[=?&]/)
					let param = {}
					for(let i=0; i<aa.length; i++) param[aa[i]] = aa[++i]
					const btn_submit = document.querySelector('#btn_submit');
					btn_submit.addEventListener('click', () => {
						const id_name = document.querySelector('#id_name');
						const id_pw = document.querySelector('#id_pw');
						const id_sub = document.querySelector('#id_sub');
						if(id_sub.value == '') {
							alert('Input the subject')
							id_sub.focus();
							return false;
						}
						const markupstr = $('#editor').editor('code');
						if(markupstr === '<p><br></p>') {
							alert('Input the content')
							return false;
						}
						const f1 = new FormData()
						f1.append('name', id_name.value)
						f1.append('pw', id_pw.value)
						f1.append('title', id_sub.value)
						f1.append('content', markupstr)
						f1.append('code', 'private')
						// ajax
						const xhr = new XMLHttpRequest()
						xhr.open('POST', './php_action/fetchPrivate.php', 'true')
						xhr.send(f1)
						btn_submit.disabled = true
						xhr.onload = () => {
							if(xhr.status == 200) {
								const data = JSON.parse(xhr.responseText)
								if(data.result == 'success') {
									alert('Success!')
									self.location.href = '/private.php?code=private';
								} else alert('Failed')
							} else alert(xhr.status)
						}
					})
					const btn_list = document.querySelector('#btn_list');
					btn_list.addEventListener('click', () => {
						self.location.href='./private.php?code=' + param['code'];
					})

				</script>

			<!-- /table -->

		</div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div>

<?php require_once 'includes/footer.php'; ?>