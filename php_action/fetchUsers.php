<?php 	
require_once 'core.php';

$sql = "SELECT * FROM users"; 
$result = $connect->query($sql);
$output = array('data' => array());

/**
 * user_id username password email user_image active credit status
 */
if($result->num_rows > 0) { 
 $active = ""; 
 while($row = $result->fetch_array()) {
 	$userId = $row[0];
 	// active 
 	if($row[6] == 1) {
		$active = "<label class='label label-primary'>paid</label>";
 	} else {
 		$active = "<label class='label label-warning'>unpaid</label>";
 	} 
	// status 
	if($row[7] == 1) {
		$status = "<label class='label label-danger'>admin</label>";
	} else {
		$status = "<label class='label label-success'>guest</label>";
	}
 	$button = '<!-- Single button -->
	<div class="btn-group">
	  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Action</button>
	  <ul class="dropdown-menu">
	    <li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$reviewId.')">Edit</a></li>
	    <li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$reviewId.')">Remove</a></li>       
	  </ul>
	</div>';
	$imageUrl = $row[4];
	$userImage = "<div class='board_profile'></div>";
 	$output['data'][] = array( 		
 		// image
 		$userImage,
 		// user name
 		$row[1], 
		// user email
		$row[3], 
 		// active
 		$active,
		// status
		$status,
		// credit
    $row[5], 
		// rDate
		'2023-12-01', 
 		// button
 		$button 		
 		); 	
 } // /while 
}// if num_rows
$connect->close();
echo json_encode($output);