<?php
require_once 'core.php';
$response = [];
$extensions = array('jpg', 'png', 'gif', 'jpeg');
$maxFileSize = 40 * 1024 * 1024; // 40 MB
$code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : 'private';
if ($code == 'undefined') $code = 'banner';
$link = $_POST['link'];
$filelist = '';
if (isset($_FILES['file'])) {
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
if (empty($response)) {
  $sql = "INSERT INTO banners (code, img, link, rdate) VALUES (?, ?, ?, NOW())";
  $ip = $_SERVER['REMOTE_ADDR'];
  $stmt = $connect->prepare($sql);
  if (!$stmt) {
    $response = ['result' => 'error', 'message' => $connect->error];
  } else {
    $stmt->bind_param('sss', $code, $filelist, $link);
    if ($stmt->execute()) {
      $response = ['result' => 'success'];
    } else {
      $response = ['result' => 'error', 'message' => $stmt->error];
    }
  }
}

$j = json_encode($response);
die($j);
?>