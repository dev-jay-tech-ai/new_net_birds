<?php
require_once 'core.php';
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if(isset($data['idx'])) {
    $arr = $data['idx'];
    list($idx, $is_pinned) = explode("_", $arr);
    if($data['board'] == 'agent') {
        $board = 'aboard';
    } elseif($data['board'] == 'private') {
        $board = 'pboard';
    } elseif($data['board'] == 'review') {
        $board = 'rboard';
    }
    if($is_pinned == 0) {
        $sql = "UPDATE $board SET is_pinned=1 WHERE idx = ?";
    } else {
        $sql = "UPDATE $board SET is_pinned=0 WHERE idx = ?";
    }
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $idx);
    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(['result' => 'success', 'message' => 'The pin status updated successfully.']);
    } else {
        echo json_encode(['result' => 'error', 'message' => 'Error']);
    }
    $connect->close();
} else {
    echo json_encode(['result' => 'error', 'message' => 'idx not provided or empty.']);
}
?>
