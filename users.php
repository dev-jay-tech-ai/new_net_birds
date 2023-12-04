<?php 
require_once 'php_action/core.php';
require_once 'includes/header.php'; 
require_once 'component/auth_session.php'; 
include 'component/pagination.php'; 

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

$sqlData = "SELECT * FROM users ORDER BY user_id DESC LIMIT $start, $limit";
$stmtData = $connect->prepare($sqlData);
if($stmtData) {
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
		<div class="div-action pull pull-right" style="padding-bottom:20px;">
			<button class="btn btn-primary" data-toggle="modal" id="addUsertModalBtn" data-target="#addUserModal">Add User</button>
			<button id='btn-delete' class="btn btn-secondary">Delete</button>
		</div> <!-- /div-action -->		
		<div style='width: 100%; overflow: scroll;'>
			<table class='table table-bordered table-hover'>
			<thead class='text-bg-primary text-center'>
				<tr>
					<th class='text-center'></th>
					<th></th>							
					<th style="width:5%;">Profile</th>							
					<th>Name</th>
					<th>Email</th>
					<th>Active</th>
					<th>Status</th>
					<th>Credit</th>
					<th>Registered</th>
					<th>IP</th>
					<th style="width:10%;">Options</th>
				</tr>
			</thead>
			<?php 
				$totalRows = count($rs);
				$activeRowCount = ($page - 1) * $limit;
				foreach ($rs as $i => $row) {
					$activeRowCount++;
				?>
				<tr class='view_detail us-cursor' data-idx='<?= $row['user_id']; ?>' data-code='<?= $code ?>'>
					<td class='text-center'><input class='_checkbox form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>
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
						<button class="btn btn-primary" data-toggle="modal" id="editUserModalBtn" data-target="#editUserModal">Edit</button>
						<button class="btn btn-secondary" >Delete</button>
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
			$param = '&code='.$code;
			$rs_str = my_pagination($total, $limit, $page_limit, $page, $param);
			echo $rs_str;
		?> 
		</div>
</div> <!-- /row -->
</div>

<?php include 'component/modal_edituser.php'; ?>
<?php include 'component/modal_adduser.php'; ?>

</div> 
<script src='/custom/js/admin.js?v=<?php echo time(); ?>'></script> 
<?php require_once 'includes/footer.php'; ?>