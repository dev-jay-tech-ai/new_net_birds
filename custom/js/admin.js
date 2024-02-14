const xhr = new XMLHttpRequest();
const url = window.location.href;
const regexFilename = /\/([^\/]+)\.php/;
const regexCode = /code=([^&]+)/;
let board = url.match(regexFilename)[1];
const code = url.match(regexCode) && url.match(regexCode)[1];
const btnDelete = document.querySelector('#btn-delete');
const checkboxes = document.querySelectorAll('.form-check-input:not(#checkAll)');
const editButtons = document.querySelectorAll('.editUserModalBtn');
const editUserModal = document.getElementById('editUserModal');
const closeModalButtons = $('[data-dismiss="modal"]');
const addUserBtn = document.querySelector('#addUserBtn');
const editUserBtn = document.querySelector('#editUserBtn');

if(board === 'list') board = code;

addUserBtn && addUserBtn.addEventListener('click',(e) => {
  e.preventDefault();
  const username = document.getElementById('username').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const passwordConfirm = document.getElementById('passwordConfirm').value;
  const active = document.getElementById('active').value;
  const status = document.getElementById('status').value;
  const credit = document.getElementById('credit').value;
  const fileInput = document.getElementById('fileInput');
  if(username == "") {
    $("#username").after('<p class="text-danger">Username field is required</p>');
    $('#username').closest('.form-group').addClass('has-error');
  } else {
    $("#username").find('.text-danger').remove();
  }

  let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if(email == "") {
      $("#email").after('<p class="text-danger">Email field is required</p>');
      $('#email').closest('.form-group').addClass('has-error');
  } else if (!emailRegex.test(email)) {
      $("#email").after('<p class="text-danger">Please enter a valid email address</p>');
      $('#email').closest('.form-group').addClass('has-error');
  } else {
      $("#email").find('.text-danger').remove();
  }

  if(password == "") {
    $("#password").after('<p class="text-danger">Password field is required</p>');
    $('#password').closest('.form-group').addClass('has-error');
  } else {
    // remov error text field
    $("#password").find('.text-danger').remove();  	
  }

  if(passwordConfirm == "") {
    $("#passwordConfirm").after('<p class="text-danger">Confirm Password field is required</p>');
    $('#passwordConfirm').closest('.form-group').addClass('has-error');
  } else {
    // remov error text field
    $("#passwordConfirm").find('.text-danger').remove(); 	
  }

  if (password !== passwordConfirm) {
    $("#passwordConfirm").after('<p class="text-danger">Passwords do not match</p>');
    $('#passwordConfirm').closest('.form-group').addClass('has-error');
  } else {
    // Remove error text field
    $("#passwordConfirm").find('.text-danger').remove();
  }

  if(username && email && password && passwordConfirm) {
    const formData = new FormData();
    formData.append('username', username);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('active', active);
    formData.append('status', status);
    formData.append('credit', credit);
    formData.append('file', fileInput.files[0]);
    for (let [key, value] of formData) {
      console.log(`${key}: ${value}`)
      } 
    xhr.open('POST', './php_action/createUser.php', true); 
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          handleResponse(xhr, 'users', 'added');
      }
    };
    xhr.send(formData);
  }
});

editUserBtn && editUserBtn.addEventListener('click',(e) => {
  e.preventDefault();
  const user_id = document.getElementById('user_id').value;
  const username = document.getElementById('username').value;
  const email = document.getElementById('email').value;
  const active = document.getElementById('active').value;
  const status = document.getElementById('status').value;
  const credit = document.getElementById('credit').value;
  const fileInput = document.getElementById('fileInput');

  const formData = new FormData();
  formData.append('user_id', user_id);
  formData.append('username', username);
  formData.append('email', email);
  formData.append('active', active);
  formData.append('status', status);
  formData.append('credit', credit);
  formData.append('file', fileInput.files[0]);
  xhr.open('POST', './php_action/fetchEditUser.php', true);
  xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          handleResponse(xhr, 'users', 'edited');
      }
  };
  xhr.send(formData);
});

closeModalButtons && closeModalButtons.click(function () {
  $('#editUserModal').modal('hide');
});

editButtons && editButtons.forEach(function (button) {
  button.addEventListener('click', function (e) {
    e.preventDefault();
    const userId = button.dataset.userId;
    $.ajax({ //create an ajax request to display.php
      type: "POST",
      url: "php_action/fetchUser.php",
      data: {
        'user_id' : userId
      },          
      success: function (response) {
        // console.log(response);
        $('.view-user-data').html(response);
        $('#editUserModal').modal('show');
      }
    });
  });
});

