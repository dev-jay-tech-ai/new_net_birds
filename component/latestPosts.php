<table class='table table-bordered table-hover desktop'> 
  <colgroup>
    <col width='7%' />
    <col width='53%' />
    <col width='15%' />
    <col width='5%' />
    <col width='10%' />
  </colgroup>	
  <thead class='text-bg-primary text-center'>
  <tr>
    <th class='text-center'></th>
    <th class='text-center'>Title</th>
    <th>User</th>
    <th class='text-center'>Views</th>
    <th class='text-center'>Date</th>
  </tr>
  </thead>
  <tbody>
  <?php 
  require_once 'php_action/core.php';
  $sqlData = "SELECT idx, code, subject, user_id, name, content, hit, active, rdate FROM ( SELECT idx, code, subject, user_id, name, content, hit, active, rdate, ROW_NUMBER() OVER (ORDER BY rdate DESC) as row_num FROM aboard UNION SELECT idx, code, subject, user_id, name, content, hit, active, rdate, ROW_NUMBER() OVER (ORDER BY rdate DESC) as row_num FROM pboard UNION SELECT idx, code, subject, user_id, name, content, hit, active, rdate, ROW_NUMBER() OVER (ORDER BY rdate DESC) as row_num FROM rboard UNION SELECT idx, code, subject, user_id, name, content, hit, active, rdate, ROW_NUMBER() OVER (ORDER BY rdate DESC) as row_num FROM jboard UNION SELECT idx, code, subject, user_id, name, content, hit, active, rdate, ROW_NUMBER() OVER (ORDER BY rdate DESC) as row_num FROM prboard ) AS combined WHERE row_num <= 5 ORDER BY rdate DESC LIMIT 7";
  $stmtData = $connect->prepare($sqlData);
  if($stmtData) {
    $stmtData->execute();
    $result = $stmtData->get_result();
    $activeRowCount = 0; // Initialize the variable
    while ($row = $result->fetch_assoc()) {
      $activeRowCount++;
      if ($row['active'] == 1) {
        echo "<tr class='view_detail us-cursor' data-idx='{$row['idx']}'>
          <td class='text-center'>{$activeRowCount}</td>
          <td>{$row['subject']}</td>
          <td>{$row['name']}</td>
          <td class='text-right'>{$row['hit']}</td>
          <td class='text-right'>" . date('Y-m-d', strtotime($row['rdate'])) . "</td>
        </tr>";
      }
    }
  } else {
    die($connect->error);
  }
  ?>
  </tbody>
</table>
<table class='table table-bordered table-hover mobile'>
<?php 
if($stmtData) {
  $stmtData->execute();
  $result = $stmtData->get_result();
  while($row = $result->fetch_assoc()) {
    if($row['active'] == 1) {
    ?>
    <tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>' data-code='<?= $row['code'] ?>'>
      <td style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'>
        <div class='d-flex flex-column'>
          <div class='fw-bold' style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'><span style='color: #a2a2b0;'><?= ucfirst($row['code']); ?> | </span><?= $row['subject']; ?></div>
          <div class='d-flex justify-content-between mt-2 cc-3'>
            <div class='d-flex align-items-center flex-row' style='flex: 2'>
              <div class='board_profile'>
                <?php if($row['user_image'] !== '' && $row['user_image'] !== NULL): ?>
                  <img src='<?= $row['user_image'] ?>' alt='profile image' />
                <?php endif; ?>
              </div>
              <div class='mx-2 align-middle'><?= $row['name']; ?></div>
            </div>
            <div style='flex: 1; text-align: end;'><?= substr($row['rdate'], 0, 10); ?></div>
          </div>
        </div>
      </td>
    </tr>
    <?php
    }
		}
	}
	?>
</table>    
<script>
  const view_detail = document.querySelectorAll('.view_detail');
  view_detail.forEach((box) => {
    box.addEventListener('click', (e) => {
      self.location.href = './view_list.php?code=' + box.dataset.code + '&idx=' + box.dataset.idx;
    });
  });    
</script>