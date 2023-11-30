<table class='table table-bordered table-hover mobile'>
<?php 
$totalRows = count($rs);
foreach ($rs as $i => $row) {
if ($row['active'] == 1) {
		?>
		<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>' data-code='<?= $code ?>'>
			<?php
			if(isset($_SESSION['userId']) && $result['status'] == 1) {
					echo "<td class='text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";
			}
			?>
			<td style='width: 44px; border-right: 0; vertical-align: middle;'>
				<div class='board_profile'></div>
			</td>
			<td style='border-left: 0;'>
				<div class='d-flex flex-column'>
					<div class='fw-bold'><?= $row['subject']; ?></div>
					<div class='d-flex justify-content-between mt-2 cc-3'>
						<div style='flex: 2'><?= $row['name']; ?></div>
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