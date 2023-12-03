<?php 
require_once 'php_action/core.php';
require_once 'includes/header.php'; 
require_once 'component/auth_session.php'; 
?>

<div class="container">
<div class="row">
	<div class="col-md-12 mb-5">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active">Users</li>
		</ol>
		<div class="div-action pull pull-right" style="padding-bottom:20px;">
			<button class="btn btn-primary" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal">Add User</button>
		</div> <!-- /div-action -->		
		<table class="table mb-4" id="manageProductTable">
			<thead>
				<tr>
					<th style="width:5%;">Profile</th>							
					<th>Name</th>
					<th>Email</th>
					<th>Active</th>
					<th>Status</th>
					<th>Credit</th>
					<th>Registered</th>
					<th style="width:10%;">Options</th>
				</tr>
			</thead>
		</table><!-- /table -->
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->
</div>
<!-- add user -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="submitProductForm" action="php_action/createReview.php" method="POST" enctype="multipart/form-data">
	      <div class="modal-header">
					<h5 class="modal-title">Add User</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">
	      	<div id="add-product-messages"></div>
	      	<div class="form-group">
				    <div class="col-sm-8">
					    <!-- the avatar markup -->
							<div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
					    <div class="kv-avatar center-block">					        
					        <input type="file" class="form-control" id="productImage" placeholder="Product Name" name="productImage" class="file-loading" style="width:auto;"/>
					    </div>			      
				    </div>
	        </div> <!-- /form-group-->	     	           	       
	        <div class="form-group">
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="productName" placeholder="Product Name" name="Username" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	    
					<div class="form-group">  
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="productName" placeholder="User email" name="email" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	    
	        <div class="form-group">
				    <div class="col-sm-8">
				      <select class="form-control" id="productStatus" name="productStatus">
				      	<option value="-1">Select</option>
				      	<option value="1">Paid</option>
				      	<option value="2">Unpaid</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->	
					<div class="form-group">
				    <div class="col-sm-8">
				      <select class="form-control" id="productStatus" name="productStatus">
				      	<option value="-1">Select</option>
				      	<option value="1">Admin</option>
				      	<option value="2">Guest</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->
					<div class="form-group">  
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="productName" placeholder="Credit" name="credit" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	 	         	        
	      </div> <!-- /modal-body -->
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary" id="createProductBtn" data-loading-text="Loading..." autocomplete="off">Save Changes</button>
	      </div> <!-- /modal-footer -->	      
     	</form> <!-- /.form -->	     
    </div> <!-- /modal-content -->    
  </div> <!-- /modal-dailog -->
</div> 


<script src="custom/js/users.js"></script>
<?php require_once 'includes/footer.php'; ?>