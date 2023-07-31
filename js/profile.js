$(document).ready(function(){

	if (localStorage.getItem("loggedin") !== 'true')
		window.location.href ='login.html';
	
	includeHTML();

	function includeHTML() {
	  	var z, i, elmnt, file, xhttp;
	  	/* Loop through a collection of all HTML elements: */
	  	z = document.getElementsByTagName("*");
	  	for (i = 0; i < z.length; i++) {
		    elmnt = z[i];
		    /*search for elements with a certain atrribute:*/
		    file = elmnt.getAttribute("w3-include-html");
		    if (file) {
		      	/* Make an HTTP request using the attribute value as the file name: */
		      	xhttp = new XMLHttpRequest();
		      	xhttp.onreadystatechange = function() {
	        		if (this.readyState == 4) {
			          	if (this.status == 200) {elmnt.innerHTML = this.responseText;}
			          	if (this.status == 404) {elmnt.innerHTML = "Page not found.";}
			          	/* Remove the attribute, and call this function once more: */
	          			elmnt.removeAttribute("w3-include-html");
	          			includeHTML();
	        		}
	      		}
	      		xhttp.open("GET", file, true);
	      		xhttp.send();
		      	/* Exit the function: */
		      	return;
	    	}
	  	}
	}

	var url = $(location).attr('href');
	var splitedurl = url.split('/').reverse()[0];

	if(splitedurl == 'profileupdate.html')
	{
		// get profile data in onload
		$.get("php/profile.php", 'id='+localStorage.getItem("id"),function(response, status){
			var result = JSON.parse(response);

			if(result.status == true) //set values to the corresponding fields
			{
				var result_data = result.data;
				$('#email_address').val(result_data.email_address);
				if(result_data.oid != '')
				{
					$('#first_name').val(result_data.first_name);
					$('#last_name').val(result_data.last_name);
					$('#dob').val(result_data.dob);
					$('#mobile_no').val(result_data.mobile_number);
					$('#age').val(result_data.age);
				}
			}
		});

		var currentDate = new Date();
		$('.dob').datepicker({
		    autoclose: true,
		    format: 'dd/mm/yyyy',
	      	autoclose:true,
	      	endDate: "currentDate",
	      	maxDate: currentDate
	    }).on('changeDate', function (ev) {
	        $(this).datepicker('hide');
      	});
	}
	else
	{
		// get profile data in onload
		$.get("php/profile.php", 'id='+localStorage.getItem("id"),function(response, status){
			var result = JSON.parse(response);

			if(result.status == true) //set values to the corresponding fields
			{
				var result_data = result.data;
				$('#email_address').text(result_data.email_address);

				if(result_data.oid != '')
				{
					$('#first_name').text(result_data.first_name);
					$('#last_name').text(result_data.last_name);
					$('#dob').text(result_data.dob);
					$('#mobile_no').text(result_data.mobile_number);
					$('#age').text(result_data.age);
				}
				else
				{
					$('#first_name').text('Nil');
					$('#last_name').text('Nil');
					$('#dob').text('Nil');
					$('#mobile_no').text('Nil');
					$('#age').text('Nil');
				}
			}
		});
	}


	/*profile update page validation*/
	$("#profileupdateform").validate({
		rules: {
			email_address: {
				required: true,
				email:true,
			},
			mobile_no: {
				digits: true,
				minlength:10,
				maxlength:10,
			},
			age: {
				digits:true,
			},
			old_password: {
				required: function(element) {
		        	return $('#new_password').val() != '';
		      	},
		      	remote: {
                    url: "php/check_old_password.php",
                    type: "post",
                    data: {                     
                        id: localStorage.getItem('id')
                    },		     
                }
			},
			confirm_new_password: {
				required: function(element) {
		        	return $('#new_password').val() != '';
		      	},
		      	equalTo: "#new_password"
			},

		},
		messages: {
			email_address: {
				required: 'Email Address field is required',
				email: "Please enter a valid email address",
			},
			mobile_no: {
				digits: "Please enter a valid mobile number",
				minlength:"Please enter at least 10 numbers.",
				maxlength:"Please enter no more than 10 numbers.",
			},
			age: {
				digits:"Please enter a valid age",
			},
			old_password: {
				required: "Old password field is required",
				remote: "Given password doesn't match"
			},
			confirm_new_password: {
				required:"New password field is required",
		      	equalTo: "Please enter the same value again."
			},
		},
	    submitHandler: function(form) {
		    $.ajax({
		      	type: "POST",
		      	url: "php/profileupdate.php",
		      	data: $('#profileupdateform').serialize()+ "&id="+localStorage.getItem('id'),
		      	dataType: "json",
		      	encode: true,
		    }).done(function (data) {
		    	if(data)
		    	{
		    		alert('Updated Successfully!...');
		    		window.location.href ='profile.html';
		    	}
		    	else
		    		alert('Unable to update');
		      	
		    });   
	    }
	});
	

	// logout functionality
	$(document).on('click', '#logout', function(){
		// clear session storage values
		if (confirm('Are you sure want to logout?')) {
			localStorage.clear();
			window.location.href ='login.html';
		}
	})


});