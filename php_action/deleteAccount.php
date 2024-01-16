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
        echo json_encode(['status' => 'success', 'message' => '您的账户已成功删除。']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '删除账户错误。']);
    }
    $connect->close();
} else {
    echo json_encode(['status' => 'error', 'message' => '未提供用户ID。']);
}
?>
