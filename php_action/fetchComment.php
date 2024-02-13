<?php
require_once 'core.php'; 
require_once 'component/auth_session.php'; 

$user_id = $_SESSION['userId'];

// id	user_id	page_id	parent_id	
// title	content	likes	approved	create_at	

$mode = (isset($_POST['mode'   ]) && isset($_POST['mode'   ]) !== '') ? $_POST['mode'   ] : '';
$idx = (isset($_POST['idx'   ]) && isset($_POST['idx'   ]) !== '') ? $_POST['idx'   ] : '';
$title = (isset($_POST['title']) && isset($_POST['title']) !== '') ? $_POST['title'] : '';
$content = (isset($_POST['content']) && isset($_POST['content']) !== '') ? $_POST['content'] : '';

if($content == '') {
  $arr = ["result" => "empty content"];
  die(json_encode($arr));
}


?>