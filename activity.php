<?php 
	require_once 'php_action/core.php';
	require_once 'includes/header.php';
	include 'component/auth_session.php';
	include 'component/pagination.php'; 
	$code = (isset($_GET['code']) && $_GET['code'] !== '') ? $_GET['code'] : '';
	include 'component/config.php'; 
	include 'component/searchbar.php'; 
  $sn = (isset($_GET['sn']) && $_GET['sn'] !== '') ? $_GET['sn'] : '';
	$sf = (isset($_GET['sf']) && $_GET['sf'] !== '') ? $_GET['sf'] : '';

?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="dashboard.php">主页</a></li>		  
				<li class="active">我的活动</li>
			</ol>
      <?php 
			searchbar_ui($sn, $sf);
			?>
			<div class="scroll mt-3 mb-2">
        <table class='table table-bordered table-hover'>
				<colgroup>
					<col width='5%' />
					<col width='65%' />
					<col width='10%' />
					<col width='5%' />
					<col width='10%' />
					<col width='10%' />
				</colgroup>
        <thead class='text-bg-primary text-center'>
          <tr>
            <th class='text-center'></th>
            <th class='text-center'>标题</th>
            <th class='text-center'>回到首页</th>
            <th class='text-center'>流量</th>
            <th class='text-center'>日期</th>
            <th class='text-center'></th>
          </tr>
        </thead>
				<tbody>
				<?php 
				require_once 'php_action/fetchUserActivity.php'; 
				$totalRows = count($rs);
				$activeRowCount = ($page - 1) * $limit;
				foreach($rs as $i => $row) { 
					$activeRowCount++;
				?>
				<tr class='view_detail us-cursor' data-idx='<?= $row['post_id']; ?>' data-code='<?= $row['board_code'] ?>'>
					<td class='text-center'><?= $activeRowCount ?></td>
					<td class='title'>
						<?= $row['post_subject']; ?>
					</td>
					<td class='text-center'>
						<?= $row['board_code']; ?>
					</td>
					<td class='text-center'>
						<?= $row['post_views']; ?>
					</td>
					<td class='rdate text-center'>
						<?= substr($row['post_date'], 0, 10); ?>
					</td>
					<td class='rdate text-center untouchable'>
						<button class='btn btn-primary btn-small btn_renew'>刷新</button>
					</td>
				</tr>
				<?php
				} 
				?>
				</tbody>			
        </table>  
        <script>
					const view_detail = document.querySelectorAll('.view_detail');
					const btn_renew = document.querySelectorAll('.btn_renew');
					view_detail.forEach((box) => {
						box.addEventListener('click', (e) => {
							const untouchable = box.querySelector('.untouchable');
							if (!e.target.closest('.untouchable')) {
								self.location.href = './view_list.php?code=' + box.dataset.code + '&idx=' + box.dataset.idx;
						  }
						});
					});    
					btn_renew.forEach((button) => {
						button.addEventListener('click', (e) => {
							e.preventDefault();
							const xhr = new XMLHttpRequest();
							const tr = button.parentNode.parentNode;
							const idx = tr.getAttribute('data-idx');
							const code = tr.getAttribute('data-code');
							const confirmRenew = confirm("你确定要再次列出吗？");
    					if(!confirmRenew) return;
							xhr.open('POST', './php_action/updateDate.php', true); 
						  xhr.setRequestHeader('Content-Type', 'application/json');
							xhr.send(JSON.stringify({ idx, code }));
							xhr.onreadystatechange = function () {
								if(xhr.readyState === XMLHttpRequest.DONE) {
									if(xhr.status == 200) {
										try {
										const data = JSON.parse(xhr.responseText);
										if (data.result == 'success') {
											alert('成功重新列出');
											location.reload();
										} else {
											alert(`Failed to : ${data.message}`);
										}
										} catch (error) {
											console.error('Error parsing JSON:', error);
										}
									} else {
											alert(`Error: ${xhr.status}`);
									}
								}
							};
						});
          });
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
      </div>
    </div> <!-- /col-md-12 -->
	</div> <!-- /row -->
</div><!-- /container -->
<?php require_once 'includes/footer.php'; ?>