<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php';
	include 'component/popup.php';  

  error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

  $limit = 20;
  $page_limit = 10;
  $page = (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
  $start = ($page - 1) * $limit;
  // Query to get total count
  $sqlCount = "SELECT COUNT(*) as cnt FROM rboard";
  $stmtCount = $connect->prepare($sqlCount);
  
  if ($stmtCount) {
      $stmtCount->execute();
      // Get the result set
      $resultCount = $stmtCount->get_result();
      $row = $resultCount->fetch_assoc();
      $total = $row['cnt'];
  } else {
      // Handle the case where the preparation failed
      die($connect->error);
  }
  // Query to get paginated data
  $sqlData = "SELECT * FROM rboard ORDER BY idx DESC LIMIT $start, $limit";
  $stmtData = $connect->prepare($sqlData);
  if($stmtData) {
      $stmtData->execute();
      // Get the result set
      $resultData = $stmtData->get_result();
      $rs = [];
      while ($row = $resultData->fetch_assoc()) {
        $rs[] = $row;
      }
  } else {
      // Handle the case where the preparation failed
      die($connect->error);
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
							<th class='text-center'>Rate</th>
							<th class='text-center'>Date</th>
						</tr>
					</thead>
					
					<?php 
					$totalRows = count($rs);
					$activeRowCount = ($page - 1) * $limit;
					foreach($rs AS $i => $row) { 
					if ($row['active'] == 1) {
						$activeRowCount++;
					?>
					<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>'>
						<?php 
								if(isset($_SESSION['userId']) && $result['status'] == 1) {
										echo "<td class='text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";		
								} 
						?>
						<td class='text-center'><?= $activeRowCount ?></td>
						<td><?= $row['subject']; ?></td>
						<td class='text-center'><?= $row['name']; ?></td>
						<td class='text-center rate'>
						<?php
								$rating = $row['rate'];
								for ($i = 1; $i <= 5; $i++) {
										$starClass = $i <= $rating ? 'fas fa-star text-warning' : 'fas fa-star star-light';
										echo "<i class='$starClass'></i>";
								}
						?>
						</td>
						<td class='text-center rdate'><?= substr($row['rdate'], 0, 10); ?></td>
						</tr>
					<?php
							} // End of if ($row['active'] == 1)
						} // End of foreach
					?>
				</table>  
				<?php include 'board_m.php';?>  
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
					echo "box.addEventListener('click', () => {";
					echo "self.location.href='./view_review.php?idx=' + box.dataset.idx";
					echo "});";
					echo "});";
				// } 
			?>
			</script>      
			<!-- /table -->   

		</div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div>

<script src="custom/js/review.js"></script>
<?php require_once 'includes/footer.php'; ?>