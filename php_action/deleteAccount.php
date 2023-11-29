<?php
require_once 'db_connect.php';

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $user_id);
    if ($stmt->execute()) {
        $stmt->close();
        session_start();
        session_unset();
        session_destroy();
        session_write_close();
        echo json_encode(['status' => 'success', 'message' => 'Your account deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting your account.']);
    }
    $connect->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'User ID not provided.']);
}
?>
