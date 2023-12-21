<?php
require_once 'core.php';

$response = [];
$extensions = array('jpg', 'png', 'gif', 'jpeg');
$maxFileSize = 4 * 1024 * 1024; // 4 MB
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$active = isset($_POST['active']) ? $_POST['active'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$credit = isset($_POST['credit']) ? $_POST['credit'] : '';
$filelist = ''; 

if (empty($username) || empty($email) || empty($password)) {
  $response = ['result' => 'error', 'message' => '请输入用户名和密码'];
} else {
  if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {
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
        'jpeg' => 'image/jpeg'
        ],
        true
      );
    }
    $file_size = $_FILES['file']['size'];
    $maxFileSize = 40 * 1024 * 1024;
    if($file_size > $maxFileSize) {
      $response = ['result' => 'error', 'message' => 'File size exceeds the allowed limit.'];
    } elseif (!in_array($file_ext, $extensions)) {
      $response = ['result' => 'error', 'message' => 'Invalid file extension.' . $file_ext];
    } else {
      $uniqueFilename = date('YmdHis') . '_' . uniqid() . '.' . $file_ext;
      $destination = $folder . $uniqueFilename;
      if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
        $filelist = $destination;
        $response = ['result' => 'error', 'message' => 'File uploaded successfully.'];
      } else {
        $response = ['result' => 'error', 'message' => 'Error moving the uploaded file'];
      }
    }
  } 

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $sql = "INSERT INTO users (username, password, user_image, email, active, status, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
  $ip = $_SERVER['REMOTE_ADDR'];
  $stmt = $connect->prepare($sql);
  if ($stmt) {
      $stmt->bind_param("sssssss", $username, $hashedPassword, $filelist, $email, $active, $status, $ip); // Bind the parameters
      if ($stmt->execute()) {
        $response = ['result' => 'success', 'message' => 'Successfully Added'];
      } else {
        $response = ['result' => 'error', 'message' => 'Execute failed: (' . $stmt->error . ') ' . $stmt->error];
      }
  } else {
    $response = ['result' => 'error', 'message' => 'Prepare failed: (' . $connect->error . ') ' . $connect->error];
  }
}

$connect->close();

$j = json_encode($response);
die($j);
?>
