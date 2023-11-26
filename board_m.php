<table class='table table-bordered table-hover mobile'>
<?php 
$totalRows = count($rs);
foreach ($rs as $i => $row) {
if ($row['active'] == 1) {
		?>
		<tr class='view_detail us-cursor' data-idx='<?= $row['idx']; ?>' data-code='<?= $code ?>'>
			<?php
			if (isset($_SESSION['userId']) && $result['status'] == 1) {
					echo "<td class='text-center'><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";
			}
			?>
			<td>
				<div class='d-flex flex-column'>
					<div class='fw-bold'><?= $row['subject']; ?></div>
					<div class='d-flex justify-content-between mt-2 cc-3'>
						<div><?= $row['name']; ?></div>
						<div><?= substr($row['rdate'], 0, 10); ?></div>
					</div>
				</div>
			</td>
		</tr>
		<?php
			}
		}
		?>
	</table>    