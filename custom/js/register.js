$(document).ready(function(){	
	// submit brand form function
	$("#submitRegisterForm").unbind('submit').bind('submit',function(e) {
		// remove the error text
		$(".text-danger").remove();
		// remove the form error
		$('.form-group').removeClass('has-error');			
		let username = $("#username").val();
		let email = $("#email").val();
		let password = $("#password").val();
		let passwordConfirm = $("#passwordConfirm").val();

		let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
		let usernameRegex = /^[a-z0-9\u4e00-\u9fa5]+$/;

		if (username === "") {
			$("#username").after('<p class="text-danger">用户名字段是必填项</p>');
			$('#username').closest('.form-group').addClass('has-error');
		} else if (!usernameRegex.test(username)) {
			$("#username").after('<p class="text-danger">请输入有效的用户名（小写，无空格，可以包含中文字符）</p>');
			$('#username').closest('.form-group').addClass('has-error');
			// If the username is not qualified by the regex, prevent form submission
			e.preventDefault();
			return false;
		} else {
			$("#username").find('.text-danger').remove();
		}

		if (email == "") {
			$("#email").after('<p class="text-danger">电子邮件字段是必填项</p>');
			$('#email').closest('.form-group').addClass('has-error');
		} else if (!emailRegex.test(email)) {
			$("#email").after('<p class="text-danger">请输入有效的电子邮件</p>');
			$('#email').closest('.form-group').addClass('has-error');
			// If the email is not qualified by the regex, prevent form submission
			e.preventDefault();
			return false;
		} else {
			$("#email").find('.text-danger').remove();
		}

		if(password == "") {
			$("#password").after('<p class="text-danger">密码字段是必填项</p>');
			$('#password').closest('.form-group').addClass('has-error');
		} else {
			// remov error text field
			$("#password").find('.text-danger').remove();  	
		}

		if(passwordConfirm == "") {
			$("#passwordConfirm").after('<p class="text-danger">确认密码字段是必填项</p>');
			$('#passwordConfirm').closest('.form-group').addClass('has-error');
		} else {
			// remov error text field
			$("#passwordConfirm").find('.text-danger').remove(); 	
		}

    // Check if the password and confirm password match
    if (password !== passwordConfirm) {
			$("#passwordConfirm").after('<p class="text-danger">密码不一致</p>');
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
						alert('注册成功，请继续登录。');
						window.location.replace('/login.php');
					} else {
						if (response.messages.includes('Duplicate')) {
						if(response.messages.includes('email')) alert("此电子邮件地址已注册。如果您有现有帐户，请登录。如果您忘记密码，可以使用“忘记密码”选项进行重置。如果此电子邮件不属于您，或者如果您继续遇到问题，请联系我们的支持团队寻求帮助。");
						else if(response.messages.includes('username')) alert('用户名已经被使用，请选择一个不同的用户名。');						
						} else {
								// Display a generic error message
								alert('发生错误，请重试。');
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