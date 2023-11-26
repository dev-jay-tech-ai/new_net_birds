<?php
// Set the folder to save files
error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

require_once 'core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $extensions = array('jpg', 'png', 'gif', 'jpeg', 'mov', 'mp4');
    // Retrieve other form data
    $name = (isset($_POST['username']) && $_POST['username'] != '') ? $_POST['username'] : '';
    $pw = (isset($_POST['password']) && $_POST['password'] != '') ? $_POST['password'] : '';
    $title = (isset($_POST['subject']) && $_POST['subject'] != '') ? $_POST['subject'] : '';
    $content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
    $code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : '';
    
    if ($code == 'undefined') $code = 'private';
    // 비밀번호 단방향 암호화
    $pwd_hash = password_hash($pw, PASSWORD_BCRYPT);

    // Validate form fields
    if (empty($title) || empty($content)) {
        echo 'Error: Subject and Content cannot be empty.';
        exit;
    }

    // Initialize the list of image and video paths
    $filelist = array();
    // Process uploaded files
    if (isset($_FILES['files'])) {
        $folder = '../assets/images/test/';
        $names = $_FILES['files']['name'];
        $tmp_names = $_FILES['files']['tmp_name'];
        $upload_data = array_combine($tmp_names, $names);
        foreach ($upload_data as $temp_folder => $file) {
            $file_ext = pathinfo($file, PATHINFO_EXTENSION);
            // Move the uploaded file to the target path
            if (move_uploaded_file($temp_folder, $folder . $file)) {
              if (in_array($file_ext, $extensions)) {
                if ($file_ext === 'mov' || $file_ext === 'mp4') {
                  $filelist[] = '<p><video controls width="300" src="' . $folder . $file . '"></video></p>';
                } else {
                  $filelist[] = '<img src="' . $folder . $file . '" style="width: 100%;" />';
                }
              } 
        
            } else {
                echo 'Error moving file ' . $file . '. Error code: ' . $_FILES['files']['error'];
            }
        }
    } else {
        echo json_encode(['error' => 'No file uploaded.']);
    }
    // Combine the image and video paths into the content
    $contentWithPaths = $content . '<p>' . implode('</p><p>', $filelist) . '</p>';
    
    echo $contentWithPaths;
    // Save data to the database
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

    // Redirect to another page upon successful insertion
    // header("Location: /path/to/your/success/page.php");
    // exit;
  }

?>