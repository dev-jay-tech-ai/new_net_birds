<table class='table table-bordered table-hover mobile'>
<?php 
$totalRows = count($rs);
foreach ($rs as $i => $row) {
	if($row['active'] == 1) {
	?>
	<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>' data-code='<?= $code ?>'>
		<?php
		if(isset($_SESSION['userId']) && $result['status'] == 1) {
				echo "<td class='_checkbox text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";
		}
		?>
		<td style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'>
			<div class='d-flex flex-column'>
				<div class='fw-bold' style='<?= $row['is_pinned'] ? "background: #4A4A6A;" : "" ?>'><?= $row['subject']; ?></div>
				<div class='d-flex justify-content-between mt-2 cc-3'>
					<div class='d-flex align-items-center flex-row' style='flex: 2'>
						<div class='board_profile'>
							<?php if($row['user_image'] !== '' && $row['user_image'] !== NULL): ?>
								<img src='<?= $row['user_image'] ?>' alt='profile image' />
							<?php endif; ?>
						</div>
						<div class='mx-2 align-middle'><?= $row['name']; ?></div>
					</div>
					<div style='flex: 1; text-align: end;'><?php if(isset($row['rate'])) {
						$rating = $row['rate'];
						for ($i = 1; $i <= 5; $i++) {
							$starClass = $i <= $rating ? 'fas fa-star text-warning' : 'fas fa-star star-light';
							echo "<i class='$starClass'></i>";
						}
					} ?></div>
					<div style='flex: 1; text-align: end;'><?= substr($row['rdate'], 0, 10); ?></div>
				</div>
			</div>
		</td>
	</tr>
	<?php
		}
	}
	?>
	</table>    