$(document).ready(function(){	
	// submit brand form function
	$("#submitRegisterForm").unbind('submit').bind('submit',function(e) {
		console.log('클릭댐')
		// remove the error text
		$(".text-danger").remove();
		// remove the form error
		$('.form-group').removeClass('has-error');			
		let userName = $("#username").val();
		let password = $("#password").val();
		let passwordConfirm = $("#passwordConfirm").val();

		if(userName == "") {
			$("#userName").after('<p class="text-danger">Username field is required</p>');
			$('#userName').closest('.form-group').addClass('has-error');
		} else {
			// remov error text field
			$("#username").find('.text-danger').remove();
 	
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

    // Check if the password and confirm password match
    if (password !== passwordConfirm) {
			$("#passwordConfirm").after('<p class="text-danger">Passwords do not match</p>');
			$('#passwordConfirm').closest('.form-group').addClass('has-error');
		} else {
			// Remove error text field
			$("#passwordConfirm").find('.text-danger').remove();
		}

		if(username && password && passwordConfirm) {
			e.preventDefault();
			const form = $(this);
			// button loading
			$("#createAccountBtn").button('loading');
			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success: function(response) {
					$("#createAccountBtn").button('reset');
					if (response.success == true) {
							// Reset the form text
							$("#submitRegisterForm")[0].reset();
							// Remove the error text
							$(".text-danger").remove();
							// Remove the form error
							$('.form-group').removeClass('has-error');
							$(".alert-success").delay(500).show(10, function() {
									$(this).delay(3000).hide(10, () => {
											$(this).remove();
									});
							}); // /.alert
							// Redirect to another page after successful registration
							window.location.replace('/newnetbirds/login.php');
					} else {
						// Handle unsuccessful response (errors from server)
						console.log(response.messages)
					}
				},
				error: (jqXHR, textStatus, errorThrown) => {
					// Handle other AJAX errors, such as network issues
					console.error('AJAX Error: ' + textStatus, errorThrown);
					// Optionally, display an error message to the user
					// Show a message to the user informing about the failure
				}
			}); // ajax
		
		} // if

		return false;
	}); // /submit brand form function

});