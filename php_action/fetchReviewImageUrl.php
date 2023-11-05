<?php 	
require_once 'core.php';

$reivewId = $_GET['i'];

$sql = "SELECT review_image FROM reivew WHERE reivew_id = {$reivewId}";
$data = $connect->query($sql);
$result = $data->fetch_row();

$connect->close();

echo "review/" . $result[0];