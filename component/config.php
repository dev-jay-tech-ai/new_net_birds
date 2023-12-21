<?php 
    if($code == 'agent') {
        $board = 'aboard';
        $board_title = '伴游机构';
    } elseif ($code == 'private') {
        $board = 'pboard';
        $board_title = '私密约会';
    } elseif ($code == 'review') {
        $board = 'rboard';
        $board_title = '评论区';
    } elseif ($code == 'jobs') {
        $board = 'jboard';
        $board_title = '招聘求职';
    } elseif ($code == 'property') {
        $board = 'prboard';
        $board_title = '房屋出租';
    }
?>