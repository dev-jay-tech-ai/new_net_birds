<?php
require_once 'core.php';

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);
if (isset($data['idx'])) {
    $arr = $data['idx'];
    list($idx, $active) = explode("_", $arr);
    if($data['board'] == 'agent') {
        $board = 'aboard';
    } elseif($data['board'] == 'private') {
        $board = 'pboard';
    } elseif($data['board'] == 'review') {
        $board = 'rboard';
    }
    $sql = "DELETE FROM $board WHERE idx = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('s', $idx);
    if($stmt->execute()) {
        $stmt->close();
        echo json_encode(['result' => 'success', 'message' => 'Banner deleted successfully.']);
    } else {
        echo json_encode(['result' => 'error', 'message' => 'Error deleting the banner.']);
    }
    $connect->close();
} else {
    echo json_encode(['result' => 'error', 'message' => 'idx not provided.']);
}
?>
