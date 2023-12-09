<?php
require_once 'core.php';
$response = [];
$extensions = array('jpg', 'png', 'gif', 'jpeg', 'mov', 'mp4');
$maxFileSize = 40 * 1024 * 1024; // 40 MB
$idx = (isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';
$name = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '';
$title = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title'] : '';
$content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
$code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : '';
if($code !== 'agent') {
  $location = (isset($_POST['location']) && $_POST['location'] != '') ? $_POST['location'] : 0;
}
if($code == 'review') {
  $rate  = (isset($_POST['rate']) && $_POST['rate'] != '') ? $_POST['rate']: 0;
}
if($idx == '') {
  $response = ['result' => 'empty_idx'];
  exit(json_encode($response));
}
$filelist = array();
if (isset($_FILES['files'])) {
  $folder = '../assets/images/test/';
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
    if ($file_size > $maxFileSize) {
        $response = ['result' => 'error', 'message' => 'File size exceeds the allowed limit.'];
        break;
    }
    if (!in_array($file_ext, $extensions)) {
        $response = ['result' => 'error', 'message' => 'Invalid file extension.' . $file_ext];
        break;
    }
    $uniqueFilename = date('YmdHis') . '_' . $key . '.' . $file_ext;
    if (move_uploaded_file($temp_folder, $folder . $uniqueFilename)) {
      if ($file_ext === 'mov' || $file_ext === 'mp4') {
        $filelist[] = "<p class='text-center'><video class='video_size' controls src='" . $folder . $uniqueFilename . "' loading='lazy'></video></p>";
      } else {
        $filelist[] = "<p class='text-center'><img src='" . $folder . $uniqueFilename . "' style='width: 100%; height: auto;' loading='lazy' /></p>";
      }
    } else {
      $response = ['result' => 'error', 'message' => 'Error moving file ' . $file . '. Error code: ' . $temp_folder . $folder];
      break;
    }
  }
}
if (empty($response)) {
  $contentWithPaths = $content . implode('', $filelist);
  $imglist = '';
  if($code == 'agent') {
    $board = 'aboard';
  } elseif ($code == 'private') {
    $board = 'pboard';
  } elseif ($code == 'review') {
    $board = 'rboard';
  }
  if($code == 'agent') {
    $sql = "UPDATE $board SET name=?, subject=?, content=?, imglist=? WHERE idx=?";
  } elseif($code == 'review') {
    $sql = "UPDATE $board SET name=?, subject=?, location=?, content=?, imglist=?, rate=? WHERE idx=?";
  } else {
    $sql = "UPDATE $board SET name=?, subject=?, location=?, content=?, imglist=? WHERE idx=?";
  }
  $stmt = $connect->prepare($sql);
  if (!$stmt) {
    $response = ['result' => 'error', 'message' => 'Prepare failed: (' . $connect->errno . ') ' . $connect->error];
  } else {
    $imglist = '';
    if($code == 'agent') {
      $stmt->bind_param('ssssi', $name, $title, $contentWithPaths, $imglist, $idx);
    } elseif($code == 'review') {
      $stmt->bind_param('ssissii', $name, $title, $location, $contentWithPaths, $imglist, $rate, $idx);
    } else {
      $stmt->bind_param('ssissii', $name, $title, $location, $contentWithPaths, $imglist, $idx);
    }
    if(!$stmt->execute()) {
      $response = ['result' => 'error', 'message' => 'Execute failed: (' . $stmt->errno . ') ' . $stmt->error];
    } else {
      $response = ['result' => 'success', 'message' => 'Update successful'];
    }
    $stmt->close();
  }  
}

$j = json_encode($response);
die($j);
?>