<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

	$username = $result['username'] ?  $result['username'] : '';
?>

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
			<div>
				<input id='id_sub' type='text' name='subject' class='form-control mb-2' 
					placeholder='Title' autocomplete='off'>
			</div>

			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->

			<div id="editor">
				<div class="container mt-5">
					<form action='' method='POST' enctype='multipart/form-data'>
						<div class="form-group">
							<label for="editor">Write your content:</label>
							<div><textarea id="editor" name="editor" class='w-50' style='height: 200px;'></textarea></div>
						</div>
						<div class="form-group">
								<label for="video">Upload Video:</label>
								<input type="file" name='userfile[]' value='' multiple=''>
						</div>
						<button type="submit" class="btn btn-primary" value='upload'>Save Content</button>
					</form>
				</div>
			</div>		


			<?php 
			$phpFileUploadErrors = array(
				0 => 'There is no error, the file uploaded with success',
				1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
				2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
				3 => 'The uploaded file was only partially uploaded',
				4 => 'No file was uploaded',
				5 => 'Missing a temporary folder',
				6 => 'Failed to write file to disk',
				7 => 'A PHP extension stopped the file upload'
			);

			if(isset($_FILES['userfile'])) {
				pre_r($_FILES);
			}

			function pre_r($array) {
				echo '<pre>';
				print_r($array);
				echo '</pre>';
			}

			function reArrayFiles($file_post) {
				$file_array = array();
				$file_count = count($file_post['name']);
				$file_keys = array_keys($file_post);

				for ($i=0; $i<$file_count; $i++) {
					foreach ($file_keys as $key) $file_array[$i][$key] = $file_post[$key][$i];
				}
				return $file_array;
			}
		?>




			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->
			<!--  Here I'm coding  -->

			
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