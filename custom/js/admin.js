const xhr = new XMLHttpRequest();
const url = window.location.href;
const regex = /\/([^\/]+)\.php/;
const match = url.match(regex);
const board = match[1];
const btnDelete = document.querySelector('#btn-delete');
const checkboxes = document.querySelectorAll('.form-check-input');
const editButtons = document.querySelectorAll('.editUserModalBtn');
const editUserModal = document.getElementById('editUserModal');
const closeModalButtons = $('[data-dismiss="modal"]');

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

btnDelete && btnDelete.addEventListener('click', function () {
  const selectedItems = [];
  checkboxes.forEach((checkbox) => {
    if(checkbox.checked) {
      selectedItems.push(checkbox.closest('.view_detail').dataset.idx);
    }
  });
  if(selectedItems.length > 0) {
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

checkboxes && checkboxes.forEach((checkbox) => {
  checkbox.addEventListener('change', (e) => {
    if (e.target.checked) {
      const idx = checkbox.closest('tr').dataset.idx;
    }
  });
});

let user_id;
const btn_delete_user = document.querySelectorAll('.btn_delete_user');
btn_delete_user && btn_delete_user.forEach(button => {
	button.addEventListener('click', async (e) => {
		e.preventDefault();
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

let idx;
const btn_deactivate = document.querySelectorAll('.btn-deactivate');
const btn_delete = document.querySelectorAll('.btn-delete');
btn_deactivate && btn_deactivate.forEach(button => {
	button.addEventListener('click', async (e) => {
		e.preventDefault();
		idx = button.getAttribute('data-idx');
		xhr.open('POST', './php_action/updateActivation.php', true);
		xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({ idx, board }));
		xhr.onreadystatechange = function () {
			if(xhr.readyState === XMLHttpRequest.DONE) {
				handleResponse(xhr, board, 'deactived');
			}
		};
	});
});

btn_delete && btn_delete.forEach(button => {
	button.addEventListener('click', async (e) => {
		e.preventDefault();
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
      alert(`Data changed successfully!`);
      self.location.href = `/${board}.php`;
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