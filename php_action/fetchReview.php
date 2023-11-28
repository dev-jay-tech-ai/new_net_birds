<?php
require_once 'core.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

$name  = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name']: '';
$pw  = (isset($_POST['pw']) && $_POST['pw'] != '') ? $_POST['pw']: '';
$title  = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title']: '';
$content  = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content']: '';
$rate  = (isset($_POST['rate']) && $_POST['rate'] != '') ? $_POST['rate']: 0;

// 비밀번호 단방향 암호화
$pwd_hash = password_hash($pw, PASSWORD_BCRYPT);

// 정규표현식 EXP
preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);
$img_array = [];
// 이미지 태그 src 값 중에서 "img" 문자열 이하 값 알아내기
foreach ($matches[1] as $key => $val) {
  [$type, $data] = explode(';', $val);
  [, $ext] = explode('/', $type);
  $ext = ($ext === 'jpeg') ? 'jpg' : $ext;
  $filename = date('YmdHis') . '_' . $key . '.' . $ext;
  [, $base64_decode_data] = explode(',', $data);
  $rs_code = base64_decode($base64_decode_data);
  $targetPath = '../assets/images/review/' . $filename;
  if (file_put_contents($targetPath, $rs_code) !== false) {
    // echo 'File saved successfully.';
  } else {
    //  echo 'Error saving the file. Check file permissions and path.';
    //  echo 'file_put_contents error: ' . error_get_last()['message'];
  }

  $img_array[] = $targetPath;
  $content = str_replace($val, $targetPath, $content);
}

$imglist = implode('|', $img_array);
$sql = "INSERT INTO rboard (name, subject, password, content, imglist, rate, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$ip = $_SERVER['REMOTE_ADDR'];
$stmt = $connect->prepare($sql);
if (!$stmt) {
  die($connect->error);
}
$stmt->bind_param('sssssss', $name, $title, $pwd_hash, $content, $imglist, $rate, $ip);
$stmt->execute();
$arr = ['result' => 'success'];
$j = json_encode($arr);
die($j);

?>