<?php 
	require_once 'php_action/core.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php';
	include 'component/popup.php';  

	$sqlCount = "SELECT COUNT(*) as cnt FROM rboard";
	if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
		$sqlCount .= " WHERE active = 1";
	}
	$stmtCount = $connect->prepare($sqlCount);
	if ($stmtCount) {
		$stmtCount->execute();
		$resultCount = $stmtCount->get_result();
		$row = $resultCount->fetch_assoc();
		$total = $row['cnt'];
	} else {
		die($connect->error);
	}
	
	$sqlData = "SELECT a.*, u.user_image FROM rboard a LEFT JOIN users u ON a.user_id = u.user_id";
	if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
		$sqlData .= " WHERE a.active = 1";
	}
	$sqlData .= " ORDER BY a.idx DESC LIMIT $start, $limit";
	$stmtData = $connect->prepare($sqlData);
	if ($stmtData) {
		$stmtData->execute();
		$resultData = $stmtData->get_result();
		$rs = [];
		while ($row = $resultData->fetch_assoc()) {
				$rs[] = $row;
		}
	} else {
			die($connect->error);
	}
	

	$totalRows = count($rs);
	$activeRowCount = ($page - 1) * $limit;
	$selectedLocation = 0; 
	if(isset($_GET['location'])) {
		$locationParam = $_GET['location'];
		$locationMapping = ['london' => 0, 'manchester' => 1,'glasgow' => 2, 'nottingham' => 3, 'birmingham' => 4, 'others' => 5];
		if(array_key_exists($locationParam, $locationMapping)) {
			$selectedLocation = $locationMapping[$locationParam];
			$sqlData = "SELECT * FROM rboard WHERE location = $selectedLocation ORDER BY idx DESC LIMIT $start, $limit";
			$stmtData = $connect->prepare($sqlData);
			if($stmtData) {
				$stmtData->execute();
				$resultData = $stmtData->get_result();
				$rs = [];
				while ($row = $resultData->fetch_assoc()) $rs[] = $row;
			} else die($connect->error);
		}
	}

