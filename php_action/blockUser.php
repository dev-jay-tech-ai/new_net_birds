<?php
require_once 'core.php';
error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);
if (isset($data['user_id'])) {
    $user_id = $data['user_id'];
    list($id, $status) = explode("_", $user_id);
    if($status == 0) {
        $sql = "UPDATE users SET status=2 WHERE user_id = ?";
    } else {
        $sql = "UPDATE users SET status=0 WHERE user_id = ?";
    }
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('s', $id);
    if (!$stmt->execute()) {
        echo json_encode(['result' => 'error', 'message' => 'Error updating user.']);
        $stmt->close();
        $connect->close();
        exit;
    }
    $stmt->close();
    echo json_encode(['result' => 'success', 'message' => 'User updated successfully.']);
} else {
    echo json_encode(['result' => 'error', 'message' => 'User not provided.']);
}

$connect->close();
?>

