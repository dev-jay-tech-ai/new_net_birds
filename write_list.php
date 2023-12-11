<?php 
	require_once 'php_action/core.php';
	require_once 'includes/header.php'; 
	require_once 'component/auth_session.php'; 
	include 'component/pagination.php'; 
	$code = (isset($_GET['code']) && $_GET['code'] !== '') ? $_GET['code'] : '';
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
				<div class="mb-2 d-flex align-items-center gap-2">
					<input id="id_name" class="form-control w-25" type="text" name="username" value="<?= $username ?>" readonly>
					<?php 
					if($code === 'review') {
						echo "<div>Rating: </div>
						<h4 class='text-center'>
							<i class='fas fa-star star-light submit_star mr-1' id='submit_star_1' data-rating='1'></i>
							<i class='fas fa-star star-light submit_star mr-1' id='submit_star_2' data-rating='2'></i>
							<i class='fas fa-star star-light submit_star mr-1' id='submit_star_3' data-rating='3'></i>
							<i class='fas fa-star star-light submit_star mr-1' id='submit_star_4' data-rating='4'></i>
							<i class='fas fa-star star-light submit_star mr-1' id='submit_star_5' data-rating='5'></i>
						</h4>	
						<input type='hidden' id='id_rate' name='rating' value=''>";
					}
					?>
				</div>
				<?php 
					if($code !== 'agent') {
						echo "<div class='form-group'>
							<select id='id_location' name='Location' class='form-control'>
								<option value='-1'> - Select Your Location -</option>  
								<option value='0'>London</option>
								<option value='1'>Manchester</option>
								<option value='2'>Glasgow</option>
								<option value='3'>Nottingham</option>
								<option value='4'>Birmingham</option>
								<option value='5'>others</option>
							</select>
						</div>";
					}		
				?>
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
				<div class="mt-2 d-flex gap-2 justify-content-end">
					<button id='btn_submit' class='btn btn-primary'>OK</button>
					<button id='btn_list' class='btn btn-secondary'>LIST</button>
				</div>
				<script>
					const code = '<?php echo $code; ?>';
					document.getElementById('id_content_editable').addEventListener('input', function() {
						document.getElementById('id_content').value = this.innerHTML;
					});
					const btn_submit = document.querySelector('#btn_submit');
					const fade_background = document.querySelector('.fade_background');
					const spinner = document.querySelector('.spinner-border');
					btn_submit.addEventListener('click', async(e) => {
						e.preventDefault();
						const id_name = document.querySelector('#id_name');
						const id_sub = document.querySelector('#id_sub');
						const id_content = document.querySelector('#id_content');
						if(code === 'review') {
							const id_rate = document.querySelector('#id_rate');
						}
						const id_location = document.querySelector('#id_location');
						const fileInput = document.querySelector('#fileInput');
						const user_id = <?= json_encode($result['user_id']); ?>;
						if(id_sub.value == '') {
							alert('Input the subject')
							id_sub.focus();
							return false;
						}
						if(id_content.value.trim() === '') {
							alert('Input the content');
							return false;
						}
						if(code !== 'agent') {
							if(id_location.value === '-1') {
								alert('Please select a location');
								return false;
							}
						}
						fade_background.style.display = 'inline-block';
						spinner.style.display = 'inline-block';
						const formData = new FormData()
						formData.append('name', id_name.value)
						formData.append('title', id_sub.value)
						formData.append('content', id_content.value);
						formData.append('code', code);
						if(code === 'review') {
							formData.append('rate', id_rate.value);
						}
						if(code !== 'agent') {
							formData.append('location', id_location.value);
						}
						formData.append('user_id', user_id);
						const files = [];
						for (const file of fileInput.files) {
							if(file.type.includes('image')) {
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
						xhr.open('POST', './php_action/writeList.php', true)
						xhr.send(formData)
						btn_submit.disabled = true;
						xhr.onload = () => {
							spinner.style.display = 'none';
							if(xhr.status == 200) {
								try {	
									const data = JSON.parse(xhr.responseText)
									if(data.result == 'success') {
										alert('Success!')
										self.location.href = '/list.php?code='+code;
									} else alert('Failed'+ data.message); // Display the error message
								} catch(error) {
									console.error('Error parsing JSON:', error);
								}
							} else alert('Error: ' + xhr.status);
						}
					})
					const btn_list = document.querySelector('#btn_list');
					btn_list.addEventListener('click', () => {
						self.location.href='./'+code+'.php';
					})
				</script>
		</div> <!-- /cmt-5 -->
	</div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div><!-- /conatiner -->
<script src="custom/js/function.js"></script>
<script src="custom/js/review.js"></script>
<?php require_once 'includes/footer.php'; ?>