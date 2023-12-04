<?php 
echo'
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="submitProductForm" action="php_action/createUser.php" method="POST" enctype="multipart/form-data">
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
					        <input type="file" class="form-control" placeholder="Profile" name="file" class="file-loading" accept="image/*" />
					    </div>			      
				    </div>
	        </div> <!-- /form-group-->	     	           	       
	        <div class="form-group">
				    <div class="col-sm-8">
				      <input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	    
					<div class="form-group">  
				    <div class="col-sm-8">
				      <input type="text" class="form-control" placeholder="Email" name="email" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	 
					<div class="form-group">
						<div class="col-sm-8">
            	<input type="password" class="form-control" placeholder="Enter Password" name="password">
						</div>
          </div>
          <div class="form-group">
						<div class="col-sm-8">
            	<input type="password" class="form-control" placeholder="Confirm Password" name="passwordConfirm">
            </div>
          </div>   
	        <div class="form-group">
				    <div class="col-sm-8">
				      <select class="form-control" name="active">
				      	<option value="-1">Select</option>
				      	<option value="1">Paid</option>
				      	<option value="2">Unpaid</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->	
					<div class="form-group">
				    <div class="col-sm-8">
				      <select class="form-control" name="status">
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
	        <button type="submit" class="btn btn-primary" id="createUserBtn" data-loading-text="Loading..." autocomplete="off">Add user</button>
	      </div> <!-- /modal-footer -->	      
     	</form> <!-- /.form -->	     
    </div> <!-- /modal-content -->    
  </div> <!-- /modal-dailog -->';
?>