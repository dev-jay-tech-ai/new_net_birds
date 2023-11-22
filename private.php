<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 
	include 'component/popup.php'; 

  error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

  $limit = 10;
  $page_limit = 10;
  $page = (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
  $start = ($page - 1) * $limit;
  
  // Query to get total count
  $sqlCount = "SELECT COUNT(*) as cnt FROM pboard WHERE code='".$code."'";
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
  $sqlData = "SELECT * FROM pboard WHERE code='".$code."'ORDER BY idx DESC LIMIT $start, $limit";
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

			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Private Bullet Board</div>
				</div> <!-- /panel-heading -->
				<div class="panel-body">
					<div class="remove-messages"></div>
					<?php 
					if(isset($_SESSION['userId']) && $result['active'] == 1) {
						if($result['status'] == 1) {
							echo "<div class='d-flex gap-2 justify-content-end'>
							<button id='btn-write' class='btn btn-primary'>Write</button>
							<button id='btn-write' class='btn btn-secondary'>Delete</button>
							</div>";		
						} else {
							echo "<div class='d-flex justify-content-end'>
							<button id='btn-write' class='btn btn-primary'>Write</button>
							</div>";		
						}
					} 
					?>
					<div class="mt-4 mb-3"></div>
					<div class="mb-2 d-flex gap-2">
						<table class='table table-bordered table-hover'>
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
								$totalRows = count($rs);
								foreach($rs AS $i => $row) { 
								$descIdx = $totalRows - $i;
							?>
							<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>' data-code='<?= $code ?>'>
								<?php 
									if(isset($_SESSION['userId']) && $result['status'] == 1) {
										echo "<td class='text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";		
									} 
								?>
								<td class='text-center'><?= $descIdx; ?></td>
								<td><?= $row['subject']; ?></td>
								<td class='text-center'><?= $row['name']; ?></td>
								<td class='text-center'><?= $row['hit']; ?></td>
								<td class='text-center' style='font-size: 14px;'><?= substr($row['rdate'],0,10); ?></td>
							</tr>
							<?php } ?>
						</table>    
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
						if(isset($_SESSION['userId'])) {
							echo "const btn_write = document.querySelector('#btn-write');";
							echo "btn_write && btn_write.addEventListener('click', () => {";
							echo "self.location.href='./write.php?code=$code';";
							echo "});";
							echo "const view_detail = document.querySelectorAll('.view_detail');";
							echo "view_detail.forEach((box) => {";
							echo "box.addEventListener('click', () => {";
							echo "self.location.href='./view.php?idx=' + box.dataset.idx + '&code=' + box.dataset.code;";
							echo "});";
							echo "});";
						} 
						?>
					</script>   
					<!-- /table -->   
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->		
		</div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div>

<?php require_once 'includes/footer.php'; ?>