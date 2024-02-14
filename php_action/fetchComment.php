<?php
require_once 'core.php'; 
require_once 'component/auth_session.php'; 

$user_id = $_SESSION['userId'];

// id	user_id	page_id	parent_id	
// title content likes	approved ip	create_at	
$page_id = (isset($_POST['page_id']) && isset($_POST['page_id']) !== '') ? $_POST['page_id'] : '';
$parent_id = (isset($_POST['parent_id']) && isset($_POST['parent_id']) !== '') ? $_POST['parent_id'] : '';
$title = (isset($_POST['title']) && isset($_POST['title']) !== '') ? $_POST['title'] : '';
$content = (isset($_POST['content']) && isset($_POST['content']) !== '') ? $_POST['content'] : '';

if($content == '') {
  $arr = ['result' => 'empty content'];
  die(json_encode($arr));
}

$sql = "INSET INTO comment (title, content) VALUES (
)";
$ip = $_SERVER['REMOTE_ADDR'];
$stmt = $connect->prepare($sql);
$stmt->bind_param('', );




?>