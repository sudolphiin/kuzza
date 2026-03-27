<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kuzza Education ERP - Login</title>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/themify-icons.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css" />
    <style>
        :root {
            --base_color: #20b2aa;
            --gradient_1: #38b2ac;
            --gradient_2: #319795;
        }
        .quick-login-section {
            background: linear-gradient(135deg, rgba(56, 178, 172, 0.1) 0%, rgba(32, 178, 170, 0.1) 100%);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(32, 178, 170, 0.2);
        }
        .quick-login-section h6 {
            color: var(--base_color);
            margin-bottom: 25px;
            font-weight: 700;
            font-size: 16px;
        }
        .quick-login-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }
        .quick-login-btn {
            background: linear-gradient(135deg, var(--gradient_1) 0%, var(--base_color) 100%) !important;
            border: 1px solid var(--base_color) !important;
            border-radius: 8px !important;
            color: #fff !important;
            font-weight: 600 !important;
            padding: 12px 16px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(32, 178, 170, 0.3) !important;
            font-size: 13px;
            cursor: pointer;
        }
        .quick-login-btn:hover {
            background: linear-gradient(135deg, var(--base_color) 0%, var(--gradient_2) 100%) !important;
            box-shadow: 0 6px 16px rgba(32, 178, 170, 0.5) !important;
            transform: translateY(-2px) !important;
            text-decoration: none;
        }
        .divider-text {
            text-align: center;
            margin: 20px 0;
            color: #666;
            font-size: 14px;
            position: relative;
        }
        .divider-text::before,
        .divider-text::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }
        .divider-text::before {
            left: 0;
        }
        .divider-text::after {
            right: 0;
        }
        @media (max-width: 768px) {
            .quick-login-buttons {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 10px;
            }
            .quick-login-btn {
                padding: 10px 12px !important;
                font-size: 12px;
            }
        }
    </style>
