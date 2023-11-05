<?php 	
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

$reviewId = $_POST['reviewId'];

if($reviewId) { 

 $sql = "UPDATE review SET active = 2, status = 2 WHERE review_id = {$reviewId}";

 if($connect->query($sql) === TRUE) {
 	$valid['success'] = true;
	$valid['messages'] = "Successfully Removed";		
 } else {
 	$valid['success'] = false;
 	$valid['messages'] = "Error while remove the brand";
 }
 
 $connect->close();

 echo json_encode($valid);
 
} // /if $_POST