?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="dashboard.php">Home</a></li>		  
				<li class="active"><?= $board_title ?></li>
			</ol>
			<div class="remove-messages"></div>
			<?php 
			$code = 'review';
			include 'component/nav_location.php'; 
			if(isset($_SESSION['userId']) /** && $result['active'] == 1 */) {
				if($result['status'] == 1) {
					echo "<div class='d-flex gap-2 justify-content-end'>
					<button id='btn-write' class='btn btn-primary'>Write</button>
					<button id='btn-delete' class='btn btn-secondary'>Delete</button>
					</div>";		
				} else {
					echo "<div class='d-flex justify-content-end mb-3'>
					<button id='btn-write' class='btn btn-primary'>Write</button>
					</div>";		
				}	
			}
			include 'component/content_login.php'; 
			?>
			</div>
			<div class="scroll mb-2">
				<table class='table table-bordered table-hover
				<?php 
					if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
						echo 'desktop';
					}
				?>
				'>
					<colgroup>
					<?php 
						if(isset($_SESSION['userId']) && $result['status'] == 1) {
							echo "
							<col width='4%' />
							<col width='6%' />
							<col width='25%' />
							<col width='5%' />
							<col width='5%' />
							<col width='10%' />    
							<col width='10%' />    
							<col width='5%' />    
							<col width='10%' />";
						} else {
							echo "
							<col width='7%' />
							<col width='53%' />
							<col width='10%' />
							<col width='10%' />
							<col width='10%' />
							<col width='10%' />";
						}
					?>
					</colgroup>
					<thead class='text-bg-primary text-center'>
						<tr>
							<?php 
								if(isset($_SESSION['userId']) && $result['status'] == 1) {
									echo "<th class='text-center'></th>";		
								} 
							?>
							<th class='text-center'></th>
							<th class='text-center'>Title</th>
							<th class='text-center'>User</th>
							<th class='text-center'>Views</th>
							<th class='text-center'>Rate</th>
							<?php 
								if(isset($_SESSION['userId']) && $result['status'] == 1) {
									echo "<th class='text-center'>Active</th>";		
								} 
							?>
							<th class='text-center'>Date</th>
							<?php 
								if(isset($_SESSION['userId']) && $result['status'] == 1) {
									echo "<th class='text-center'></th>";		
								} 
							?>
						</tr>
					</thead>
					<?php 
					foreach($rs as $i => $row) { 
						$activeRowCount++;
					?>
					<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>'>
						<?php 
							if(isset($_SESSION['userId']) && $result['status'] == 1) {
									echo "<td class='_checkbox text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";		
							} 
						?>
						<td class='text-center'><?= $activeRowCount ?></td>
						<td class='title'><?= $row['subject']; ?></td>
						<td class='text-center'><?= $row['name']; ?></td>
						<td class='text-center'><?= $row['hit']; ?></td>
						<td class='text-center rate'>
						<?php
							$rating = $row['rate'];
							for ($i = 1; $i <= 5; $i++) {
									$starClass = $i <= $rating ? 'fas fa-star text-warning' : 'fas fa-star star-light';
									echo "<i class='$starClass'></i>";
							}
						?>
						</td>
						<?php
						if(isset($_SESSION['userId']) && $result['status'] == 1) {
							echo "<td class='text-center'>";
							if($row['active'] == 1) {
								echo "<label class='label label-success'>active</label>";
							} else {
								echo "<label class='label label-danger'>deactive</label>";
							}
							echo "</td>";
						}
						?>
						<td class='rdate text-center'>
						<?php
						if (isset($_SESSION['userId']) && $result['status'] == 1) {
								echo $row['rdate'];
						} else {
								echo substr($row['rdate'], 0, 10);
						}
						?>
						</td>
						<?php
						if (isset($_SESSION['userId']) && $result['status'] == 1) {
							echo "<td class='text-center'>
							<div class='btn-group'>
							<button class='btn-deactivate btn btn-secondary me-1' data-idx='{$row['idx']}_{$row['active']}'>";
							if ($row['active'] == 1) {
								echo "Hide";
							} else {
								echo "Show";
							}
							echo "</button>
							<button class='btn-delete btn btn-primary' data-idx='{$row['idx']}'>Delete</button>
							</div>
							</td>";
						}
						?>
						</tr>
					<?php
					} // End of if ($row['active'] == 1)
					?>
				</table>  
				<?php 
					if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
						include 'board_m.php';
					}
				?>
			</div>
			<div class="mt-3 d-flex gap-2 justify-content-center">
			<?php 
				$rs_str = my_pagination($total, $limit, $page_limit, $page, '');
				echo $rs_str;
			?> 
			</div>
			<script>
			<?php 
				/**if(isset($_SESSION['userId']) && $result['active'] == 1 ) {*/
				echo "const btn_write = document.querySelector('#btn-write');";
				echo "btn_write && btn_write.addEventListener('click', () => {";
				echo "self.location.href='./write_review.php';";
				echo "});";
				echo "const view_detail = document.querySelectorAll('.view_detail');";
				echo "view_detail.forEach((box) => {";
				echo "box.addEventListener('click', (e) => {";
				echo "const isCheckbox = e.target.type === 'checkbox';";
				echo "const checkboxCell = box.querySelector('._checkbox');";
				echo "if(!(e.target.type === 'checkbox' || (checkboxCell && checkboxCell.contains(e.target)))) {";
				echo "self.location.href='./view_review.php?idx=' + box.dataset.idx;";
				echo "};";
				echo "});";
				echo "});";
			// } 
			?>
			</script><!-- /table -->   
		</div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div>
<script src="custom/js/review.js"></script>
<script src='/custom/js/admin.js?v=<?php echo time(); ?>'></script> 
<?php require_once 'includes/footer.php'; ?>