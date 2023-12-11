<?php
require_once 'core.php';
$response = [];
$extensions = array('jpg', 'png', 'gif', 'jpeg', 'mov', 'mp4');
$maxFileSize = 40 * 1024 * 1024; // 40 MB
$name  = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name']: '';
$title  = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title']: '';
$content  = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content']: '';
$code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : '';
$userId = (isset($_POST['user_id']) && $_POST['user_id'] != '') ? $_POST['user_id'] : NULL;
$filelist = array();
if($code !== 'agent') {
  $location = (isset($_POST['location']) && $_POST['location'] != '') ? $_POST['location'] : 0;
}
if($code == 'review') {
  $rate  = (isset($_POST['rate']) && $_POST['rate'] != '') ? $_POST['rate']: 0;
}
if(isset($_FILES['files'])) {
    $folder = "../assets/images/$code/";
    foreach ($_FILES['files']['tmp_name'] as $key => $temp_folder) {
      $file = $_FILES['files']['name'][$key];
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
      $file_size = $_FILES['files']['size'][$key];
      if($file_size > $maxFileSize) {
        $response = ['result' => 'error', 'message' => 'File size exceeds the allowed limit.'];
        break;
      }
      if(!in_array($file_ext, $extensions)) {
        $response = ['result' => 'error', 'message' => 'Invalid file extension.' . $file_ext ];
        break;
      }
      $uniqueFilename = date('YmdHis') . '_' . $key . '.' . $file_ext;
      if(move_uploaded_file($temp_folder, $folder . $uniqueFilename)) {
        if($file_ext === 'mov' || $file_ext === 'mp4') {
            $filelist[] = "<p class='text-center'><video class='video_size' controls src='" . $folder . $uniqueFilename . "' loading='lazy'></video></p>";
        } else {
            $filelist[] = "<p class='text-center'><img src='" . $folder . $uniqueFilename . "' style='width: 100%; height: auto;' loading='lazy' /></p>";
        }
      } else {
        $response = ['result' => 'error', 'message' => 'Error moving file ' . $file . '. Error code: ' . $temp_folder . $folder ];
        break;
      }
    }
} 
if (empty($response)) {
  $contentWithPaths = '<pre>' . $content . '</pre>' . implode('', $filelist);
  include 'getBoard.php';
  if($code == 'agent') {
    $sql = "INSERT INTO $board (code, user_id, name, subject, content, imglist, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
  } elseif ($code == 'review') {
    $sql = "INSERT INTO $board (code, location, user_id, name, subject, content, imglist, rate, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
  } else {
    $sql = "INSERT INTO $board (code, location, user_id, name, subject, content, imglist, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
  }
  $ip = $_SERVER['REMOTE_ADDR'];
  $stmt = $connect->prepare($sql);

  if (!$stmt) {
    $response = ['result' => 'error', 'message' => $connect->error];
  } else {
    $imglist = '';
    if ($code == 'agent') {
      $stmt->bind_param('sisssss', $code, $userId, $name, $title, $contentWithPaths, $imglist, $ip);
    } elseif ($code == 'review') {
      $stmt->bind_param('ssissssss', $code, $location, $userId, $name, $title, $contentWithPaths, $imglist, $rate, $ip);
    } else {
      $stmt->bind_param('ssisssss', $code, $location, $userId, $name, $title, $contentWithPaths, $imglist, $ip);
    }
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