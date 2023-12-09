<?php 
require_once 'php_action/core.php';

$sqlData = "SELECT * FROM banners WHERE active=1";
$stmtData = $connect->prepare($sqlData); // Corrected variable name
if($stmtData) {
  $stmtData->execute();
  $result = $stmtData->get_result();
  $activeRowCount = 0; // Initialize the variable
  while ($row = $result->fetch_assoc()) {
    if ($row['active'] == 1) {
      $activeRowCount++;
      echo "<div class='banner_advert'>";
      echo "<a href='" . $row['link'] . "' target='_blank'>";
      echo "<img src='" . $row['img'] . "' alt='Agent banner" . $activeRowCount . "' style='width: 100%;' />";
      echo "</a>";
      echo "</div>";
    }
  }
} else {
  die($connect->error);
}
?>