</head>
<body class="login">

    <!--================ Start Login Area =================-->
	<section class="login-area">
		<div class="container">
			<div class="row login-height justify-content-center align-items-center">
			<div class="col-lg-5 col-md-8">
				<div class="form-wrap text-center">
					<div class="logo-container">
						<a href="#">
							<img src="{{asset('logo.png')}}" alt="Kuzza Logo" style="max-width: 200px; height: auto;">
						</a>
					</div>
					<h5 class="text-uppercase" style="color: var(--base_color); margin: 20px 0;">Kuzza Education ERP</h5>
					
					<!-- Quick Login Section -->
					<div class="quick-login-section">
						<h6>Quick Login - Select Your Role</h6>
						<div class="quick-login-buttons">
							<form method="POST" action="<?php echo e(route('login')); ?>" style="display: inline-block; width: 100%;">
								<?php echo csrf_field(); ?>
								<?php 
								$user = DB::table('users')->select('email')->where('role_id',1)->first();
								$email = $user ? $user->email : 'admin@example.com';
								?>
								<input type="hidden" name="email" value="{{$email}}">
								<input type="hidden" name="password" value="123456">
								<button type="submit" class="quick-login-btn">Super Admin</button>
							</form>
							
							<form method="POST" action="<?php echo e(route('login')); ?>" style="display: inline-block; width: 100%;">
								<?php echo csrf_field(); ?>
								<?php 
								$user = DB::table('users')->select('email')->where('role_id',5)->first();
								$email = $user ? $user->email : 'admin@example.com';
								?>
								<input type="hidden" name="email" value="{{$email}}">
								<input type="hidden" name="password" value="123456">
								<button type="submit" class="quick-login-btn">Admin</button>
							</form>
							
							<form method="POST" action="<?php echo e(route('login')); ?>" style="display: inline-block; width: 100%;">
								<?php echo csrf_field(); ?>
								<?php 
								$user = DB::table('users')->select('email')->where('role_id',4)->first();
								$email = $user ? $user->email : 'teacher@example.com';
								?>
								<input type="hidden" name="email" value="{{$email}}">
								<input type="hidden" name="password" value="123456">
								<button type="submit" class="quick-login-btn">Teacher</button>
							</form>
							
							<form method="POST" action="<?php echo e(route('login')); ?>" style="display: inline-block; width: 100%;">
								<?php echo csrf_field(); ?>
								<?php 
								$user = DB::table('users')->select('email')->where('role_id',6)->first();
								$email = $user ? $user->email : 'accountant@example.com';
								?>
								<input type="hidden" name="email" value="{{$email}}">
								<input type="hidden" name="password" value="123456">
								<button type="submit" class="quick-login-btn">Accountant</button>
							</form>
							
							<form method="POST" action="<?php echo e(route('login')); ?>" style="display: inline-block; width: 100%;">
								<?php echo csrf_field(); ?>
								<?php 
								$user = DB::table('users')->select('email')->where('role_id',7)->first();
								$email = $user ? $user->email : 'receptionist@example.com';
								?>
								<input type="hidden" name="email" value="{{$email}}">
								<input type="hidden" name="password" value="123456">
								<button type="submit" class="quick-login-btn">Receptionist</button>
							</form>
							
							<form method="POST" action="<?php echo e(route('login')); ?>" style="display: inline-block; width: 100%;">
								<?php echo csrf_field(); ?>
								<?php 
								$user = DB::table('users')->select('email')->where('role_id',8)->first();
								$email = $user ? $user->email : 'librarian@example.com';
								?>
								<input type="hidden" name="email" value="{{$email}}">
								<input type="hidden" name="password" value="123456">
								<button type="submit" class="quick-login-btn">Librarian</button>
							</form>
							
							<form method="POST" action="<?php echo e(route('login')); ?>" style="display: inline-block; width: 100%;">
								<?php echo csrf_field(); ?>
								<?php 
								$user = DB::table('users')->select('email')->where('role_id',2)->first();
								$email = $user ? $user->email : 'student@example.com';
								?>
								<input type="hidden" name="email" value="{{$email}}">
								<input type="hidden" name="password" value="123456">
								<button type="submit" class="quick-login-btn">Student</button>
							</form>
							
							<form method="POST" action="<?php echo e(route('login')); ?>" style="display: inline-block; width: 100%;">
								<?php echo csrf_field(); ?>
								<?php 
								$user = DB::table('users')->select('email')->where('role_id',3)->first();
								$email = $user ? $user->email : 'parent@example.com';
								?>
								<input type="hidden" name="email" value="{{$email}}">
								<input type="hidden" name="password" value="123456">
								<button type="submit" class="quick-login-btn">Parent</button>
							</form>
						</div>
						<div class="divider-text">or</div>
					</div>
					
					<h5 class="text-uppercase">Login Details</h5>
						<form action="" id="loginForm">
							<div class="form-group input-group">
								<span class="input-group-addon">
									<i class="lnr lnr-user"></i>
								</span>
								<input class="form-control" type="email" name='username' placeholder="Enter Email address" required="required" />
							</div>
							<div class="form-group input-group">
								<span class="input-group-addon">
									<i class="fa fa-key"></i>
								</span>
								<input class="form-control" type="password" name='password' placeholder="Enter Password" required="required" />
							</div>
							<div class="d-flex justify-content-between">
								<div class="checkbox">
									<input type="checkbox" id="rememberMe">
									<label for="rememberMe">Remember Me</label>
								</div>
								<div>
									<a href="#">Forget Password?</a>
								</div>
							</div>
							<div class="form-group">
								<button type="submit" class="primary-btn fix-gr-bg">
									<span class="ti-lock"></span>
									Login
                                </button>
							</div>
							<div class="form-group text-center">
								Don’t have an account? <a href="#">Create Here</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================ Start End Login Area =================-->

	<!--================ Footer Area =================-->
	<footer class="footer_area">
		<div class="container">
			<div class="row justify-content-center">
			<div class="col-lg-5">
				<p>Copyright © <?php echo date('Y'); ?> All rights reserved | <strong>Kuzza Education ERP</strong></p>
			</div>
			</div>
		</div>
	</footer>
	<!--================ End Footer Area =================-->


    <script src="{{asset('public/backEnd/')}}/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="{{asset('public/backEnd/')}}/vendors/js/popper.js"></script>
	<script src="{{asset('public/backEnd/')}}/vendors/js/bootstrap.min.js"></script>
	<script>
		$('.primary-btn').on('click', function(e) {
		// Remove any old one
		$('.ripple').remove();

		// Setup
		var primaryBtnPosX = $(this).offset().left,
			primaryBtnPosY = $(this).offset().top,
			primaryBtnWidth = $(this).width(),
			primaryBtnHeight = $(this).height();

		// Add the element
		$(this).prepend("<span class='ripple'></span>");

		// Make it round!
		if (primaryBtnWidth >= primaryBtnHeight) {
			primaryBtnHeight = primaryBtnWidth;
		} else {
			primaryBtnWidth = primaryBtnHeight;
		}

		// Get the center of the element
		var x = e.pageX - primaryBtnPosX - primaryBtnWidth / 2;
		var y = e.pageY - primaryBtnPosY - primaryBtnHeight / 2;

		// Add the ripples CSS and start the animation
		$('.ripple')
			.css({
				width: primaryBtnWidth,
				height: primaryBtnHeight,
				top: y + 'px',
				left: x + 'px'
			})
			.addClass('rippleEffect');
		});
	</script>
</body>
</html>
