<?php 
$code = basename($_SERVER['PHP_SELF'], '.php');
if (strpos($code, 'private') !== false || strpos($code, 'Private') !== false) {
    $board_title = 'Private';
} elseif (strpos($code, 'agent') !== false || strpos($code, 'Agent') !== false) {
    $board_title = 'Club';
} elseif (strpos($code, 'review') !== false || strpos($code, 'Review') !== false) {
    $board_title = 'Review';
} else {
    $code = '';
    $board_title = '';
}
?>