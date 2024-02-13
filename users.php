<?php 
require_once 'php_action/core.php';
require_once 'includes/header.php'; 
require_once 'component/auth_session.php'; 
include 'component/pagination.php'; 

$sf = (isset($_GET['sf']) && $_GET['sf'] !== '') ? $_GET['sf'] : '';
$sqlCount = "SELECT COUNT(*) as cnt FROM users";


$stmtCount = $connect->prepare($sqlCount);
if ($stmtCount) {
	$stmtCount->execute();
	$resultCount = $stmtCount->get_result();
	$row = $resultCount->fetch_assoc();
	$total = $row['cnt'];
} else {
	die($connect->error);
}

$sqlData = "SELECT * FROM users";
if (!empty($sf)) {
  $sqlData .= " WHERE username LIKE ? OR email LIKE ?";
}
$sqlData .= " ORDER BY user_id DESC LIMIT $start, $limit";

$stmtData = $connect->prepare($sqlData);
if($stmtData) {
	if(!empty($sf)) {
		$sfParam = "%$sf%";
		$stmtData->bind_param("ss", $sfParam, $sfParam);
	}
	$stmtData->execute();
	$resultData = $stmtData->get_result();
	$rs = [];
	while ($row = $resultData->fetch_assoc()) {
		$rs[] = $row;
	}
} else die($connect->error);
?>

<div class="container">
<div class="row">
	<div class="col-md-12 mb-5">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active">Users</li>
		</ol>
		<div class="searchbar d-flex div-action pull pull-left">
			<input type="text" class="search form-control" id="sf" value="<?= $sf; ?>">
			<button class="btn btn-secondary ms-1" id="btn_search"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
		</div>
		
		<div class="div-action pull pull-right" style="padding-bottom:20px;">
			<button class="btn btn-primary" data-toggle="modal" id="addUserModalBtn" data-target="#addUserModal">Add User</button>
			<button id='btn-delete' class="btn btn-secondary">Delete</button>
		</div> <!-- /div-action -->		
		<div class='scroll'>
			<table class='table table-bordered table-hover'>
			<thead class='text-bg-primary text-center'>
				<tr>
					<th class='text-center'><input class='form-check-input' type='checkbox' value='' id='checkAll'></th>
					<th></th>							
					<th style="width:5%;">Profile</th>							
					<th>Name</th>
					<th>Email</th>
					<th>Active</th>
					<th>Status</th>
					<th>Credit</th>
					<th>Registered</th>
					<th>IP</th>
					<th>Options</th>
				</tr>
			</thead>
			<?php 
				$totalRows = count($rs);
				$activeRowCount = ($page - 1) * $limit;
				foreach ($rs as $i => $row) {
					$activeRowCount++;
				?>
				<tr class='view_detail us-cursor' data-idx='<?= $row['user_id']; ?>' data-code='<?= $code ?>'>
					<td class='text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>
					<td class='text-center'><?= $activeRowCount; ?></td>
					<td><div class='board_profile'>
					<?php if ($row['user_image'] !== '' && $row['user_image'] !== NULL): ?>
						<img src='<?= $row['user_image'] ?>' alt='profile image' />
					<?php endif; ?>
					</div></td>
					<td><?= $row['username']; ?></td>
					<td><?= $row['email']; ?></td>
					<td class='text-center'>
					<?php 
						if($row['active'] === 1) {
							echo "<label class='label label-primary'>paid</label>";
						} else {
							echo "<label class='label label-warning'>unpaid</label>";
						}; 
					?>
					</td>
					<td class='text-center'>
					<?php 
						if($row['status'] === 1) {
							echo "<label class='label label-danger'>admin</label>";
						} else {
							echo "<label class='label label-success'>guest</label>";
						}; 
					?>
					</td>
					<td class='text-center'><?= $row['credit']; ?></td>
					<td class='rdate text-center'><?= $row['rdate'] ?></td>
					<td class='rdate text-center'><?= $row['ip']; ?></td>
					<td class='text-center'>
						<div class="btn-group">
							<button type='button' class="btn btn-primary editUserModalBtn" data-user-id="<?= $row['user_id'] ?>">Edit</button>
							<?php 
								if($row['status'] === 0) {
									echo '<button class="btn btn_block_user" data-user-id="' . $row['user_id'] . '_' . $row['status'] . '">Unblock</button>';
								} else {
									echo '<button class="btn btn-danger btn_block_user" data-user-id="' . $row['user_id'] . '_' . $row['status'] . '">Block</button>';
								}
							?>
							<button class="btn btn-secondary btn_delete_user" data-user-id="<?= $row['user_id'] ?>">Delete</button>
						</div>
					</td>
				</tr>
				<?php
				}
				?>
			</table><!-- /table -->
		</div>
	</div> <!-- /col-md-12 -->
	<div class="mt-3 d-flex gap-2 justify-content-center">
		<?php 
			//$param = '&code='.$code;
			$param = '';
			$rs_str = my_pagination($total, $limit, $page_limit, $page, $param);
			echo $rs_str;
		?> 
		</div>
</div> <!-- /row -->
</div>

<div class="modal" id="editUserModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" id="submitEditForm" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title">Edit User</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class='view-user-data'></div>
				</div> <!-- /modal-body -->
				<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" id="editUserBtn" data-loading-text="Loading..." autocomplete="off">Edit</button>
			</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dialog -->
</div>

<?php include 'component/modal_adduser.php'; ?>

</div> 
<script>
const pathname = window.location.pathname;
const search = window.location.search;
const btn_search = document.querySelector('#btn_search');
btn_search.addEventListener('click', (e) => {
  e.preventDefault();
  const sf = document.querySelector('#sf');
  if(sf.value == '') {
    self.location.href='.'+pathname+search;
    return false;
  }
  self.location.href='.'+pathname+'?sf=' + sf.value;
})
</script> 
<script src='/custom/js/admin.js?v=<?php echo time(); ?>'></script> 
<?php require_once 'includes/footer.php'; ?>