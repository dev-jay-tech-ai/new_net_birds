<?php
require_once 'core.php';
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if (isset($data['idx'])) {
    $arr = $data['idx'];
    list($idx, $active) = explode("_", $arr);
    if($active == 1) {
        $sql = "UPDATE banners SET active=2 WHERE idx = ?";
    } else {
        $sql = "UPDATE banners SET active=1 WHERE idx = ?";
    }
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $idx);
    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(['result' => 'success', 'message' => 'Your banner status updated successfully.']);
    } else {
        echo json_encode(['result' => 'error', 'message' => 'Error']);
    }
    $connect->close();
} else {
    echo json_encode(['result' => 'error', 'message' => 'idx not provided or empty.']);
}
?>
