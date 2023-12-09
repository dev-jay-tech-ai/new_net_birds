<?php 
function executeData($connect, $sqlData) {
  $stmtData = $connect->prepare($sqlData);
  if($stmtData) {
    $stmtData->execute();
    $resultData = $stmtData->get_result();
    $rs = [];
    while ($row = $resultData->fetch_assoc()) {
      $rs[] = $row;
    }
    return $rs;
  } else {
    die($connect->error);
  }
}

function executeCnt($connect, $sqlCount) {
  $stmtCount = $connect->prepare($sqlCount);
  if($stmtCount) {
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
    $row = $resultCount->fetch_assoc();
    $total = $row['cnt'];
    return $total;
  } else {
    die($connect->error);
  }
}
?>
