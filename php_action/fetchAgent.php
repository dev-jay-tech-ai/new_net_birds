<?php 
error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

require_once 'core.php';

// print_r($_POST)
$name  = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name']: '';
$pw  = (isset($_POST['pw']) && $_POST['pw'] != '') ? $_POST['pw']: '';
$title  = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title']: '';
$content  = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content']: '';
$code  = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code']: '';

if($code == 'undefined') $code = 'Agent';

// 비밀번호 단방향 암호화
$pwd_hash = password_hash($pw, PASSWORD_BCRYPT);

// 정규표현식 EXP
preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);
$img_array = [];

foreach ($matches[1] as $key => $val) {
    [$type, $data] = explode(';', $val);
    [, $ext] = explode('/', $type);
    $ext = ($ext === 'jpeg') ? 'jpg' : $ext;
    $filename = date('YmdHis') . '_' . $key . '.' . $ext;
    [, $base64_decode_data] = explode(',', $data);
    $rs_code = base64_decode($base64_decode_data);
    // Check the file size before saving
    $maxFileSize = 1024 * 1024; // 1 MB (adjust this value as needed)
    if (strlen($rs_code) <= $maxFileSize) {
        $targetPath = '../assets/images/private/' . $filename;
        if (file_put_contents($targetPath, $rs_code) !== false) {
            $img_array[] = $targetPath;
            $content = str_replace($val, $targetPath, $content);
        } else {
            echo 'Error saving the file. Check file permissions and path.';
            echo 'file_put_contents error: ' . error_get_last()['message'];
        }
    } else {
        echo 'File size exceeds the limit.';
        // You can handle this case as needed, e.g., show an error message to the user.
    }
}

$imglist = implode('|', $img_array);
$sql = "INSERT INTO aboard (code, name, subject, password, content, imglist, ip, rdate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$ip = $_SERVER['REMOTE_ADDR'];
$stmt = $connect->prepare($sql);
if (!$stmt) {
    die($connect->error);
}
$stmt->bind_param('sssssss', $code, $name, $title, $pwd_hash, $content, $imglist, $ip);
$stmt->execute();
$arr = ['result' => 'success'];
$j = json_encode($arr);
die($j);

?>