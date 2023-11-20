<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 

  error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

  $idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx']) ? $_GET['idx'] : '');
  if($idx == '') {
    exit('Not allow to the abnormal access');
  }

	$sql = "UPDATE pboard SET hit=hit+1 WHERE idx=?";
	$stmt = $connect->prepare($sql);
	$stmt->bind_param('i', $idx);
	$stmt->execute();

  $sql = "SELECT * FROM pboard WHERE idx=?";
  $stmt = $connect->prepare($sql);
  $stmt->bind_param('i', $idx); // 'i' represents the data type of the parameter (integer)
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();  
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="dashboard.php">Home</a></li>		  
				<li class="active"><?= $board_title ?></li>
			</ol>

			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Bullet Board Write</div>
				</div> <!-- /panel-heading -->
				<div class="panel-body">
					<div class="remove-messages"></div>
					<div class="container border border-1 w-50 vstack">
						<div class='p-3'>
							<span class='h3 fw-bolder'><?= $row['subject'] ?></span>
						</div>
						<div class='d-flex px-3 border border-top-0 border-start-0 border-end-0 border-bottom-1'>
							<span><?= $row['name'] ?></span>
							<span class='ms-5 me-auto'><?= $row['hit'] ?></span>
							<span><?= $row['rdate'] ?></span>
						</div>
						<div id='bbs_content' class='p-3'>
							<?= $row['content'] ?><br>
						</div>
						<div class="mt-3 d-flex gap-2 p-3">
							<button id='btn_list' class="btn btn-secondary me-auto">List</button>
							<button id='btn_edit' class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#modal'>Update</button>
							<button id='btn_delete' class="btn btn-danger" data-bs-toggle='modal' data-bs-target='#modal'>Delete</button>
						</div>
					</div>

					<!-- Modal -->
					<div id='modal' class="modal" tabindex="-1">
						<div class="modal-dialog">
							<form method='post' name='modal_form' action='./php_action/fetchView.php'>
								<input type='hidden' name='mode' value=''>
								<div class="modal-content">
									<div class="modal-header">
										<h5 id='modal_title' class="modal-title">Modal title</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<input id='password' type='password' name='password' class='form-control' placeholder='Input your password' >

									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
										<button id='btn_pw_check' type="button" class="btn btn-primary">Check</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<script>
				const splited = window.location.search.replace('?','').split(/[=?&]/);
				let param = {};
				for(let i=0; i<splited.length; i++) {
					param[splited[i]] = splited[++i];
				}

				const btn_edit = document.querySelector('#btn_edit'); 
				btn_edit.addEventListener('click', () => {
					const modal_title = document.querySelector('#modal_title'); 
					modal_title.textContent = 'Edit';
					document.modal_form.mode.value = 'edit';
				})
				const btn_list = document.querySelector('#btn_list');
				btn_list.addEventListener('click', () => {
					self.location.href='./list.php?code=' + param['code'];
				})
				const btn_delete = document.querySelector('#btn_delete'); 
				btn_delete.addEventListener('click', () => {
					const modal_title = document.querySelector('#modal_title'); 
					modal_title.textContent = 'Delete';
					document.modal_form.mode.value = 'delete';
				})
				const btn_pw_check = document.querySelector('#btn_pw_check'); 
				btn_pw_check.addEventListener('click', () => {
					const password = document.querySelector('#password'); 
					if(password.value == '') {
						alert('Input your password');
						password.focus()
						return false;
					}
					// 비밀번호, code, 게시물 번호등을 가지고 비밀 번호 비교
					const xhr = new XMLHttpRequest();
					xhr.open('POST', './php_action/fetchView.php', true);
					const f1 = new FormData(document.modal_form);
					f1.append('idx', param['idx']);
					f1.append('code', param['code']);
					xhr.send(f1);
					xhr.onload = () => {
						if(xhr.status == 200) {
							const data = JSON.parse(xhr.responseText);
							if(data.result == 'edit_success') self.location.href = './edit.php?idx=' + param['idx'] + '&code=' + param['code'];  
							else if(data.result == 'delete_success') {
								alert('Deleted');
								self.location.href='./list.php?code=' + param['code'];
							} else if(data.result == 'wrong_password') {
								alert('Wrong password');
								password.value = '';
								password.focus()
							}
						} else alert(xhr.status)
					}

				})
				</script>
				<!-- /table -->   
			</div> <!-- /panel-body -->
		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->

