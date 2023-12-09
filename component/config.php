<?php 
    if($code == 'agent') {
        $board_title = 'Club';
    } else {
        $board_title = ucfirst($code);
    }

    if($board_title == 'Club') {
        $board = 'aboard';
    } elseif ($board_title == 'Private') {
        $board = 'pboard';
    } elseif ($board_title == 'Review') {
        $board = 'rboard';
    } elseif ($board_title == 'Jobs') {
        $board = 'jboard';
    } elseif ($board_title == 'Property') {
        $board = 'prboard';
    }

?>