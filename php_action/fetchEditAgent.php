<?php
error_reporting(E_ALL); 
ini_set('display_errors', '1'); 
require_once 'core.php';

$edit_idx = (isset($_SESSION['edit_idx']) && $_SESSION['edit_idx'] != '' && is_numeric($_SESSION['edit_idx'])) ? $_SESSION['edit_idx'] : '';

// print_r($_POST)
$name  = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name']: '';
$pw  = (isset($_POST['pw']) && $_POST['pw'] != '') ? $_POST['pw']: '';
$title  = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title']: '';
$content  = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content']: '';
$code  = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code']: '';
$idx = (isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';

if($idx == '') {
  $arr = ['result' => 'empty_idx'];
  exit(json_encode($arr));
}

if($edit_idx != $idx) {
  $arr = ['result' => 'denied'];
  exit(json_encode($arr));
}

$pwd_hash = '';
if($pw != '') {
  $pwd_hash = password_hash($pw, PASSWORD_BCRYPT);
}

// 정규표현식 EXP
preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);
$img_array = [];
// 이미지 태그 src 값 중에서 "img" 문자열 이하 값 알아내기
foreach ($matches[1] as $key => $row) {
  if(substr($row,0,6) == 'upload') {
    $img_array[] = $row;
    continue;
  }
  if(substr($row,0,5) != 'data:') {
    continue;
  }
  [$type, $data] = explode(';', $row);
  [, $ext] = explode('/', $type);
  $ext = ($ext === 'jpeg') ? 'jpg' : $ext;
  [, $base64_decode_data] = explode(',', $data);
  $rs_code = base64_decode($base64_decode_data);
  $filename = date('YmdHis') . '_' . $key . '.' . $ext;
  $targetPath = '../assets/images/agent/' . $filename;
  if (file_put_contents($targetPath,  $rs_code) !== false) {
      // echo 'File saved successfully.';
  } else {
      echo 'Error saving the file. Check file permissions and path.';
      echo 'file_put_contents error: ' . error_get_last()['message'];
  }

  $img_array[] = $targetPath;
  $content = str_replace($row, $targetPath, $content);
}

$imglist = implode('|', $img_array); // 배열을 구분자 기준으로 문자열로 바꿔쥼
if($pwd_hash != '') {
  $sql = 'UPDATE aboard SET name=?, subject=?, content=?, imglist=?, password=? WHERE idx=?';
  $stmt = $connect->prepare($sql);
  if (!$stmt) {
    die($connect->error);
  }
  $stmt->bind_param('sssssi', $name, $title, $content, $imglist, $pwd_hash, $idx);
} else {
  $sql = 'UPDATE aboard SET name=?, subject=?, content=?, imglist=? WHERE idx=?';
  $stmt = $connect->prepare($sql);
  if (!$stmt) {
    die($connect->error);
  }
  $stmt->bind_param('ssssi', $name, $title, $content, $imglist, $idx);
}

$stmt->execute();
$arr = ['result' => 'success'];
$j = json_encode($arr);
die($j);

?>