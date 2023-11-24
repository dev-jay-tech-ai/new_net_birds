<?php 	
require_once 'core.php';

error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

$mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$idx = (isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';
$code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : '';
$password = (isset($_POST['password']) && $_POST['password'] != '') ? $_POST['password'] : '';
if($mode == '') {
  $arr = ['result' => 'empty_mode'];
  exit(json_encode($arr));
}
$sql = 'SELECT password, code FROM aboard WHERE idx=?';
$stmt = $connect->prepare($sql);
$stmt->bind_param('i', $idx); // 'i' represents the data type of the parameter (integer)
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['code'] != $code) {
  $arr = ['result' => 'wrong_code'];
}

if (password_verify($password, $row['password'])) {
  if ($mode == 'delete') {
    $sql = 'DELETE FROM aboard WHERE idx=?';
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $idx); // 'i' represents the data type of the parameter (integer)
    $stmt->execute();
    $arr = ['result' => 'delete_success'];
  } elseif ($mode == 'edit') {
    $_SESSION['edit_idx'] = $idx;
    $arr = ['result' => 'edit_success'];
  } else {
    $arr = ['result' => 'wrong_mode'];
  }
  
} else {
  $arr = ['result' => 'wrong_password'];
}
die(json_encode($arr));

?>