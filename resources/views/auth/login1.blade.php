<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransUnion Mentorship Platform</title>
    <!-- TU icon -->
    {{--<link rel="icon" type="image/png" href="{{ asset('images/TU_icon.png') }}">--}}
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for social icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height:100vh;
        margin: 10px;
        font-family: 'Arial', sans-serif;
        background-color: white;
        /* overflow:auto; Prevent scrolling */
    }
        .navbar {
            background-color: black;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 10px 20px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            width: 120px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 900px;
            padding: 20px;
            box-sizing: border-box;

        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* background-color: rgba(0, 0, 255, 0.1); */
            background-color: rgba(255, 255, 255, 0.5);
            padding: 15px;
            border-radius: 15px;
            /* box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); */
        }
        .name {
            color: #003366; /* Dark blue color */
            font-weight: bold;
            font-size: 1.75rem; /* Slightly larger font size */
            font-family: 'Oswald', sans-serif;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3); /* Subtle text shadow for better contrast */
            border-bottom: 3px solid #0066cc; /* Underline effect */
            padding-bottom: 10px; /* Adds space between text and underline */
            margin-bottom: 20px; /* Adds space below the element */
            background: linear-gradient(90deg, #003366, #0066cc); /* Gradient background */
            -webkit-background-clip: text; /*Clips the gradient to the text */
            color: transparent; /* Hides text color to show gradient */
        }
        

        .logo-section img {
            margin-top: 5%;
            width: 40%;
            opacity: 0.9;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.2));
            /* height:fit-content; Ensures the aspect ratio remains consistent */

        }

        .login-container {
            /* background-color: rgba(130, 113, 227, 0.9); */
            background-color: rgba(0, 0, 255, 0.1);
            color: black;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            width: 80%;
            max-width: 400px;
            text-align: center;
            /* animation: slideIn 0.5s forwards, pulse 1.5s infinite; */
        }
        

        .login-container h2 {
            color: black;
            font-weight: bold;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }
    
        .form-control {
            border: 1px solid #e3e3e3;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #6c63ff;
        }

        .btn-primary {
            background-color: #6c63ff;
            border-color: #6c63ff;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #00bcd4;
            border-color: #00bcd4;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
        }

        .footer img {
            width: 80px;
            height: auto;
            margin: 0 10px;
            opacity: 0.85;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.2));
        }
        .dropdown-menu {
            color: white;
            box-sizing: border-box;
            border-radius: 15%;
        }
        .dropdown-item:hover {
            background-color: black;
            color: white;
            border-radius: 5%;
        }

        footer {
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: right;
            color: white;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            height: 5%;
        }

        @keyframes slideIn {
            0% {
                transform: translateY(-100px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            }
            50% {
                box-shadow: 0 15px 45px rgba(0, 0, 0, 0.3);
            }
            100% {
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            }
        }


        @media (max-width: 1024px) {
            .container {
                padding: 15px;
            }
            .login-container {
                width: 90%;
            }
            .logo-section img {
                width: 50%; /* Adjust logo size for medium screens */
            }
            .name {
                font-size: 1.5rem; /* Slightly smaller font size */
            }
        }

        @media (max-width: 768px) {
            .navbar-brand img {
                width: 80px;
            }
            .logo-section img {
                width: 60%;
            }
            .name {
                font-size: 1.3rem;
            }
            .login-container h2 {
                font-size: 1.2rem;
            }
            .btn-primary,
            .btn-dark {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand img {
                width: 60px;
            }
            .logo-section img {
                margin-top: 8%;
                width: 70%;
            }
            .name {
                font-size: 1.1rem;
            }
            .login-container h2 {
                font-size: 2rem;
            }
            .btn-primary,
            .btn-dark {
                font-size: 0.8rem;
                padding: 8px;
            }
            .form-control {
                font-size: 0.9rem;
                padding: 8px;
            }
        }

        @media (max-width: 400px) {
            .navbar {
                padding: 8px 15px;
            }
            .login-container {
                padding: 1.5rem;
            }
            .footer img {
                width: 60px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            {{--<img src="{{ asset('images/logo tu.png') }}" class="brandlogotu" alt="Brand Logo">--}}
            <img src="https://forstubucket1.s3.ap-south-1.amazonaws.com/logos/TUC+Blue+Transparent.png" class="brandlogotu" alt="Brand Logo">
            <div class="name">
                <h3>Mentorship Management Portal</h3>
            </div>
        </div>

            <!-- Login Section -->
        <div class="login-container">
            @if($errors->has('email'))
                <div class="alert alert-danger">
                    {{$errors->first('email')}}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @endif

            <h2 class="text-center mb-4">Login</h2>
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                    @if($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
                <div class="input-group mb-4">
                    <div class="form-check checkbox">
                        <input class="form-check-input" name="remember" type="checkbox" id="remember" style="vertical-align: middle;" />
                        <label class="form-check-label" for="remember" style="vertical-align: middle;">
                            <span class="text-muted">Remember Me</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark w-100">Login</button>
            </form>
            <br>
            <div>
                <a href="{{route('Emailverify')}}" style="color: black;">Forgot Password</a>
            </div>
            <br>

            <a href="{{ route('sendVerificationForm') }}" style="color: black;" target="_blank">Resend Verification</a>

            @if ($errors->any())
                <div class="text-danger mt-3 text-center">
                    {{ $errors->first() }}
                </div>
            @endif
            <hr>
            <div>
                <p>New Member ?</p>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Register Here
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li><a class="dropdown-item" href="{{ route('menteeshow') }}" target="_blank">Register for Mentee</a></li>
                        <li><a class="dropdown-item" href="{{ route('mentorshow') }}" target="_blank">Register for Mentor</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class=" footer">
            <p>Powered By</p>
            <span><img src="{{ asset('images/egghead logo.png') }}" id="logo" alt="Egghead Logo"></span>
            <span><img src="{{ asset('images/logo forstu.png') }}" id="logo" alt="Logo TU"></span>
        </div>  
    </div>
        

    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
