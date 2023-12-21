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

  include 'php_action/fetchList.php'; 
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="dashboard.php">主页</a></li>		  
				<li class="active"><?= $board_title ?></li>
			</ol>
      <?php 
      if($code !=='agent') {
        include 'component/nav_location.php'; 
      }
      searchbar_ui($sn, $sf);
			if(isset($_SESSION['userId']) /** && $result['active'] == 1 */) {
				if($result['status'] == 1) {
					echo "<div class='d-flex gap-2 justify-content-end'>
					<button id='btn-write' class='btn btn-primary'>Write</button>
					<button id='btn-delete' class='btn btn-secondary'>Delete</button>
					</div>";		
				} else {
					echo "<div class='d-flex justify-content-end mb-3'>
					<button id='btn-write' class='btn btn-primary'>编辑内容</button>
					</div>";		
				}
			} 
			include 'component/content_login.php';
			?>
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
            <th class='text-center'>标题</th>
            <th class='text-center'>用户名</th>
            <th class='text-center'>流量</th>
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
            <?php
            if($code == 'review') {
              echo "<td class='text-center rate' >";
                $rating = $row['rate'];
                for ($i = 1; $i <= 5; $i++) {
                    $starClass = $i <= $rating ? 'fas fa-star text-warning' : 'fas fa-star star-light';
                    echo "<i class='$starClass'></i>";
                }
              echo "</td>";
            }
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
						<td class='rdate text-center' style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'>
						<?php
						if(isset($_SESSION['userId']) && $result['status'] == 1) {
							echo $row['rdate'];
						} else {
							echo substr($row['rdate'], 0, 10);
						}
						?>
						</td>
						<?php
						if(isset($_SESSION['userId']) && $result['status'] == 1) {
							echo "<td class='untouchable text-center'>
							<div class='btn-group'>
							<button class='btn-pin btn btn-danger' data-idx='{$row['idx']}_{$row['is_pinned']}'>";
							if ($row['is_pinned'] == 1) {
								echo "unpin";
							} else {
								echo "pin";
							}
							echo"</button>
							<button class='btn-deactivate btn btn-secondary' data-idx='{$row['idx']}_{$row['active']}'>";
							if ($row['active'] == 1) {
								echo "Hide";
							} else {
								echo "Show";
							}
							echo "</button>
							<button class='btn-delete btn btn-primary' data-idx='{$row['idx']}_{$row['active']}'>Delete</button>
							</div>
							</td>";
						}
						?>
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
        <script>
        <?php 
          echo "const btn_write = document.querySelector('#btn-write');";
          echo "btn_write && btn_write.addEventListener('click', () => {";
          echo "self.location.href='./write_list.php?code=$code';";
          echo "});";
          echo "const view_detail = document.querySelectorAll('.view_detail');";
          echo "view_detail.forEach((box) => {";
          echo "box.addEventListener('click', (e) => {";
          echo "const untouchable = box.querySelector('.untouchable');";
          echo "if (!e.target.closest('.untouchable')) {";
					echo "self.location.href='./view_list.php?code=" . $code . "&idx=' + box.dataset.idx;";
          echo "}";
          echo "});";
          echo "});";
        ?>
			</script><!-- /table --> 
      </div>
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
    </div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div><!-- /container -->
<?php if($code == 'review') echo "<script src='custom/js/review.js'></script>"; ?>
<script src='/custom/js/search.js?v=<?php echo time(); ?>'></script> 
<script src='/custom/js/admin.js?v=<?php echo time(); ?>'></script> 
<?php require_once 'includes/footer.php'; ?>