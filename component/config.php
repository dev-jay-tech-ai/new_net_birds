<?php 
$filename = basename($_SERVER['PHP_SELF'], '.php');
if (strpos($filename, 'private') !== false || strpos($filenam, 'Private') !== false) {
    $code = 'private';
    $board_title = 'Private';
} elseif (strpos($filename, 'agent') !== false || strpos($filenam, 'Agent') !== false) {
    $code = 'agent';
    $board_title = 'Club';
} elseif (strpos($filename, 'review') !== false || strpos($filenam, 'Review') !== false) {
    $board_title = 'Review';
} else {
    $code = '';
    $board_title = '';
}
?>