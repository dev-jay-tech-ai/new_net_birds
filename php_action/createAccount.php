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
        $response['messages'] = "Username, email and Password are required.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, user_image, email, active, status, ip, rdate) VALUES (?, ?, '', ?, 2, 2, ?, NOW())";
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $connect->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $username, $hashedPassword, $email, $ip); // Bind the parameters
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['messages'] = "Successfully Added";
            } else {
                $response['messages'] = "Error while adding the member: " . $stmt->error;
            }
        } else {
            $response['messages'] = "Failed to prepare the SQL statement";
        }
    }
    $connect->close();
    echo json_encode($response);
}
?>