<!-- add product -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

    	<form class="form-horizontal" id="submitProductForm" action="php_action/createProduct.php" method="POST" enctype="multipart/form-data">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Product</h4>
	      </div>

	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div id="add-product-messages"></div>

	      	<div class="form-group">
	        	<label for="productImage" class="col-sm-3 control-label">Product Image: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
					    <!-- the avatar markup -->
							<div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
					    <div class="kv-avatar center-block">					        
					        <input type="file" class="form-control" id="productImage" placeholder="Product Name" name="productImage" class="file-loading" style="width:auto;"/>
					    </div>
				      
				    </div>
	        </div> <!-- /form-group-->	     	           	       

	        <div class="form-group">
	        	<label for="productName" class="col-sm-3 control-label">Product Name: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="productName" placeholder="Product Name" name="productName" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	    

	        <div class="form-group">
	        	<label for="quantity" class="col-sm-3 control-label">Quantity: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="quantity" placeholder="Quantity" name="quantity" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	        	 

	        <div class="form-group">
	        	<label for="rate" class="col-sm-3 control-label">Rate: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="rate" placeholder="Rate" name="rate" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	     	        

	        <div class="form-group">
	        	<label for="brandName" class="col-sm-3 control-label">Brand Name: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="brandName" name="brandName">
				      	<option value="">~~SELECT~~</option>
				      	<?php 
				      	$sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_status = 1 AND brand_active = 1";
								$result = $connect->query($sql);

								while($row = $result->fetch_array()) {
									echo "<option value='".$row[0]."'>".$row[1]."</option>";
								} // while
								
				      	?>
				      </select>
				    </div>
	        </div> <!-- /form-group-->	

	        <div class="form-group">
	        	<label for="categoryName" class="col-sm-3 control-label">Category Name: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select type="text" class="form-control" id="categoryName" placeholder="Product Name" name="categoryName" >
				      	<option value="">~~SELECT~~</option>
				      	<?php 
				      	$sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
								$result = $connect->query($sql);

								while($row = $result->fetch_array()) {
									echo "<option value='".$row[0]."'>".$row[1]."</option>";
								} // while
								
				      	?>
				      </select>
				    </div>
	        </div> <!-- /form-group-->					        	         	       

	        <div class="form-group">
	        	<label for="productStatus" class="col-sm-3 control-label">Status: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="productStatus" name="productStatus">
				      	<option value="">~~SELECT~~</option>
				      	<option value="1">Available</option>
				      	<option value="2">Not Available</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->	         	        
	      </div> <!-- /modal-body -->
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
	        <button type="submit" class="btn btn-primary" id="createProductBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
	      </div> <!-- /modal-footer -->	      
     	</form> <!-- /.form -->	     
    </div> <!-- /modal-content -->    
  </div> <!-- /modal-dailog -->
</div> 
<!-- /add categories -->
<!-- edit categories brand -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	    	
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Product</h4>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div class="div-loading">
	      		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						<span class="sr-only">Loading...</span>
	      	</div>

	      	<div class="div-result">
				  <!-- Tab panes -->
				  <div class="tab-content">

				  	

			
				    <!-- /product info -->
				  </div>

				</div>
	      	
	      </div> <!-- /modal-body -->
	      	      
     	
    </div>
    <!-- /modal-content -->
  </div>
  <!-- /modal-dailog -->
</div>
<!-- /categories brand -->

<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Product</h4>
      </div>
      <div class="modal-body">

      	<div class="removeProductMessages"></div>

        <p>Do you really want to remove ?</p>
      </div>
      <div class="modal-footer removeProductFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
        <button type="button" class="btn btn-primary" id="removeProductBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="custom/js/board.js"></script>
<?php require_once 'includes/footer.php'; ?>