<?php
require_once 'core.php';
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if(isset($data['idx'])) {
    $arr = $data['idx'];
    list($idx, $active) = explode("_", $arr);
    if($data['board'] == 'agent') {
        $board = 'aboard';
    } elseif($data['board'] == 'private') {
        $board = 'pboard';
    } elseif($data['board'] == 'review') {
        $board = 'rboard';
    }
    if($active == 1) {
        $sql = "UPDATE $board SET active=2 WHERE idx = ?";
    } else {
        $sql = "UPDATE $board SET active=1 WHERE idx = ?";
    }
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $idx);
    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(['result' => 'success', 'message' => 'The activitiy updated successfully.']);
    } else {
        echo json_encode(['result' => 'error', 'message' => 'Error']);
    }
    $connect->close();
} else {
    echo json_encode(['result' => 'error', 'message' => 'idx not provided or empty.']);
}
?>
