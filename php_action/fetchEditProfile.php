<?php
require_once 'core.php';

$valid = ['success' => false, 'messages' => []];
if ($_POST) {
    $user_id = $_SESSION['userId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($username) || empty($email) || empty($password)) {
        $valid['messages'] = "Username, email and Password are required.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'UPDATE users SET username=?, password=?, email=? WHERE user_id=?';
        $stmt = $connect->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssi", $username, $hashedPassword, $email, $user_id);
            if ($stmt->execute()) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Edited";
            } else {
                $valid['messages'] = "Error while editing: " . $stmt->error;
            }
        } else {
            $valid['messages'] = "Failed to prepare the SQL statement";
        }
    }

    $connect->close();
    echo json_encode($valid);
}
?>