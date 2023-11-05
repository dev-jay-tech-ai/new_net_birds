<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {		

$reviewId = $_POST['reviewId'];
 
$type = explode('.', $_FILES['editReviewImage']['name']);
	$type = $type[count($type)-1];		
	$url = '../assests/images/review/'.uniqid(rand()).'.'.$type;
	if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
		if(is_uploaded_file($_FILES['editReviewImage']['tmp_name'])) {			
			if(move_uploaded_file($_FILES['editReviewImage']['tmp_name'], $url)) {

				$sql = "UPDATE review SET review_image = '$url' WHERE review_id = $reviewId";				

				if($connect->query($sql) === TRUE) {									
					$valid['success'] = true;
					$valid['messages'] = "Successfully Updated";	
				} else {
					$valid['success'] = false;
					$valid['messages'] = "Error while updating review image";
				}
			}	else {
				return false;
			}	// /else	
		} // if
	} // if in_array 		
	 
	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST