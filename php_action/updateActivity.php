<?php
require_once 'core.php';
$response = [];
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if(isset($data['idx'])) {
    $arr = $data['idx'];
    list($idx, $active) = explode("_", $arr);
    $code = $data['board'];  
    include_once 'getBoard.php';
    if($active == 1) {
        $sql = "UPDATE $board SET active=2 WHERE idx = ?";
    } else {
        $sql = "UPDATE $board SET active=1 WHERE idx = ?";
    }
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $idx);
    if ($stmt->execute()) {
        $stmt->close();
        $response = ['result' => 'success', 'message' => 'The activitiy updated successfully.'];
    } else {
        $response = ['result' => 'error', 'message' => 'Error'];
    }
    $connect->close();
} else {
    $response = ['result' => 'error', 'message' => 'idx not provided or empty.'];
}

$j = json_encode($response);
die($j);
?>
