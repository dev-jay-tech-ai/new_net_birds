<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

  error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

  $limit = 10;
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
				<li class="active">Review</li>
			</ol>

			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Review Bullet Board</div>
				</div> <!-- /panel-heading -->
				<div class="panel-body">
					<div class="remove-messages"></div>
					<?php 
					if(isset($_SESSION['userId']) && $result['active'] == 1) {
						echo "<div class='d-flex justify-content-end'>
						<button id='btn-write' class='btn btn-primary'>Write</button>
						</div>";	
					} else {
						echo '<div class="alert alert-danger" role="alert">
						<i class="glyphicon glyphicon-exclamation-sign"></i>
						You are required to pay for the utilisation of this service
						</div>';	
					}
					?>
					<div class="mt-4 mb-3"></div>
					<div class="mb-2 d-flex gap-2">
						<table class='table table-bordered table-hover'>
							<colgroup>
								<col width='7%' />
								<col width='63%' />
								<col width='10%' />
								<col width='10%' />
								<col width='10%' />
							</colgroup>
							<thead class='text-bg-primary text-center'>
								<tr>
									<th class='text-center'>Index</th>
									<th class='text-center'>Title</th>
									<th class='text-center'>User</th>
									<th class='text-center'>Date</th>
									<th class='text-center'>Rate</th>
									<?php 
										if(isset($_SESSION['userId']) && $result['status'] == 1) {
											echo "<th class='text-center'>-</th>";		
										} 
									?>
								</tr>
							</thead>
							<?php foreach($rs AS $row) { ?>
							<tr class='view_detail us-cursor' data-idx='<?= $row['idx'] ?>' data-code='<?= $code ?>'>
								<td class='text-center'><?= $row['idx']; ?></td>
								<td><?= $row['subject']; ?></td>
								<td class='text-center'><?= $row['name']; ?></td>
								<td class='text-center'><?= substr($row['rdate'],0,10); ?></td>
								<td class='text-center'><?= $row['rate']; ?></td>
								<?php 
									if(isset($_SESSION['userId']) && $result['status'] == 1) {
										echo "<td class='text-center'><button class='btn btn-secondary'>Delete</button></td>";		
									} 
								?>
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
						const btn_write = document.querySelector('#btn-write');
						btn_write.addEventListener('click', () => {
							self.location.href='./write_review.php?code<?= $code; ?>'
						})
						const view_detail = document.querySelectorAll('.view_detail');
						view_detail.forEach((box) => {
							box.addEventListener('click', () => {
								self.location.href='./view_review.php?idx=' + box.dataset.idx + '&code=' + box.dataset.code;
							})
						})
					</script>      
					<!-- /table -->   
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->		
		</div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div>

<script src="custom/js/review.js"></script>
<?php require_once 'includes/footer.php'; ?>