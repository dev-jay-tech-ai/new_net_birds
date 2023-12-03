<?php 
	require_once 'php_action/core.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 
	include 'component/popup.php'; 

  // Query to get total count
  $sqlCount = "SELECT COUNT(*) as cnt FROM pboard WHERE code='".$code."'";
  $stmtCount = $connect->prepare($sqlCount);
  if ($stmtCount) {
		$stmtCount->execute();
		$resultCount = $stmtCount->get_result();
		$row = $resultCount->fetch_assoc();
		$total = $row['cnt'];
  } else {
    die($connect->error);
  }
	// Default SQL query without location condition
	$sqlData = "	SELECT a.*, u.user_image FROM pboard a LEFT JOIN users u ON a.user_id = u.user_id WHERE code='".$code."' ORDER BY idx DESC LIMIT $start, $limit";
  $stmtData = $connect->prepare($sqlData);
  if($stmtData) {
		$stmtData->execute();
		$resultData = $stmtData->get_result();
		$rs = [];
		while ($row = $resultData->fetch_assoc()) {
			$rs[] = $row;
		}
  } else die($connect->error);
	$totalRows = count($rs);
	$activeRowCount = ($page - 1) * $limit;
	$selectedLocation = 0; 
	if(isset($_GET['location'])) {
		$locationParam = $_GET['location'];
		$locationMapping = ['london' => 0, 'manchester' => 1,'glasgow' => 2, 'nottingham' => 3, 'birmingham' => 4, 'others' => 5];
		if(array_key_exists($locationParam, $locationMapping)) {
			$selectedLocation = $locationMapping[$locationParam];
			$sqlData = "SELECT * FROM pboard WHERE code = '$code' AND location = $selectedLocation ORDER BY idx DESC LIMIT $start, $limit";
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
			<?php 
			include 'component/nav_location.php'; 
			if(isset($_SESSION['userId']) /** && $result['active'] == 1 */) {
				if($result['status'] == 1) {
					echo "<div class='d-flex gap-2 justify-content-end'>
					<button id='btn-write' class='btn btn-primary'>Write</button>
					<button id='btn-write' class='btn btn-secondary'>Delete</button>
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
			<div class="mb-2">
				<table class='table table-bordered table-hover desktop'>
					<colgroup>
						<?php 
						if(isset($_SESSION['userId']) && $result['status'] == 1) {
							echo "
							<col width='4%' />
							<col width='6%' />
							<col width='50%' />
							<col width='10%' />
							<col width='10%' />
							<col width='10%' />";		
						} else {
							echo "
							<col width='7%' />
							<col width='63%' />
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
							<th class='text-center'>Date</th>
						</tr>
					</thead>
					<?php 
					foreach ($rs as $i => $row) {
					if ($row['active'] == 1) {
						$activeRowCount++;
					?>
						<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>' data-code='<?= $code ?>'>
							<?php
								if(isset($_SESSION['userId']) && $result['status'] == 1) {
									echo "<td class='text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";
								}
							?>
							<td class='text-center'><?= $activeRowCount; ?></td>
							<td><?= $row['subject']; ?></td>
							<td class='text-center'><?= $row['name']; ?></td>
							<td class='text-center'><?= $row['hit']; ?></td>
							<td class='rdate text-center'><?= substr($row['rdate'], 0, 10); ?></td>
						</tr>
						<?php
					} 
				}
				?>	
				</table>    
				<?php include 'board_m.php';?>
			</div>
			<div class="mt-3 d-flex gap-2 justify-content-center">
			<?php 
				$param = '&code='.$code;
				$rs_str = my_pagination($total, $limit, $page_limit, $page, $param);
				echo $rs_str;
			?> 
			</div>
			<script>
				<?php 
				// if(isset($_SESSION['userId'])) {
					echo "const btn_write = document.querySelector('#btn-write');";
					echo "btn_write && btn_write.addEventListener('click', () => {";
					echo "self.location.href='./write_private.php?code=$code';";
					echo "});";
					echo "const view_detail = document.querySelectorAll('.view_detail');";
					echo "view_detail.forEach((box) => {";
					echo "box.addEventListener('click', () => {";
					echo "self.location.href='./view_private.php?idx=' + box.dataset.idx + '&code=' + box.dataset.code;";
					echo "});";
					echo "});";
				// } 
				?>
			</script><!-- /table -->   
		</div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div>

<?php require_once 'includes/footer.php'; ?>