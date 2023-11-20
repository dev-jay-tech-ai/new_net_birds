<?php 

$code = (isset($_GET['code']) && $_GET['code'] != '') ? $_GET['code'] : '';
	switch($code) {
		case 'private' : $board_title = 'Private'; 
		break;
		case 'notice' : $board_title = 'Review'; 
		break;
		default : $code = 'private';
		$board_title = 'Private'; 
	}

?>