<?php 	
require_once 'core.php';

$sql = "SELECT * FROM review"; 
		// WHERE review.status = 1

$result = $connect->query($sql);
$output = array('data' => array());

if($result->num_rows > 0) { 
 // $row = $result->fetch_array();
 $active = ""; 

 while($row = $result->fetch_array()) {
 	$reviewId = $row[0];
 	// active 
 	if($row[7] == 1) {
 		// activate member
 		$active = "<label class='label label-success'>Available</label>";
 	} else {
 		// deactivate member
 		$active = "<label class='label label-danger'>Not Available</label>";
 	} // /else

 	$button = '<!-- Single button -->
	<div class="btn-group">
	  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Action <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
	    <li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$reviewId.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
	    <li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$reviewId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
	  </ul>
	</div>';

	$imageUrl = substr($row[2], 3);
	$reviewImage = "<img class='img-round' src='".$imageUrl."' style='height:30px; width:50px;'  />";

 	$output['data'][] = array( 		
 		// image
 		$reviewImage,
 		// review name
 		$row[1], 
 		// rate
 		$row[4],
 		// active
 		$active,
 		// button
 		$button 		
 		); 	
 } // /while 

}// if num_rows
$connect->close();

echo json_encode($output);