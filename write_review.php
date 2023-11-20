<?php 
	require_once 'php_action/db_connect.php';
	require_once 'includes/header.php'; 
	include 'component/pagination.php'; 
	include 'component/config.php'; 
?>

<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active"><?= $board_title ?></li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Review Write</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">

				<div class="remove-messages"></div>
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
				<div class="mt-4 mb-3"></div>
				<div class="mb-2 d-flex gap-2">
					<input id='id_name' type='text' name='name' class='form-control w-25' 
						placeholder='Writer' autocomplete='off'>
					<input id='id_pw' type='password' name='password' class='form-control w-25' 
						placeholder='Password' autocomplete='off'>
					<div>Rating: </div>
					<h4 class="text-center mt-2 mb-4">
						<i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
						<i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
						<i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
						<i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
						<i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
					</h4>	
					<input type="hidden" id="id_rate" name="rating" value="">

				</div>
				<div>
					<input id='id_sub' type='text' name='subject' class='form-control mb-2' 
					  placeholder='Title' autocomplete='off'>
				</div>
        <div id="summernote"></div>
				<div class="mt-2 d-flex gap-2 justify-content-end">
					<button id='btn_submit' class='btn btn-primary'>OK</button>
					<button id='btn_list' class='btn btn-secondary'>LIST</button>
				</div>
        <script>
					const aa = window.location.search.replace('?','').split(/[=?&]/)
					let param = {}
					for(let i=0; i<aa.length; i++) param[aa[i]] = aa[++i]

					const btn_submit = document.querySelector('#btn_submit');
					btn_submit.addEventListener('click', () => {
						const id_name = document.querySelector('#id_name');
						const id_pw = document.querySelector('#id_pw');
						const id_sub = document.querySelector('#id_sub');
						const id_rate = document.querySelector('#id_rate');
						if(id_name.value == '') {
							alert('Input the writer')
							id_name.focus();
							return false;
						}
						if(id_pw.value == '') {
							alert('Input the password')
							id_pw.focus();
							return false;
						}
						if(id_sub.value == '') {
							alert('Input the subject')
							id_sub.focus();
							return false;
						}
						const markupstr = $('#summernote').summernote('code');
						if(markupstr === '<p><br></p>') {
							alert('Input the content')
							return false;
						}
						const f1 = new FormData()
						f1.append('name', id_name.value)
						f1.append('pw', id_pw.value)
						f1.append('title', id_sub.value)
						f1.append('content', markupstr)
						f1.append('rate', id_rate.value)

						const xhr = new XMLHttpRequest()
						xhr.open('POST', './php_action/fetchReview.php', 'true')
						xhr.send(f1)
						btn_submit.disabled = true
						xhr.onload = () => {
							if(xhr.status == 200) {
								const data = JSON.parse(xhr.responseText)
								if(data.result == 'success') {
									alert('Success!')
									self.location.href = '/newnetbirds/review.php';
								} else alert('Failed')
							} else alert(xhr.status)
						}
					})
					const btn_list = document.querySelector('#btn_list');
					btn_list.addEventListener('click', () => {
						self.location.href='./review.php';
					})
					
          $('#summernote').summernote({
            placeholder: 'Write your review',
            tabsize: 2,
            height: 120,
            toolbar: [
              ['style', ['style']],
              ['font', ['bold', 'underline', 'clear']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['table', ['table']],
              ['insert', ['link', 'picture', 'video']],
              ['view', ['fullscreen', 'codeview', 'help']]
            ]
          });

        </script>
				<!-- /table -->

			</div> <!-- /panel-body -->
		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->

<script src="custom/js/review.js"></script>
<?php require_once 'includes/footer.php'; ?>