<?php 	
require_once 'core.php';

$sql = "SELECT * FROM users"; 
// WHERE review.status = 1

$result = $connect->query($sql);
$output = array('data' => array());

if($result->num_rows > 0) { 
 // $row = $result->fetch_array();
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
	    <li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$reviewId.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
	    <li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$reviewId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
	  </ul>
	</div>';
	$imageUrl = $row[4];
	$userImage = "<img class='img-round' src='".$imageUrl."' style='height:35px; width:35px;' />";
 	$output['data'][] = array( 		
 		// image
 		$userImage,
 		// user name
 		$row[1], 
 		// active
 		$active,
		// status
		$status,
		// credit
    $row[5], 
 		// button
 		$button 		
 		); 	
 } // /while 

}// if num_rows
$connect->close();
echo json_encode($output);