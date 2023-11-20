<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

  error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

  $sn = (isset($_GET['sn']) && $_GET['sn'] != '') ? $_GET['sn'] : '';
  $sf = (isset($_GET['sf']) && $_GET['sf'] != '') ? $_GET['sf'] : '';
	$code  = (isset($_GET['code']) && $_GET['code'] != '') ? $_GET['code']: 'private';

	if($code == '') die('Missing code');
	$where = ""; // Initialize $where
	if($sn != '' && $sf !='') {
		switch($sn) {
			case 1: $where .= " AND (subject LIKE '%".$sf."%' OR content LIKE '%".$sf."%' )";
			break;
			case 2: $where .= " AND (subject LIKE '%".$sf."%' )";
			break;
			case 3: $where .= " AND (content LIKE '%".$sf."%' )";
			break;
			case 4: $where .= " AND (name LIKE '%".$sf."%' )";
			break;
		}
	}

  $limit = 10;
  $page_limit = 10;
  $page = (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
  $start = ($page - 1) * $limit;
  
  // Query to get total count
  $sqlCount = "SELECT COUNT(*) as cnt FROM pboard WHERE code='".$code."' " . $where;
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
	$sqlData = "SELECT * FROM pboard WHERE code='".$code."' " . $where . " ORDER BY idx DESC LIMIT $start, $limit";
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

<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active"><?= $board_title ?></li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Bullet Board Write</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">
				<div class="remove-messages"></div>

				<div class="mt-4 mb-3">
					<h1><?= $board_title ?></h1>
				</div>
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
                <th class='text-center'>Writer</th>
                <th class='text-center'>Date</th>
                <th class='text-center'>Views</th>
              </tr>
            </thead>
            <?php foreach($rs AS $row) { ?>
            <tr class='view_detail us-cursor' data-idx='<?= $row['idx'] ?>' data-code='<?= $code ?>'>
              <td class='text-center'><?= $row['idx']; ?></td>
              <td><?= $row['subject']; ?></td>
              <td class='text-center'><?= $row['name']; ?></td>
              <td class='text-center'><?= substr($row['rdate'],0,10); ?></td>
              <td class='text-center'><?= $row['hit']; ?></td>
            </tr>
            <?php } ?>
          </table>    
				</div>
				<div class='container mt-3 d-flex gap-2 w-50'>
					<select name="" id="sn" class="form-select">
						<option value="1"<?= ($sn == 1) ? ' selected' : ''; ?>>Title+content</option>
						<option value="2"<?= ($sn == 2) ? ' selected' : ''; ?>>Title</option>
						<option value="3"<?= ($sn == 3) ? ' selected' : ''; ?>>Content</option>
						<option value="4"<?= ($sn == 4) ? ' selected' : ''; ?>>Writer</option>
					</select>
					<input type='text' id='sf' class='form-control w-2' value='<?= $sf ?>'>
					<button class='btn btn-primary' id='btn_search'>Search</button>		
				</div>
				<div class="mt-3 d-flex gap-2 justify-content-between align-items-start">
        <?php 
          $param = '&code='.$code;
					if($sn != '') $param .= '$sn='.$sn;
					if($sf != '') $param .= '$sf='.$sf;
          $rs_str = my_pagination($total, $limit, $page_limit, $page, $param);
          echo $rs_str;
        ?> 
        <button id='btn_write' class="btn btn-primary">Write</button>
        </div>
        <script>
          const btn_write = document.querySelector('#btn_write');
          btn_write.addEventListener('click', () => {
            self.location.href='./write.php?code<?= $code; ?>'
          })
          const view_detail = document.querySelectorAll('.view_detail');
          view_detail.forEach((box) => {
            box.addEventListener('click', () => {
              self.location.href='./view.php?idx=' + box.dataset.idx + '&code=' + box.dataset.code;
            })
          })
					const btn_search = document.querySelector('#btn_search');
					btn_search.addEventListener('click', () => {
						const sn = document.querySelector('#sn').value;
						const sf = document.querySelector('#sf').value;
						if(sf.value == '') {
							alert('Input search');
							sf.focus()
							return false;
						}
						self.location.href = './list.php?code=private&sn=' + sn + '&sf=' + sf;
					})
        </script>      
				<!-- /table -->   
			</div> <!-- /panel-body -->
		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->


<script src="custom/js/board.js"></script>
<?php require_once 'includes/footer.php'; ?>