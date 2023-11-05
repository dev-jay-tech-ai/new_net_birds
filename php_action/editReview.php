<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
	$review_id = $_POST['reviewId'];
	$reviewName 		= $_POST['editReviewName']; 
  $reviewContent 			= $_POST['editReviewContent'];
  $rate 					= $_POST['editRate'];
  $reviewStatus 	= $_POST['editreviewStatus'];

	$sql = "UPDATE review SET review_name = '$reviewName', brand_id = '$brandName', categories_id = '$categoryName', quantity = '$quantity', rate = '$rate', active = '$reviewStatus', status = 1 WHERE review_id = $reviewId ";

	if($connect->query($sql) === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Update";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating review info";
	}

} // /$_POST
	 
$connect->close();

echo json_encode($valid);
 
