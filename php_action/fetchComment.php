<?php
require_once 'core.php'; 
$post_id = isset($_POST['idx']) ? intval($_POST['idx']) : 0;
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$content = isset($_POST['content']) ? $_POST['content'] : '';
$parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;
if ($content == '') {
  $arr = ['result' => 'empty content'];
  die(json_encode($arr));
}

$sql = '';
if(!isset($parent_id) && $parent_id !== 0) {
 $sql =  "INSERT INTO comments (post_id, user_id, title, content, likes, active, ip, create_at) VALUES (?, ?, '', ?, 0, 1, ?, NOW())";
} else {
  $sql =  "INSERT INTO comments_re (post_id, user_id, parent_id, title, content, likes, active, ip, create_at) VALUES (?, ?, ?, '', ?, 0, 1, ?, NOW())";
}

$ip = $_SERVER['REMOTE_ADDR'];
$stmt = $connect->prepare($sql);

if ($stmt) {
  if(!isset($parent_id)) {
    $stmt->bind_param('ssss', $post_id, $user_id, $content, $ip);
   } else {
    $stmt->bind_param('sssss', $post_id, $user_id, $parent_id, $content, $ip);
   }

}

if(!$stmt->execute()) {
  $response = ['result' => 'error', 'message' => '失败: (' . $stmt-> error . ') ' . $stmt-> error];
} else {
  $response = ['result' => 'success', 'message' => '成功'];
}
$stmt->close();

echo json_encode($response);
?>
