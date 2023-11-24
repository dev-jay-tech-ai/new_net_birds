$(document).ready(function(){	
	// submit brand form function
	$("#submitRegisterForm").unbind('submit').bind('submit',function(e) {
		// remove the error text
		$(".text-danger").remove();
		// remove the form error
		$('.form-group').removeClass('has-error');			
		let userName = $("#username").val();
		let email = $("#email").val();
		let password = $("#password").val();
		let passwordConfirm = $("#passwordConfirm").val();

		if(userName == "") {
			$("#userName").after('<p class="text-danger">Username field is required</p>');
			$('#userName').closest('.form-group').addClass('has-error');
		} else {
			// remov error text field
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

    // Check if the password and confirm password match
    if (password !== passwordConfirm) {
			$("#passwordConfirm").after('<p class="text-danger">Passwords do not match</p>');
			$('#passwordConfirm').closest('.form-group').addClass('has-error');
		} else {
			// Remove error text field
			$("#passwordConfirm").find('.text-danger').remove();
		}

		if(username && email && password && passwordConfirm) {
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

							alert('Registration successful. Please proceed to log in.');
				
							window.location.replace('/newnetbirds/login.php');
				
					} else {
						if (response.messages.includes('Duplicate')) {
							if(response.messages.includes('email')) alert("Error: This email address is already registered. If you have an existing account, please log in. If you've forgotten your password, you can reset it using the 'Forgot Password' option. If this email does not belong to you or if you continue to experience issues, please contact our support team for assistance.");
							else if(response.messages.includes('username')) alert('Username is already taken. Please choose a different username.');
							
						} else {
								// Display a generic error message
								alert('An error occurred. Please try again.');
						}
					}
				},
				error: (jqXHR, textStatus, errorThrown) => {
					// Handle other AJAX errors, such as network issues
					console.log(jqXHR.responseText)
					console.error('AJAX Error: ' + textStatus, errorThrown);
					// Optionally, display an error message to the user
					// Show a message to the user informing about the failure
				}
			}); // ajax
		} // if
		return false;
	}); // /submit brand form function
});