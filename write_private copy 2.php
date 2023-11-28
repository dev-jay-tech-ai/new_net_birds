<?php
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

	error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

	if(!isset($_SESSION['userId'])) {
		echo "<script>window.location.href=' /dashboard.php';</script>";
	}

	$username = $result['username'] ?  $result['username'] : '';
?>

<div class="container">
  <div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
					<li><a href="dashboard.php">Home</a></li>
					<li class="active"><?= $board_title ?></li>
			</ol>

			<div id="editor">
				<div class="container mt-5">
					<div class="spinner-border" role="status">
							<span class="visually-hidden">Loading...</span>
					</div>

					<!-- Upload form -->
					<form id="uploadForm" enctype="multipart/form-data">
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
						<input id="fileInput" type="file" name="files[]" multiple >
					</div>

					<!-- Textarea for content -->
					<div class="form-group">
						<textarea id="id_content" name="content" class="form-control" rows="4" placeholder="Tell your story here"></textarea>
					</div>
					<div class="d-flex gap-2 justify-content-end mt-3 mb-3">
								<button type="button" id="btn_submit" class="btn btn-primary">OK</button>
								<button type="button" id="btn_list" class="btn btn-secondary">LIST</button>
							</div>
							</form>
						</div>
					</div>
					<!-- /table -->
			</div> <!-- /col-md-12 -->
    </div> <!-- /row -->
</div>

<script>
	// Your code here
	const btn_submit = document.querySelector('#btn_submit');
	const spinner = document.querySelector('.spinner-border');
	btn_submit.addEventListener('click', async () => {
		const id_name = document.querySelector('#id_name');
		const id_pw = document.querySelector('#id_pw');
		const id_sub = document.querySelector('#id_sub');
		const fileInput = document.querySelector('#fileInput');
		const id_content = document.querySelector('#id_content');
		// Check if subject is empty
		if (id_sub.value == '') {
			alert('Input the subject');
			id_sub.focus();
			return false;
		}
		// Check if content is empty
		if (id_content.value.trim() === '') {
			alert('Input the content');
			return false;
		}

		// Show loading spinner
		spinner.style.display = 'inline-block';
		// Create FormData object
		const formData = new FormData();
		formData.append('name', id_name.value);
		formData.append('pw', id_pw.value);
		formData.append('subject', id_sub.value);
		formData.append('content', id_content.value);
		formData.append('code', 'private');
		const files = [];
		for (const file of fileInput.files) {
			if (file.type.includes('image')) {
				const compressedImage = await compressImage(file);
				files.push(compressedImage);
			} else if (file.type.includes('video')) {
				// console.log(file)
				const compressedVideo = await compressVideo(file);
				// console.log(compressedImage)
				files.push(compressedVideo);
			}
		}

		for (const file of files) {
			formData.append('files[]', file, file.name);
		}

		const xhr = new XMLHttpRequest();
		xhr.open('POST', './php_action/fetchPrivate.php', true);
		xhr.send(formData);
		btn_submit.disabled = true;

		xhr.onload = () => {
			spinner.style.display = 'none';
			if (xhr.status == 200) {
				try {
					const data = JSON.parse(xhr.responseText);
					if (data.result == 'success') {
						alert('Success!');
						self.location.href = '/private.php?code=private';
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

	// Function to compress images
	function compressImage(file) {
		return new Promise((resolve, reject) => {
			new Compressor(file, {
				quality: 0.8, // Adjust quality as needed
				success(result) {
					resolve(result);
				},
				error(e) {
					reject(e);
				},
			});
		});
	}

	// Function to compress videos
	async function compressVideo(originalFile) {
		const { read, write, createFFmpeg } = ffmpeg;
		const ffmpegInstance = createFFmpeg({ log: true });

		await ffmpegInstance.load();

		const inputName = 'input.mp4';
		const outputName = 'output.mp4';

		// Write the original video to memory
		ffmpegInstance.FS('writeFile', inputName, await fetchFile(originalFile));

		// Run FFmpeg command to compress video
		await ffmpegInstance.run('-i', inputName, '-c:v', 'libx264', '-b:v', '1M', '-c:a', 'aac', '-b:a', '192k', outputName);

		// Read the compressed video from memory
		const compressedVideo = ffmpegInstance.FS('readFile', outputName);

		return new File([compressedVideo], 'compressed_video.mp4', { type: 'video/mp4' });
	}

</script>



<?php require_once 'includes/footer.php'; ?>