<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            max-width: 550px;
            margin: auto;
            padding: 30px;
            border: 1px solid #dcdde1;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-section img {
            width: 70%;
            opacity: 0.9;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.2));
        }

        h2 {
            font-size: 22px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 500;
            color: #34495e;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"], 
        input[type="tel"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ced6e0;
            background-color: #f7f9fc;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer img {
            width: 80px;
            margin: 0 10px;
            opacity: 0.85;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.2));
        }
        

        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            position: fixed;
            bottom: 0;
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
                margin: 20px;
            }

            .logo-section img {
                width: 80%;
            }

            footer {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <!-- Mentor Registration Form Container -->
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="{{ asset('images/logo tu.png') }}" class="brandlogotu" alt="Brand Logo">
            <h2>Mentorship Management Portal</h2>
        </div>
        <h2>Registration Form </h2>
        <form id="mentorRegistrationForm" action="{{ route('mentorreg') }}" method="POST">
            @csrf

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required pattern="[A-Z a-z.]{3,25}">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="tel" id="mobile" name="mobile" pattern="[6789][0-9]{9}" required unique>
            </div>
            <div class="form-group">
                <label for="companyname">Company Name</label>
                <input type="text" id="companyname" name="companyname" required>
            </div>

            <div class="form-group">
                <label for="skills">Skills</label>
                <select class="form-control" id="skills" name="skills[]" multiple required>
                    <!-- <option value="select" disabled>---------Select Skills---------</option> -->
                    <option value="Communication">Communication</option>
                    <option value="Problem solving">Problem solving</option>
                    <option value="Decision making">Decision making</option>

                </select>
            </div>

            
            <div class="form-group">
                <label for="pwd">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>

        <div class="footer">
            <p>Powered By</p>
            <span><img src="{{ asset('images/egghead logo.png') }}" id="logo" alt="Egghead Logo"></span>
            <span><img src="{{ asset('images/logo forstu.png') }}" id="logo" alt="Logo TU"></span>
        </div>
    </div>

    <script>
         $(document).ready(function() {
        $('#skills').select2({
            placeholder: "Select Skills",
            allowClear: true
        });
    });
    </script>

    <script>
        $(document).ready(function () {
            $('#skills').select2({
                placeholder: "Select Skills",
                allowClear: true
            });

            $("#mentorRegistrationForm").submit(function (e) {
                let isValid = true;

                // Clear previous errors
                $(".error-message").remove();

                // Validate Name
                let name = $("#name").val().trim();
                if (name.length < 3) {
                    showError("#name", "Name must be at least 3 characters.");
                    isValid = false;
                }

                // Validate Email
                let email = $("#email").val().trim();
                let emailPattern = /^(?!.*\.\.)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                if (!emailPattern.test(email)) {
                    showError("#email", "Enter a valid email address (e.g., user@example.com).");
                    isValid = false;
                } else if (email.length > 320) {
                    showError("#email", "Email is too long (max 320 characters).");
                    isValid = false;
                } else if (!/^.{1,64}@.{1,255}$/.test(email)) {
                    showError("#email", "Email local part (before @) must be max 64 characters.");
                    isValid = false;
                }


                // Validate Mobile Number
                let mobile = $("#mobile").val().trim();
                let mobilePattern = /^[6789]\d{9}$/;
                if (!mobilePattern.test(mobile)) {
                    showError("#mobile", "Enter a valid 10-digit mobile number.");
                    isValid = false;
                }

                // Validate Password
                let password = $("#password").val().trim();
                let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

                if (password.length < 8) {
                    showError("#password", "Password must be at least 8 characters.");
                    isValid = false;
                } else if (!/[A-Z]/.test(password)) {
                    showError("#password", "Password must contain at least one uppercase letter.");
                    isValid = false;
                } else if (!/[a-z]/.test(password)) {
                    showError("#password", "Password must contain at least one lowercase letter.");
                    isValid = false;
                } else if (!/\d/.test(password)) {
                    showError("#password", "Password must contain at least one number.");
                    isValid = false;
                } else if (!/[@$!%*?&]/.test(password)) {
                    showError("#password", "Password must contain at least one special character (@, $, !, %, *, ?, &).");
                    isValid = false;
                } else if (!passwordRegex.test(password)) {
                    showError("#password", "Password is not strong enough.");
                    isValid = false;
                }


                if (!isValid) {
                    e.preventDefault(); // Prevent form submission if validation fails
                }
            });

            function showError(inputId, message) {
                $(inputId).after(`<div class="error-message" style="color: red; font-size: 14px;">${message}</div>`);
                $(inputId).css("border", "1px solid red");
            }
        });
    </script>

</body>
</html>
