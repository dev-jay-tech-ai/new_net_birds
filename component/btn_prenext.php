<?php
function getPreviousPost($board, $connect, $idx, $code) {
  $queryString = isset($code) ? "&code={$code}" : '';
  $stmt = $connect->prepare("SELECT * FROM $board WHERE idx > ? AND active = 1 ORDER BY idx ASC LIMIT 1");
  $stmt->bind_param('i', $idx);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return "<a class='btn btn-w' href='?idx={$row['idx']}{$queryString}'><i class='fa-solid fa-chevron-left'></i></a>";
  }
  return '';
}

function getNextPost($board, $connect, $idx, $code) {
  $queryString = isset($code) ? "&code={$code}" : '';
  $stmt = $connect->prepare("SELECT * FROM $board WHERE idx < ? AND active = 1 ORDER BY idx DESC LIMIT 1");
  $stmt->bind_param('i', $idx);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return "<a class='btn btn-w' href='?idx={$row['idx']}{$queryString}'><i class='fa-solid fa-chevron-right'></i></a>";
  }
  return '';
}

$idx = $row['idx'];
$previousPostButton = getPreviousPost($board, $connect, $idx, $code);
$nextPostButton = getNextPost($board, $connect, $idx, $code);

echo $previousPostButton;
echo $nextPostButton;
?>
