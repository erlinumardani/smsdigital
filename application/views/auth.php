<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{title}</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- START: Template CSS-->
	<link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/jquery-ui/jquery-ui.min.css">
	<link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/jquery-ui/jquery-ui.theme.min.css">
	<link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/simple-line-icons/css/simple-line-icons.css">        
	<link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/flags-icon/css/flag-icon.min.css"> 
	<link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/sweetalert/sweetalert.css">
	<!-- END Template CSS-->     

	<!-- START: Page CSS-->   
	<link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/social-button/bootstrap-social.css"/>   
	<!-- END: Page CSS-->

	<!-- START: Custom CSS-->
	<link rel="stylesheet" href="{base_url}assets/pick/dist/css/main.css">
	<style  type="text/css">
   
		body {
		/*opacity: 0.2;
		filter: alpha(opacity=20);*/
		background-image:url({base_url}{bg_login}) !important;
		background-size: cover !important;
		background-color: #D2D6DE !important;
		
			background-attachment: fixed !important;
			background-repeat: no-repeat !important;
			background-position:center center !important;
		}

	</style>

	<!-- START: Template JS-->
	<script src="{base_url}assets/pick/dist/vendors/jquery/jquery-3.3.1.min.js"></script>
	<script src="{base_url}assets/pick/dist/vendors/jquery-ui/jquery-ui.min.js"></script>
	<script src="{base_url}assets/pick/dist/vendors/moment/moment.js"></script>
	<script src="{base_url}assets/pick/dist/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>    
	<script src="{base_url}assets/pick/dist/vendors/slimscroll/jquery.slimscroll.min.js"></script>
	<!-- END: Template JS-->

	<script src="{base_url}assets/pick/dist/vendors/sweetalert/sweetalert.min.js"></script>
	<script src="{base_url}assets/js/jquery.cookie.js"></script>

	<script>

		$(window).on("load", function () {
			// Animate loader off screen
			$(".se-pre-con").fadeOut("slow");
			;
		});

		$(document).ready(function () { 
			$('#username').val($.cookie("username"));
			$('#password').val($.cookie("password"));
			$('#remember').prop('checked', $.cookie("remember"));

			$( "#form_auth" ).submit(function( event ) {
				
				event.preventDefault();
				var form = $(this);
				var url = form.attr('action');

				$.ajax({
					type: "POST",
					url: url,
					data: form.serialize(), // serializes the form's elements.
					dataType: 'json',
					success: function(data)
					{
						if(data.status=="success"){
							document.location.href="{base_url}profile/data/update";
							if($('#remember').is(':checked')){
								$.cookie("username", $('#username').val());
								$.cookie("password", $('#password').val());
								$.cookie("remember", true);
							}else{
								$.removeCookie("username");
								$.removeCookie("password");
								$.removeCookie("remember");
							}
						}else{
							/* $('#alert').show(); 
							$('#error').append(data.messages); */ 
							swal({
								title: "Login Failed",
								text: data.messages,
								type: "error",
								confirmButtonClass: 'btn-danger',
								confirmButtonText: 'Ok'
							});
							setTimeout(function() {
								$('#alert').slideUp("slow");
								$('#error').empty(); 
							}, 2000);
						}
					}
					});

			});

			$('.captcha-refresh').on('click', function(){
				$.get('<?php echo base_url().'auth/refresh_captcha'; ?>', function(data){
					$('#image_captcha').html(data);
				});
			});
		});

	</script>
</head>
	
<body id="main-container" class="hold-transition login-page">
<div class="se-pre-con">
	<div class="loader"></div>
</div>

<div class="container">
	<div class="row vh-100 justify-content-between align-items-center">
		<div class="col-12">
			<form id="form_auth" action="{base_url}auth/authentication" method="post" class="row row-eq-height lockscreen  mt-5 mb-5">
				<input name="{csrf_token_name}" type="hidden" value="{csrf_hash}">
				<div class="lock-image col-12 col-sm-5"></div>
				<div class="login-form col-12 col-sm-7">
					<div class="form-group mb-3">
						<img src="{base_url}{logo}" width="100%"><br />
					</div>

					<div class="form-group mb-3">
						<label for="emailaddress">Email address</label>
						<input class="form-control" type="email" id="username" name="username" required="" placeholder="Enter your email">
					</div>

					<div class="form-group mb-3">
						<label for="password">Password</label>
						<input class="form-control" type="password" required="" id="password" name="password" placeholder="Enter your password">
					</div>

					<div class="form-group mb-3">
						<label for="password">Captcha</label>
						<input class="form-control" type="text" required="" autocomplete="off" id="captcha" name="captcha" placeholder="Enter captcha">
					</div>

					<div class="form-group mb-3">
						<?=$captcha?>
					</div>

					<div class="form-group mb-3">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="checkbox-signin" checked="">
							<label class="custom-control-label" for="checkbox-signin">Remember me</label>
						</div>
					</div>

					<div class="form-group mb-0">
						<button class="btn btn-primary" type="submit"> Log In </button>
					</div>
					<!-- 
					<p class="my-2 text-muted">--- Or connect with ---</p>
					<a class="btn btn-social btn-dropbox text-white mb-2">
						<i class="icon-social-dropbox align-middle"></i>
					</a>
					<a class="btn btn-social btn-facebook text-white mb-2">
						<i class="icon-social-facebook align-middle"></i>
					</a>                                   
					<a class="btn btn-social btn-github text-white mb-2">
						<i class="icon-social-github align-middle"></i>
					</a>
					<a class="btn btn-social btn-google text-white mb-2">
						<i class="icon-social-google align-middle"></i>
					</a>
					<div class="mt-2">Don't have an account? <a href="page-register.html">Create an Account</a></div>
					 -->
				</div>
			</form>
		</div>
	</div>
</div>

</body>
</html>