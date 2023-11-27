<?php
// Set the folder to save files
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $extensions = array('jpg', 'png', 'gif', 'jpeg', 'mov', 'mp4');
  $maxFileSize = 40 * 1024 * 1024; // 40 MB

  // Retrieve other form data
  $name = (isset($_POST['username']) && $_POST['username'] != '') ? $_POST['username'] : '';
  $pw = (isset($_POST['password']) && $_POST['password'] != '') ? $_POST['password'] : '';
  $title = (isset($_POST['subject']) && $_POST['subject'] != '') ? $_POST['subject'] : '';
  $content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
  $code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : 'private';
  if ($code == 'undefined') $code = 'private';
  // 비밀번호 단방향 암호화
  $pwd_hash = password_hash($pw, PASSWORD_BCRYPT);
  // Validate form fields
  if (empty($title) || empty($content)) {
    echo 'Error: Subject and Content cannot be empty.';
      exit;
  }
  $filelist = array();
  if (isset($_FILES['files'])) {
    $folder = '../assets/images/test/';
    $names = $_FILES['files']['name'];
    $tmp_names = $_FILES['files']['tmp_name'];
    $upload_data = array_combine($tmp_names, $names);
    foreach ($upload_data as $temp_folder => $file) {
      $file_ext = pathinfo($file, PATHINFO_EXTENSION);
      $file_size = $_FILES['files']['size'][$temp_folder];

      // Check file size
      if ($file_size > $maxFileSize) {
          echo "Error: File size exceeds the allowed limit.";
          exit;
      }
      if (!in_array($file_ext, $extensions)) {
          echo "Error: Invalid file extension.";
          exit;
      }
      $uniqueFilename = date('YmdHis') . '_' . generateRandomString() . '.' . $file_ext;
      if (move_uploaded_file($temp_folder, $folder . $uniqueFilename)) {
          if ($file_ext === 'mov' || $file_ext === 'mp4') {
            $filelist[] = "<p class='text-center'><video class='video_size' controls src='" . $folder . $uniqueFilename . "' loading='lazy'></video></p>";
          } else {
            $filelist[] = "<p class='text-center'><img src='" . $folder . $uniqueFilename . "' style='width: 100%; height: auto;' loading='lazy' /></p>";
          }
      } else {
          echo 'Error moving file ' . $file . '. Error code: ' . $_FILES['files']['error'];
          exit;
      }
  }
  } else {
    echo "Error: No file uploaded.";
    exit;
  }
  $contentWithPaths = '<pre>' . $content . '</pre>' . '<p>' . implode('</p><p>', $filelist) . '</p>';
  $sql = "INSERT INTO pboard (code, name, subject, password, content, imglist, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
  $ip = $_SERVER['REMOTE_ADDR'];
  $stmt = $connect->prepare($sql);
  if (!$stmt) {
    die($connect->error);
  }
  $imglist = '';
  $stmt->bind_param('sssssss', $code, $name, $title, $pwd_hash, $contentWithPaths, $imglist, $ip);
  $stmt->execute();
  $arr = ['result' => 'success'];
  $j = json_encode($arr);
  die($j);
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
