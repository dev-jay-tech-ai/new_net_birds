<?php
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

	if(!isset($_SESSION['userId'])) {
		echo "<script>window.location.href=' /dashboard.php';</script>";
	}

	error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

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
				<form action="/php_action/fetchPrivate.php" method="post" enctype="multipart/form-data">

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
						<button button type="submit" name="upload"id='btn_submit' class='btn btn-primary'>OK</button>
						<button id='btn_list' class='btn btn-secondary'>LIST</button>
					</div>

				</form>
				</div>
			</div>	
			<!-- /table -->
		</div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Add event listener to the form submit
		document.getElementById("uploadForm").addEventListener("submit", function() {
				// Show the loading spinner when the form is submitted
				document.getElementById("loadingSpinner").style.display = "inline-block";
		});
	});
</script>

<?php require_once 'includes/footer.php'; ?>