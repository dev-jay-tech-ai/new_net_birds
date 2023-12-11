<?php 
	require_once 'php_action/core.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	$code = (isset($_GET['code']) && $_GET['code'] !== '') ? $_GET['code'] : '';
	include 'component/config.php'; 
	include 'component/popup.php'; 
	include 'component/searchbar.php'; 
  $sn = (isset($_GET['sn']) && $_GET['sn'] !== '') ? $_GET['sn'] : '';
	$sf = (isset($_GET['sf']) && $_GET['sf'] !== '') ? $_GET['sf'] : '';

?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="dashboard.php">Home</a></li>		  
				<li class="active">Activity</li>
			</ol>
      <?php 
			searchbar_ui($sn, $sf);
			?>
			<!-- <div class='d-flex justify-content-end mb-3'>
			<button id='btn-write' class='btn btn-primary'>Write</button>
			</div> -->

      </div>
			<div class="scroll mt-3 mb-2">
        <table class='table table-bordered table-hover 
				<?php 
					if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
						echo 'desktop';
					}
				?>
				'>
				<colgroup>
				<?php include 'component/colgroup.php'; ?>
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
            <?php 
              if($code == 'review') echo "<th class='text-center'>Rate</th>";
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
				<tbody>
        <?php 
					foreach($rs as $i => $row) { 
						$activeRowCount++;
					?>
					<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>'>
						<?php 
							if(isset($_SESSION['userId']) && $result['status'] == 1) {
									echo "<td class='untouchable text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";		
							} 
						?>
						<td class='text-center' style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'><?= $activeRowCount ?></td>
						<td class='title' style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'>
							<?= $row['subject']; ?>
						</td>
						<td class='text-center' style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'>
							<?= $row['name']; ?>
						</td>
						<td class='text-center' style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'>
							<?= $row['hit']; ?>
						</td>
						<td class='rdate text-center' style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'>
						<?php
						if(isset($_SESSION['userId']) && $result['status'] == 1) {
							echo $row['rdate'];
						} else {
							echo substr($row['rdate'], 0, 10);
						}
						?>
						</td>
						</tr>
						<?php
					} 
					?>
					</tbody>
        </table>  
        <?php 
					if(!(isset($_SESSION['userId']) && $result['status'] == 1)) {
						include 'board_m.php';
					}
				?>
        <div class="mt-3 d-flex gap-2 justify-content-center">
        <?php 
          $param = '&code='.$code;
          if($sn != '') {
            $param .= '&sn='.$sn;
          }	
          if($sf != '') {
            $param .= '&sf='.$sf;
          }
          if(!empty($total)) {
            $rs_str = my_pagination($total, $limit, $page_limit, $page, $param);
            echo $rs_str;
          }
        ?> 
        </div>
        <script>
        <?php 
          echo "const view_detail = document.querySelectorAll('.view_detail');";
          echo "view_detail.forEach((box) => {";
          echo "box.addEventListener('click', (e) => {";
          echo "const isCheckbox = e.target.type === 'checkbox';";
          echo "const untouchable = box.querySelector('.untouchable');";
          echo "if (!e.target.closest('.untouchable')) {";
					echo "self.location.href='./view_list.php?code=" . $code . "&idx=' + box.dataset.idx;";
          echo "}";
          echo "});";
          echo "});";
        ?>
			</script><!-- /table --> 
      </div>
    </div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div><!-- /container -->

<?php if($code == 'review') echo "<script src='custom/js/review.js'></script>"; ?>
<script src='/custom/js/search.js?v=<?php echo time(); ?>'></script> 
<script src='/custom/js/admin.js?v=<?php echo time(); ?>'></script> 
<?php require_once 'includes/footer.php'; ?>