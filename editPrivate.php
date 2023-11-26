<?php 
require_once 'php_action/db_connect.php';
require_once 'includes/header.php'; 
include 'component/config.php'; 

$edit_idx = (isset($_SESSION['edit_idx']) && $_SESSION['edit_idx'] != '' && is_numeric($_SESSION['edit_idx'])) ? $_SESSION['edit_idx'] : '';
$idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx'])) ? $_GET['idx'] : '';

if($idx == '') die('<script>alert("Not any number"); history.go(-1);</script>');
if($edit_idx != $idx) {
  die('<script>alert("You don\'t have a permission to edit"); history.go(-1);</script>');
}

$sql = "SELECT * FROM pboard WHERE idx=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param('i', $idx); // 'i' represents the data type of the parameter (integer)
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

?>
<div class='container'>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active"><?= $board_title ?></li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Private write</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">

				<div class="remove-messages"></div>

        <div class="container w-50">
          <div class="mt-4 mb-3">
            <h2><?= $board_title ?></h2>
          </div>
          <div class="mb-2 d-flex gap-2">
            <input id='id_name' type='text' name='name' class='form-control w-25' 
              value="<?=  $row['name'] ?>" placeholder='Writer' autocomplete='off'>
            <input id='id_pw' type='password' name='password' class='form-control w-25' 
              placeholder='Password' autocomplete='off'>
          </div>
          <div>
            <input id='id_sub' type='text' name='subject' class='form-control mb-2' 
            value="<?=  $row['subject'] ?>" placeholder='Title' autocomplete='off'>
          </div>
          <div id="summernote"></div>
          <div class="mt-2 d-flex gap-2 justify-content-end">
            <button id='btn_submit' class='btn btn-primary'>OK</button>
            <button id='btn_list' class='btn btn-secondary'>LIST</button>
          </div>
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
						f1.append('code', '<?= $code ?>')
						f1.append('idx', param['idx'])
						// ajax
						const xhr = new XMLHttpRequest()
						xhr.open('POST', './php_action/fetchEditPrivate.php', 'true')
						xhr.send(f1)
						btn_submit.disabled = true
						xhr.onload = () => {
							if(xhr.status == 200) {
                console.log(xhr.responseText)
                console.log(JSON.parse(xhr.responseText))

								const data = JSON.parse(xhr.responseText)
								if(data.result == 'success') {
									alert('Success!')
									self.location.href = '/newnetbirds/view_private.php?code=private' + '&idx=' + param['idx'];
                } else if(data.result == 'denied') {
                  alert('No permission to edit');
                  self.location.href = '/newnetbirds/private.php?code=private';
								} else alert('Failed')
							} else alert(xhr.status)
						}
					})
					const btn_list = document.querySelector('#btn_list');
					btn_list.addEventListener('click', () => {
						self.location.href='./private.php?code=' + param['code'];
					})
          $('#summernote').summernote({
            placeholder: 'Market yourself',
            tabsize: 2,
            height: 500,
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
          const markupstr = `<?=  $row['content'] ?>`;
          $('#summernote').summernote('code', markupstr);
        </script>

				<!-- /table -->

			</div> <!-- /panel-body -->
		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->
</div>
<?php require_once 'includes/footer.php'; ?>