const checkAllCheckbox = document.getElementById('checkAll');
checkAllCheckbox.addEventListener('change', () => {
  checkboxes.forEach(function(checkbox) {
      checkbox.checked = checkAllCheckbox.checked;
  });
});

checkboxes && checkboxes.forEach((checkbox) => {
  checkbox.addEventListener('change', (e) => {
    if (e.target.checked) {
      const idx = checkbox.closest('tr').dataset.idx;
    }
  });
});

btnDelete && btnDelete.addEventListener('click', function () {
  const selectedItems = [];
  checkboxes.forEach((checkbox) => {
    if(checkbox.checked) {
      selectedItems.push(checkbox.closest('.view_detail').dataset.idx);
    }
  });
  console.log(selectedItems);
  if(selectedItems.length > 0) {
    const confirmDelete = confirm("Are you sure you want to delete?");
    if(!confirmDelete) return;
    const xhr = new XMLHttpRequest();
    const data = JSON.stringify({ items: selectedItems, board });
    if(board === 'users') {
      xhr.open('POST', './php_action/deleteUsers.php', true);
    } else xhr.open('POST', './php_action/deleteBoardBulk.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if(board === 'users') {
          handleResponse(xhr, 'users', 'deleted');
        } else {
          handleResponse(xhr, board, 'deleted');
        }
			}
    };
    xhr.send(data);
  } else {
    alert('Please select items to delete.');
  }
});

let user_id;
const btn_delete_user = document.querySelectorAll('.btn_delete_user');
btn_delete_user && btn_delete_user.forEach(button => {
	button.addEventListener('click', async (e) => {
		e.preventDefault();
    const confirmDelete = confirm("Are you sure you want to delete?");
    if(!confirmDelete) return;
		user_id = button.getAttribute('data-user-id');
		xhr.open('POST', './php_action/deleteUser.php', true); 
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(JSON.stringify({ user_id }));
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				handleResponse(xhr, 'users', 'deleted');
			}
		};
	});
});

const btn_block_user = document.querySelectorAll('.btn_block_user');
btn_block_user && btn_block_user.forEach(button => {
	button.addEventListener('click', async (e) => {
		e.preventDefault();
    const confirmBlock = confirm("Are you sure?");
    if(!confirmBlock) return;
		user_id = button.getAttribute('data-user-id');
		xhr.open('POST', './php_action/blockUser.php', true); 
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(JSON.stringify({ user_id }));
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				handleResponse(xhr, 'users', 'updated');
			}
		};
	});
});

let idx;
const btn_pin = document.querySelectorAll('.btn-pin');
const btn_deactivate = document.querySelectorAll('.btn-deactivate');
const btn_delete = document.querySelectorAll('.btn-delete');

btn_pin && btn_pin.forEach(button => {
	button.addEventListener('click', async(e) => {
		e.preventDefault();
		idx = button.getAttribute('data-idx');
		xhr.open('POST', './php_action/updatePin.php', true);
		xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({ idx, board }));
		xhr.onreadystatechange = function () {
			if(xhr.readyState === XMLHttpRequest.DONE) {
				handleResponse(xhr, board, 'changed');
			}
		};
	});
});

btn_deactivate && btn_deactivate.forEach(button => {
	button.addEventListener('click', async(e) => {
		e.preventDefault();
    const confirmHide = confirm("Are you sure you want to hide?");
    if(!confirmHide) return;
		idx = button.getAttribute('data-idx');
		xhr.open('POST', './php_action/updateActivity.php', true);
		xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({ idx, board }));
		xhr.onreadystatechange = function () {
			if(xhr.readyState === XMLHttpRequest.DONE) {
				handleResponse(xhr, board, 'changed');
			}
		};
	});
});

btn_delete && btn_delete.forEach((button) => {
	button.addEventListener('click', async (e) => {
		e.preventDefault();
    const confirmDelete = confirm("Are you sure you want to delete?");
    if(!confirmDelete) return;
		idx = button.getAttribute('data-idx');
		xhr.open('POST', './php_action/deleteBoardSingle.php', true); 
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(JSON.stringify({ idx, board }));
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				handleResponse(xhr, board, 'deleted');
			}
		};
	});
});

function handleResponse(xhr, board, action) {
  if (xhr.status == 200) {
    try {
    const data = JSON.parse(xhr.responseText);
    if (data.result == 'success') {
      alert(`${action} successfully!`);
      self.location.href = url;

    } else {
      alert(`Failed to : ${data.message}`);
    }
    } catch (error) {
      console.error('Error parsing JSON:', error);
    }
  } else {
      alert(`Error: ${xhr.status}`);
  }
}