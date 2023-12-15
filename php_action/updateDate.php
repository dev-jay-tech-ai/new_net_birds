<?php
require_once 'core.php';
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if(isset($data['idx'])) {
    if ($code == 'agent') {
		$board = 'aboard';
	} elseif ($code == 'private') {
		$board = 'pboard';
	} elseif ($code == 'review') {
		$board = 'rboard';
	} elseif ($code == 'jobs') {
		$board = 'jboard';
	} elseif ($code == 'property') {
		$board = 'prboard';
	}

    $sql = "UPDATE $board SET rdate=NOW() WHERE idx = ?";
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