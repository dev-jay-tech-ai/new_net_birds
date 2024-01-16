<?php 
echo'
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" enctype="multipart/form-data">
	      <div class="modal-header">
					<h5 class="modal-title">添加用户</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">
	      	<div id="add-product-messages"></div>
	      	<div class="form-group">
				    <div class="col-sm-8">
					    <!-- the avatar markup -->
							<div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
					    <div class="kv-avatar center-block">					        
					      <input type="file" id="fileInput" class="form-control" placeholder="Profile" name="file" class="file-loading" accept="image/*" />
					    </div>			      
				    </div>
	        </div> <!-- /form-group-->	     	           	       
	        <div class="form-group">
				    <div class="col-sm-8">
				      <input type="text" id="username" class="form-control" placeholder="Username" name="username" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	    
					<div class="form-group">  
				    <div class="col-sm-8">
				      <input type="text" id="email" class="form-control" placeholder="Email" name="email" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	 
					<div class="form-group">
						<div class="col-sm-8">
            	<input type="password" id="password" class="form-control" placeholder="Enter Password" name="password" autocomplete=new-password>
						</div>
          </div>
          <div class="form-group">
						<div class="col-sm-8">
            	<input type="password" id="passwordConfirm" class="form-control" placeholder="Confirm Password" name="passwordConfirm" autocomplete=new-password>
            </div>
          </div>   
	        <div class="form-group">
				    <div class="col-sm-8">
				      <select id="active" class="form-control" name="active">
				      	<option value="-1">选项</option>
				      	<option value="1">有酬</option>
				      	<option value="2">无酬</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->	
					<div class="form-group">
				    <div class="col-sm-8">
				      <select id="status" class="form-control" name="status">
				      	<option value="-1">选项</option>
				      	<option value="1">管理员</option>
				      	<option value="2">访客</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->
					<div class="form-group">  
				    <div class="col-sm-8">
				      <input type="text" id="credit" class="form-control" id="productName" placeholder="Credit" name="credit" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	 	         	        
	      </div> <!-- /modal-body -->
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
	        <button type="submit" id="addUserBtn" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off">添加用户</button>
	      </div> <!-- /modal-footer -->	      
     	</form> <!-- /form -->	     
    </div> <!-- /modal-content -->    
  </div>';
?>