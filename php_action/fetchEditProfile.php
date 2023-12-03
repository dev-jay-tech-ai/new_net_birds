<?php
require_once 'core.php';

$valid = ['success' => false, 'messages' => []];
if ($_POST) {
    $extensions = array('jpg', 'png', 'gif', 'jpeg');
    $maxFileSize = 4 * 1024 * 1024; // 4 MB
    $user_id = $_SESSION['userId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $filelist = ''; 
    if(isset($_FILES['file'])) {
        $folder = '../assets/images/test/';
        $file = $_FILES['file']['name'];
        $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if($file_ext == '') {
            $file_tmp = $_FILES['files']['tmp_name'][$key];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_mime_type = finfo_file($finfo, $file_tmp);
            finfo_close($finfo);
            $file_ext = array_search(
                $file_mime_type,
                [
                'jpg' => 'image/jpeg',         
                'png' => 'image/png',
                'gif' => 'image/gif',
                'jpeg' => 'image/jpeg',
                'mov' => 'video/quicktime',
                'mp4' => 'video/mp4',
                ],
                true
            );
        }
        $file_size = $_FILES['file']['size'];
        $maxFileSize = 40 * 1024 * 1024;
        if($file_size > $maxFileSize) {
            $valid['messages'] = 'File size exceeds the allowed limit.';
        } elseif (!in_array($file_ext, $extensions)) {
            $valid['messages'] = 'Invalid file extension.' . $file_ext;
        } else {
            $uniqueFilename = date('YmdHis') . '_' . uniqid() . '.' . $file_ext;
            $destination = $folder . $uniqueFilename;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
                $filelist = $destination;
                $valid['messages'] = 'File uploaded successfully.';
            } else {
                $valid['messages'] = 'Error moving the uploaded file';
            }
        }
    } 
    if (empty($username) || empty($email) || empty($password)) {
        $valid['messages'] = "Username, email and Password are required.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'UPDATE users SET username=?, password=?, email=?, user_image=? WHERE user_id=?';
        $stmt = $connect->prepare($sql);
        if($stmt) {
            $stmt->bind_param("ssssi", $username, $hashedPassword, $email, $filelist, $user_id);
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