<?php
require_once 'core.php';

error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);
if (isset($data['user_id'])) {
    $user_id = $data['user_id'];
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('s', $user_id);
    if (!$stmt->execute()) {
        echo json_encode(['result' => 'error', 'message' => 'Error deleting user.']);
        $stmt->close();
        $connect->close();
        exit;
    }
    $stmt->close();
    echo json_encode(['result' => 'success', 'message' => 'User deleted successfully.']);
} else {
    echo json_encode(['result' => 'error', 'message' => 'User not provided.']);
}

$connect->close();
?>

