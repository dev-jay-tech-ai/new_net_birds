<?php
require_once 'core.php';

$response = [];
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if(isset($data['idx']) && isset($data['code'])) { 
    $code = $data['code'];  
    include_once 'getBoard.php';
    $sql = "UPDATE $board SET rdate=CURRENT_TIMESTAMP WHERE idx = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $data['idx']);
    if ($stmt->execute()) {
        $stmt->close();
        $response = ['result' => 'success', 'message' => 'The activity updated successfully.' . $data['idx']];
    } else {
        $response = ['result' => 'error', 'message' => 'Error: ' . $stmt->error];
    }
    $connect->close();
} else {
    $response = ['result' => 'error', 'message' => 'idx not provided or empty.'];
}

$j = json_encode($response);
die($j);
?>