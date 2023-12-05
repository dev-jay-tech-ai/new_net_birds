<?php
require_once 'core.php';

$response = ['success' => false, 'messages' => []];
if($_POST) {
  $extensions = array('jpg', 'png', 'gif', 'jpeg');
  $maxFileSize = 4 * 1024 * 1024; // 4 MB
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $filelist = ''; 
  if(isset($_FILES['file'])) {
    $folder = '../assets/images/profile/';
    $file = $_FILES['file']['name'];
    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if($file_ext == '') {
      $file_tmp = $_FILES['file']['tmp_name'];
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
        $response['messages'] = 'File size exceeds the allowed limit.';
    } elseif (!in_array($file_ext, $extensions)) {
        $response['messages'] = 'Invalid file extension.' . $file_ext;
    } else {
      $uniqueFilename = date('YmdHis') . '_' . uniqid() . '.' . $file_ext;
      $destination = $folder . $uniqueFilename;
      if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
        $filelist = $destination;
        $response['messages'] = 'File uploaded successfully.';
      } else {
        $response['messages'] = 'Error moving the uploaded file';
      }
    }
  } 
  if (empty($username) || empty($email) || empty($password)) {
    $response['messages'] = "Username, email and Password are required.";
  } else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, user_image, email, active, status, ip, rdate) VALUES (?, ?, ?, ?, 2, 2, ?, NOW())";
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $connect->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssss", $username, $hashedPassword, $filelist, $email, $ip); // Bind the parameters
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
  json_encode($response);
  echo "<script>window.location.href='/users.php';</script>";
  exit();
}
?>
