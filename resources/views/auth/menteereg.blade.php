<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentee Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>    <style>
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
        .error {
        color: red;
        font-size: 14px;
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

    <!-- Registration Form Container -->
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="{{ asset('images/logo tu.png') }}" class="brandlogotu" alt="Brand Logo">
            <h2>Mentorship Management Portal</h2>
        </div>
        <h2>Registration Form </h2>
        <form id="registrationForm" action="{{ route('menteereg') }}" method="POST">
            @csrf

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
                <span class="error" id="nameError"></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <span class="error" id="emailError"></span>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="tel" id="mobile" name="mobile" required>
                <span class="error" id="mobileError"></span>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
                <span class="error" id="dobError"></span>
            </div>

            <div class="form-group">
                <label for="skills">Skills</label>
                <select class="form-control" id="skills" name="skills[]" multiple required>
                    
                    <option value="Communication">Communication</option>
                    <option value="Problem solving">Problem solving</option>
                    <option value="Decision making">Decision making</option>
                    <option value="Leadership">Leadership</option>
                    <option value="none">None</option>
                </select>
                <span class="error" id="skillsError"></span>
            </div>

            <div class="form-group">
                <label for="interestedskills">Interested Skills</label>
                <input type="text" id="interestedskills" name="interestedskills" required>
                <span class="error" id="interestedskillsError"></span>
            </div>

            <div class="form-group">
                <label for="pwd">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="error" id="passwordError"></span>
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
        $("#registrationForm").submit(function(e) {
            let isValid = true;
            
            // Full Name Validation
            let name = $("#name").val().trim();
            let namePattern = /^[A-Za-z\s]{3,25}$/;
            if (!namePattern.test(name)) {
                $("#nameError").text("Full Name must be 3-25 letters.");
                isValid = false;
            } else {
                $("#nameError").text("");
            }

            // Email Validation
            let email = $("#email").val().trim();
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                $("#emailError").text("Enter a valid email.");
                isValid = false;
            } else {
                $("#emailError").text("");
            }

            // Mobile Number Validation
            let mobile = $("#mobile").val().trim();
            let mobilePattern = /^[6789][0-9]{9}$/;
            if (!mobilePattern.test(mobile)) {
                $("#mobileError").text("Enter a valid 10-digit mobile number.");
                isValid = false;
            } else {
                $("#mobileError").text("");
            }

            // Date of Birth Validation
            let dob = $("#dob").val();
            let today = new Date().toISOString().split("T")[0];
            if (dob >= today) {
                $("#dobError").text("DOB cannot be in the future.");
                isValid = false;
            } else {
                $("#dobError").text("");
            }

            // Skills Validation
            if ($("#skills").val().length == 0) {
                $("#skillsError").text("Select at least one skill.");
                isValid = false;
            } else {
                $("#skillsError").text("");
            }

            // Interested Skills Validation
            let interestedskills = $("#interestedskills").val().trim();
            if (interestedskills === "") {
                $("#interestedskillsError").text("This field is required.");
                isValid = false;
            } else {
                $("#interestedskillsError").text("");
            }

            // Password Validation
            let password = $("#password").val().trim();
            let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordPattern.test(password)) {
                $("#passwordError").text("Password must be 8+ characters, include 1 uppercase, 1 number, and 1 special character.");
                isValid = false;
            } else {
                $("#passwordError").text("");
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>

</body>
</html>
