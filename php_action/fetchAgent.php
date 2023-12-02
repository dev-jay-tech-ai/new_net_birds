<?php 
require_once 'core.php';
$response = [];
$extensions = array('jpg', 'png', 'gif', 'jpeg', 'mov', 'mp4');
$maxFileSize = 40 * 1024 * 1024; // 40 MB
$name = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '';
$title = (isset($_POST['subject']) && $_POST['subject'] != '') ? $_POST['subject'] : '';
$content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
$code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : 'private';
$userId = (isset($_POST['user_id']) && $_POST['user_id'] != '') ? $_POST['user_id'] : NULL;
if ($code == 'undefined') $code = 'agent';
$filelist = array();
if (isset($_FILES['files'])) {
    $folder = '../assets/images/test/';
    foreach ($_FILES['files']['tmp_name'] as $key => $temp_folder) {
      $file = $_FILES['files']['name'][$key];
      $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      $file_size = $_FILES['files']['size'][$key];
      if ($file_size > $maxFileSize) {
        $response = ['result' => 'error', 'message' => 'File size exceeds the allowed limit.'];
        break;
      }
      if(!in_array($file_ext, $extensions)) {
        $response = ['result' => 'error', 'message' => 'Invalid file extension.' . $file_ext ];
        break;
      }
      $uniqueFilename = date('YmdHis') . '_' . $key . '.' . $file_ext;
      if(move_uploaded_file($temp_folder, $folder . $uniqueFilename)) {
          if ($file_ext === 'mov' || $file_ext === 'mp4') {
              $filelist[] = "<p class='text-center'><video class='video_size' controls src='" . $folder . $uniqueFilename . "' loading='lazy'></video></p>";
          } else {
              $filelist[] = "<p class='text-center'><img src='" . $folder . $uniqueFilename . "' style='width: 100%; height: auto;' loading='lazy' /></p>";
          }
      } else {
       //  $response = ['result' => 'error', 'message' => 'Error moving file ' . $file . '. Error code: ' . $_FILES['files']['error'][$key]];
        $response = ['result' => 'error', 'message' => 'Error moving file ' . $file . '. Error code: ' . $temp_folder . $folder ];
          break;
      }
    }
} 
if (empty($response)) {
  $contentWithPaths = '<pre>' . $content . '</pre>' . implode('', $filelist);
  $sql = "INSERT INTO aboard (code, user_id, name, subject, content, imglist, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
  $ip = $_SERVER['REMOTE_ADDR'];
  $stmt = $connect->prepare($sql);
  if (!$stmt) {
    $response = ['result' => 'error', 'message' => $connect->error];
  } else {
    $imglist = '';
    $stmt->bind_param('sisssss', $code, $userId, $name, $title, $contentWithPaths, $imglist, $ip);
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