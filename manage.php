<?php 
require_once 'php_action/core.php';
require_once 'includes/header.php'; 
include 'component/auth_session.php'; 

$sqlData = "SELECT * FROM banners";
$stmtData = $connect->prepare($sqlData);
if ($stmtData) {
  $stmtData->execute();
  $result = $stmtData->get_result();
  $rs = [];
  while ($row = $result->fetch_assoc()) {
    $rs[] = $row;
  }
} else {
  die($connect->error);
}

?>

<div class="container">
<?php include_once 'component/loading.php'; ?>
<div class="row">
	<div class="col-md-12 mb-5">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">主页</a></li>		  
		  <li class="active">Manage</li>
		</ol>
		<div class="div-action pull pull-right" style="padding-bottom:20px;">
			<button class="btn btn-primary" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal">Add Banner</button>
		</div> <!-- /div-action -->		
		<div class="scroll mb-2">
			<table class="table table-bordered table-hover">
			<colgroup>
				<col width='7%' />
				<col width='33%' />
				<col width='40%' />
				<col width='10%' />
				<col width='10%' />
			</colgroup>
			<thead>
				<tr>				
					<th></th>
					<th>Image</th>
					<th>Link</th>
					<th>Status</th>
					<th style="width:10%;"></th>
					<th style="width:5%;"></th>
				</tr>
			</thead>
			<?php 
				foreach ($rs as $i => $row) {
					$activeRowCount++;
				?>
					<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>' data-code='<?= $code ?>'>
						<td class='text-center'><?= $activeRowCount; ?></td>
						<td><img src="<?= $row['img'] ?>" style='width: 100px;' alt='banner'/></td>
						<td><?= $row['link'] ?></td>
						<td>
							<?php 
								if($row['active'] == 1) {
									echo "<label class='label label-success'>active</label>";
								} else {
									echo "<label class='label label-danger'>deactive</label>";
								}
							?>
						</td>
						<td class='rdate text-center'><?= substr($row['rdate'], 0, 10); ?></td>
						<td class='rdate text-center'>
						<button class='btn-deactivate btn btn-secondary btn-small' data-idx='<?= $row['idx']; ?>_<?= $row['active']; ?>'>
							<?php 
							if($row['active'] == 1) {
								echo "deactive";
							} else {
								echo "active";
							}
							?>
							</button>
							<button class='btn-delete btn btn-primary btn-small mt-2' data-idx='<?= $row['idx']; ?>'>delete</button>
						</td>
					</tr>
					<?php
				} 
			?>	
		</table><!-- /table -->
		</div> 
	</div><!-- /col-md-12 -->
</div> <!-- /row -->
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="uploadForm" method="POST" enctype="multipart/form-data" accept="image/*">
	      <div class="modal-header">
					<h4 class="modal-title">Add Banner</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">
	      	<div id="add-product-messages"></div>
	      	<div class="form-group">
				    <div class="w-100 p-5">						
							<input id="fileInput" type="file" name="file" class="form-control mb-2" placeholder="Product Name" class="file-loading" />
							<input id="file" type="text" placeholder="Link" name="link" class="form-control" autocorrect="off" autocapitalize="off" autocomplete="off">
							<p class='mt-2 text-center' style='font-size: 12px; color:#a2a2b0;'>Please include the complete URL, starting with either 'https://' or 'http://'.</p>
						</div>
	        </div> <!-- /form-group-->	     	           	           
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" id="btn_submit" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off">Upload</button>
	      </div> <!-- /modal-footer -->	      
     	</form> <!-- /.form -->	     
    </div> <!-- /modal-content -->    
  </div> <!-- /modal-dailog -->
</div> 
</div> 

<script>
const xhr = new XMLHttpRequest();
let idx;
const btn_deactivate = document.querySelectorAll('.btn-deactivate');
const btn_delete = document.querySelectorAll('.btn-delete');
btn_deactivate.forEach(button => {
	button.addEventListener('click', async (e) => {
		e.preventDefault();
		idx = button.getAttribute('data-idx');
		xhr.open('POST', './php_action/updateBanner.php', true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(JSON.stringify({ idx }));
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				handleResponse(xhr, idx, 'deactived');
			}
		};
	});
});

btn_delete.forEach(button => {
	button.addEventListener('click', async (e) => {
		e.preventDefault();
		idx = parseInt(button.getAttribute('data-idx'));
		xhr.open('POST', './php_action/deleteBanner.php', true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(JSON.stringify({ idx }));
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				handleResponse(xhr, idx, 'deleted');
			}
		};
	});
});
const btn_submit = document.querySelector('#btn_submit');
const fade_background = document.querySelector('.fade_background');
const spinner = document.querySelector('.spinner-border');
btn_submit.addEventListener('click', async (e) => {
	e.preventDefault();
	const file = document.querySelector('#file');
	const fileInput = document.querySelector('#fileInput');
	fade_background.style.display = 'inline-block';
	spinner.style.display = 'inline-block';
	const formData = new FormData();
	formData.append('link', file.value);
	formData.append('file', fileInput.files[0]);
	xhr.open('POST', './php_action/fetchBanner.php', true);
	xhr.send(formData);
	btn_submit.disabled = true;
	xhr.onload = () => {
		spinner.style.display = 'none';
		if (xhr.status == 200) {
			try {
				const data = JSON.parse(xhr.responseText);
				if (data.result == 'success') {
					alert('Success!');
					self.location.href = '/manage.php';
				} else {
					alert('Failed: ' + data.message); // Display the error message
				}
			} catch(error) {
				console.error('Error parsing JSON:', error);
			}
		} else {
			alert('Error: ' + xhr.status);
		}
	};
});
</script>
<script src="custom/js/banners.js?v=1.0"></script>
<?php require_once 'includes/footer.php'; ?>