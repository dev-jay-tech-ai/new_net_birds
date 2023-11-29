<?php
require_once 'core.php';

$valid = ['success' => false, 'messages' => []];

if ($_POST) {
    // 프로파일 이미지 처리 필요
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the form data - ensure username and password are not empty
    if (empty($username) || empty($email) || empty($password)) {
        $valid['messages'] = "Username, email and Password are required.";
    } else {
        // Hash the password (for better security, do not store passwords in plain text)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $sql = "INSERT INTO users (username, password, user_image, email, active, status) VALUES (?, ?, '', ?, 2, 2)";
        $stmt = $connect->prepare($sql);

        if ($stmt) {
            // You need to bind three parameters, not two
            $stmt->bind_param("sss", $username, $hashedPassword, $email); // Bind the parameters

            if ($stmt->execute()) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Added";
            } else {
                $valid['messages'] = "Error while adding the member: " . $stmt->error;
            }
        } else {
            $valid['messages'] = "Failed to prepare the SQL statement";
        }
    }

    $connect->close();

    echo json_encode($valid);
}
?>