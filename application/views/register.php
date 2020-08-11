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

<!-- Font Awesome -->
<link rel="stylesheet" href="{base_url}assets/adminlte/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="{base_url}assets/adminlte/dist/css/ionicons.min.css">
<!-- icheck bootstrap -->
<link rel="stylesheet" href="{base_url}assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{base_url}assets/adminlte/dist/css/adminlte.min.css">
<!-- Google Font: Source Sans Pro -->
<!-- <link href="{base_url}assets/adminlte/dist/css/fonts.googleapis.com.css" rel="stylesheet"> -->
<!-- Favicon-->
<link rel="icon" href="{base_url}{icon}" type="image/x-icon">
<!-- Select2 -->
<link rel="stylesheet" href="{base_url}assets/adminlte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{base_url}assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{base_url}assets/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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

	.preloader {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 9999;
		background-color: #fff;
	}
	.preloader .loading {
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%,-50%);
		font: 14px arial;
	}

</style>

<!-- jQuery -->
<script src="{base_url}assets/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{base_url}assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{base_url}assets/adminlte/dist/js/adminlte.min.js"></script>
<script src="{base_url}assets/adminlte/dist/js/jquery.cookie.js"></script>
<!-- jquery-validation -->
<script src="{base_url}assets/adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="{base_url}assets/adminlte/plugins/jquery-validation/additional-methods.min.js"></script>
 <!-- Select2 -->
<script src="{base_url}assets/adminlte/plugins/select2/js/select2.full.min.js"></script>
<!-- SweetAlert2 -->
<script src="{base_url}assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>

	$(document).ready(function () {          

		$(".preloader").delay(500).fadeOut();
		$(".select2").select2({
			theme: 'bootstrap4'
		});
		
		$('#form_register').validate({
			submitHandler: function () {
				var form = document.getElementById('form_register');
				var formData = new FormData(form);
				$(".preloader").fadeIn();
				$.ajax({
					url: '{base_url}auth/register_submit',
					enctype: 'multipart/form-data',
					data: formData,
					processData: false,
					contentType: false,
					type: 'POST',
					dataType: 'json',
				})
				.done(function(data) {
					$(".preloader").fadeOut('slow',function() {
						if(data.status==true){
						Swal.fire({
							type: 'success',
							title: 'Success',
							text: data.message,
							showConfirmButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'ok'
						}).then(function(){
							window.history.go(-1);
						});
					}else{
						Swal.fire({
							type: 'error',
							title: 'Failed',
							text: data.message,
							showConfirmButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'ok'
						});
					}    
					});
					
				})
			},
			rules: {
				"vendor_code":{
					"required":true
				},
				"fullname":{
					"required":true
				},
				"email":{
					"required":true
				},
				"password":{
					"required":true
				},
				"confirm_password":{
					"required":true,
					"equalTo": "#password"
				},
			},
			messages: {
				email: {
					required: "Please enter a email address",
					email: "Please enter a vaild email address"
				},
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				}
			},
			errorElement: 'span',
			errorPlacement: function (error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group').append(error);
			},
			highlight: function (element, errorClass, validClass) {
				$(element).addClass('is-invalid');
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
			}
		});

		$('.captcha-refresh').on('click', function(){
			$.get('<?php echo base_url().'auth/refresh_captcha'; ?>', function(data){
				$('#image_captcha').html(data);
			});
		});
		
	});
</script>
</head>
<body class="hold-transition login-page">
<div class="preloader">
	<div class="loading">
		<img src="{base_url}assets/adminlte/dist/img/loading.gif" width="88">
		<!--       <div>
		<p>Harap Tunggu...</p>
		</div>-->
	</div>
</div>
<div class="login-box">
	<div class="login-logo">
	<img src="{base_url}assets/adminlte/dist/img/logo.png" height="100px"><br />
		<a href="#"><b>{title}</b></a>
	</div>
	<!-- /.login-logo -->
	<div class="card shadow-lg p-3 mb-5 bg-white rounded" style="opacity: 0.8;">
		<div class="card-body login-card-body">
		<p class="login-box-msg">Sign in to start your session</p>

		<form id="form_register" action="auth/authentication" method="post">
			<input name="{csrf_token_name}" type="hidden" value="{csrf_hash}">
			<div class="form-group">
				<div class="input-group">
					<select class="form-control select2" name="vendor_code">
						<option value="">Vendor Name</option>
						{vendor_list}
					</select>
					<div class="input-group-append">
						<div class="input-group-text">
						<span class="fas fa-briefcase"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group mb-3">
					<input name="fullname" type="text" class="form-control" placeholder="Full name">
					<div class="input-group-append">
						<div class="input-group-text">
						<span class="fas fa-user"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group mb-3">
					<input name="email" type="email" class="form-control" placeholder="Email">
					<div class="input-group-append">
						<div class="input-group-text">
						<span class="fas fa-envelope"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group mb-3">
					<input id="password" name="password" type="password" class="form-control" placeholder="Password">
					<div class="input-group-append">
						<div class="input-group-text">
						<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group mb-3">
					<input name="confirm_password" type="password" class="form-control" placeholder="Retype password">
					<div class="input-group-append">
						<div class="input-group-text">
						<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="input-group mb-3">
				<input autocomplete="off" id="captcha" name="captcha" type="captcha" class="form-control" placeholder="Captcha">
				<div class="input-group-append">
					<div class="input-group-text">
					<span class="fas fa-robot"></span>
					</div>
				</div>
			</div>
			<div class="input-group mb-3">
				<div class="row">
					<div class="col-10" id="image_captcha">
						<?=$captcha?>
					</div>
					<div class="col-2">
						<a href="javascript:void(0);" class="captcha-refresh" ><span class="fas fa-sync-alt"></span></a>
					</div>
				</div>
			</div>
			<div class="row">
			<div class="col-8">
				<p class="mb-0">
					<a href="{base_url}auth" class="text-center">Sign In</a>
				</p> 
			</div>
			<!-- /.col -->
			<div class="col-4">
				<button type="submit" class="btn btn-primary btn-block">Register</button>
			</div>
			<!-- /.col -->
			</div>
		</form>

		</div>
		<!-- /.login-card-body -->
	</div>
  	<div id="alert" class="alert alert-danger alert-dismissible" style="display:none;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h5><i class="icon fas fa-ban"></i> Alert!</h5>
		<span id="error"></span>
	</div>
</div>
<!-- /.login-box -->

</body>
</html>