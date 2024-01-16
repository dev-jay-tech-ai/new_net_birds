<?php

error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

require_once 'core.php';

$response = ['success' => false, 'messages' => []];
if ($_POST) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($username) || empty($email) || empty($password)) {
        $response['messages'] = "用户名、电子邮件和密码是必填项。";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, user_image, email, active, status, ip, rdate) VALUES (?, ?, '', ?, 2, 2, ?, NOW())";
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $connect->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $username, $hashedPassword, $email, $ip); // Bind the parameters
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['messages'] = "添加成功";
            } else {
                $response['messages'] = "添加会员时出错: " . $stmt->error;
            }
        } else {
            // $response['messages'] = "Failed to prepare the SQL statement";
            $response['messages'] = "添加会员时出错";
        }
    }
    $connect->close();
    echo json_encode($response);
}
?>