<?php
require_once 'core.php';
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);
if (isset($data['items'])) {
    $idxArray = $data['items'];
    if (isset($data['board'])) {
        if ($data['board'] == 'agent') {
            $board = 'aboard';
        } elseif ($data['board'] == 'private') {
            $board = 'pboard';
        } elseif ($data['board'] == 'review') {
            $board = 'rboard';
        }
        $sql = "DELETE FROM $board WHERE idx = ?";
        $stmt = $connect->prepare($sql);
        foreach ($idxArray as $idx) {
            $stmt->bind_param('s', $idx);
            if (!$stmt->execute()) {
                echo json_encode(['result' => 'error', 'message' => 'Error deleting postings.']);
                $stmt->close();
                $connect->close();
                exit;
            }
        }
        $stmt->close();
        echo json_encode(['result' => 'success', 'message' => 'Items deleted successfully.']);
    } else {
        echo json_encode(['result' => 'error', 'message' => 'Board not provided.']);
    }
} else {
    echo json_encode(['result' => 'error', 'message' => 'Items not provided.']);
}

$connect->close();
?>
