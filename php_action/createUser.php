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
      $response = ['result' => 'error', 'message' => '文件过大。'];
    } elseif (!in_array($file_ext, $extensions)) {
      $response = ['result' => 'error', 'message' => '无效的文件扩展名。' . $file_ext];
    } else {
      $uniqueFilename = date('YmdHis') . '_' . uniqid() . '.' . $file_ext;
      $destination = $folder . $uniqueFilename;
      if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
        $filelist = $destination;
        $response = ['result' => 'error', 'message' => '文件上传成功。'];
      } else {
        $response = ['result' => 'error', 'message' => '上传失败。'];
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
        $response = ['result' => 'success', 'message' => '添加成功。'];
      } else {
        $response = ['result' => 'error', 'message' => '执行失败: (' . $stmt->error . ') ' . $stmt->error];
      }
  } else {
    $response = ['result' => 'error', 'message' => '准备失败: (' . $connect->error . ') ' . $connect->error];
  }
}

$connect->close();

$j = json_encode($response);
die($j);
?>
