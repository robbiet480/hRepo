// This is the clientside implementation of validation
// If you change something here, remember to change it in the User class
// or your changes won't really be that effective ^_^
$(document).ready(function(){
	jQuery.validator.addMethod("username", function(value, element) { 
		return this.optional(element) || /^[a-zA-Z0-9_]+$/.test(value); 
	}, "Usernames may only contain letters, numbers, and underscores.");
	$('#registerForm').validate({
		rules: {
			password: {
				minlength: 8,
				required: true
			},
			confirmPassword: {
				equalTo: "#passwordReg",
				required: true
			},
			email: {
				required: true,
				email: true
			},
			username: {
				required: true,
				username: true,
				rangelength: [4, 32]
			}
		},
		messages: {
			password: {
				minlength: "Your password should be longer than 8 characters.",
				required: "Please enter a password."
			},
			confirmPassword: {
				equalTo: "The password confirmation does not match your original password.",
				required: "Please reenter your password."
			},
			email: {
				required: "We need an email address to validate your account.",
				email: "Please enter a valid email address."
			},
			username: {
				required: "Please enter a username.",
				username: "Usernames may only contain letters, numbers, and underscores.",
				rangelength: "Usernames should be between 4 and 32 characters."
			}
		}
	});
});