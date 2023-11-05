<?php 	

require_once 'core.php';

$reviewId = $_POST['reviewId'];

$sql = "SELECT review_id, review_name, review_image, review_content, client_name, client_contact, rate, active, status FROM review WHERE review_id = $reviewId";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);