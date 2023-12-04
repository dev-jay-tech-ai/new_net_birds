<?php
require_once 'core.php';
require 'vendor/autoload.php';

$response = [];
$extensions = array('jpg', 'png', 'gif', 'jpeg', 'mov', 'mp4');
$maxFileSize = 40 * 1024 * 1024; // 40 MB 이미지 용량
$name = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '';
$title = (isset($_POST['subject']) && $_POST['subject'] != '') ? $_POST['subject'] : '';
$content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
$code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : 'private';
$location = (isset($_POST['location']) && $_POST['location'] != '') ? $_POST['location'] : 0;
$userId = (isset($_POST['user_id']) && $_POST['user_id'] != '') ? $_POST['user_id'] : NULL;
if ($code == 'undefined') $code = 'private';

$filelist = array();
$videoProcessed = false; // Flag to track if video is processed

$ffmpegPath = '/Applications/XAMPP/xamppfiles/htdocs/newnetbirds/ffmpeg';

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
    if (in_array($file_ext, ['mov', 'mp4'])) {
      $bitrate = '2500k';
      $outputDirectory = "../assets/videos/test/";
      $video = escapeshellarg($temp_folder);
      $bitrate = escapeshellarg($bitrate);
      $outputFile = $outputDirectory . date('YmdHis') . '_' . uniqid() . '.' . $file_ext;
      $outputFile_esc = escapeshellarg($outputFile);

      // $command = "$ffmpegPath -i $video -b:v $bitrate -bufsize $bitrate $outputFile_esc 2>&1";
      // exec($command, $output, $returnCode);
      $percentage = 10%;
      $format = new FFMpeg\Format\Video\X264();
      $format->on('progress', function ($video, $format, $percentage) {
          echo "$percentage % transcoded";
      });
      $format
          ->setKiloBitrate(1000)
          ->setAudioChannels(2)
          ->setAudioKiloBitrate(256);
      
      $video->save($format, $outputFile_esc);

      if($returnCode === 0) {
        $filelist[] = "<p class='text-center'><video class='video_size' controls src='" . $outputFile . "' loading='lazy'></video></p>";
      } else {
        $response = ['result' => 'error', 'message' => "Error for file $key: " . implode("<br>", $output) . "<br>"];
      }
    } else {
      $uniqueFilename = date('YmdHis') . '_' . $key . '.' . $file_ext;
      if (move_uploaded_file($temp_folder, $folder . $uniqueFilename)) {
        $filelist[] = "<p class='text-center'><img src='" . $folder . $uniqueFilename . "' style='width: 100%; height: auto;' loading='lazy' /></p>";
      } else {
        $response = ['result' => 'error', 'message' => 'Error moving file ' . $file . '. Error code: ' . $temp_folder . $folder];
        break;
      }
    }
  }
  if (empty($response)) {
    $contentWithPaths = '<pre>' . $content . '</pre>' . implode('', $filelist);
    $sql = "INSERT INTO pboard (code, location, user_id, name, subject, content, imglist, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $connect->prepare($sql);
    if (!$stmt) {
      $response = ['result' => 'error', 'message' => $connect->error];
    } else {
      $imglist = '';
      $stmt->bind_param('ssisssss', $code, $location, $userId, $name, $title, $contentWithPaths, $imglist, $ip);
      if ($stmt->execute()) {
        $response = ['result' => 'success'];
      } else {
        $response = ['result' => 'error', 'message' => $stmt->error];
      }
    }
  }
}

$j = json_encode($response);
die($j);
?>
