<?php 
  if($result['active'] == 1) {
    echo '<div class="div-action pull pull-right" style="padding-bottom:20px;">
      <button class="btn btn-default button1" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal"> <i class="glyphicon glyphicon-plus-sign"></i> Add Product </button>
    </div>';		
  } else {
    echo '<div class="alert alert-danger" role="alert">
    <i class="glyphicon glyphicon-exclamation-sign"></i>
    You are required to pay for the utilisation of this service
    </div>';	
  }
?>