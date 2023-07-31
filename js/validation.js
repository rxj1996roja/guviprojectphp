$(document).ready(function(){	

	if (localStorage.getItem("loggedin") === 'true') //if already logged in means, then redirect to profile page
		window.location.href ='profile.html';
	
	$("#loginform").validate({
		rules: {
			email_address: {
				required: true,
				email:true,
			},
			password: {
				required: true,
			},
		},
		messages: {
			email_address: {
				required: 'Email Address field is required',
				email: "Please enter a valid email address",
			},
			password: {
				required: 'Password field is required',
			},
		},
	    submitHandler: function(form) {
	    	var formData = {
		      	email_address: $("#email_address").val(),
		      	password: $("#password").val(),
		    };
		    $.ajax({
		      	type: "POST",
		      	url: "php/login.php",
		      	data: formData,
		      	dataType: "json",
		      	encode: true,
		    }).done(function (data) {
		      	if(data.status === 'true')
				{
					localStorage.setItem('loggedin', data.status);
					localStorage.setItem('id', data.id);
					window.location.href ='profile.html';
				}
				else
					alert('Invalid credentials!...')
		    });   
	    }
	});

	// Registration from
	$("#registrationform").validate({
		rules: {
			email_address: {
				required: true,
				email:true,
				remote: {
                    url: "php/check_email_address_exists.php",
                    type: "post",		     
                }
			},
			password: {
				required: true,
			},
			confirm_password: {
				required: true,
				equalTo: "#password"
			}
		},
		messages: {
			email_address: {
				required: 'Email Address field is required',
				email: "Please enter a valid email address",
				remote: "Given email address already exists"
			},
			password: {
				required: 'Password field is required',
			},
			confirm_password: {
				required: " Confirm password field is required",
				equalTo: "Password does not match !",
			}
		},
	    submitHandler: function(form) {
	    	var formData = {
		      	email_address: $("#email_address").val(),
		      	password: $("#password").val(),
		    };
		    $.ajax({
		      	type: "POST",
		      	url: "php/registration.php",
		      	data: formData,
		      	dataType: "json",
		      	encode: true,
		    }).done(function (data) {
				if(data.status === 'true')
				{
					localStorage.setItem('loggedin', data.status);
					localStorage.setItem('id', data.id);
					window.location.href ='profile.html';
				}
		    });   
	    }
	})
});