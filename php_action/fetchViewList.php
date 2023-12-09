<?php
require_once 'core.php';

$mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$idx = (isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';
$code = (isset($_POST['code']) && $_POST['code'] != '') ? $_POST['code'] : '';

if ($code == 'agent') {
    $board = 'aboard';
} elseif ($code == 'private') {
    $board = 'pboard';
} elseif ($code == 'review') {
    $board = 'rboard';
}
if ($mode == '') {
    $arr = ['result' => 'empty_mode'];
    exit(json_encode($arr));
}
$sql = "SELECT * FROM $board WHERE idx=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param('i', $idx);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($mode == 'delete') {
    $sql = "DELETE FROM $board WHERE idx=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $idx);
    $stmt->execute();
    $arr = ['result' => 'delete_success'];
} elseif ($mode == 'edit') {
    $_SESSION['edit_idx'] = $idx;
    $arr = ['result' => 'edit_success'];
} else {
    $arr = ['result' => 'wrong_mode'];
}

die(json_encode($arr));
?>
