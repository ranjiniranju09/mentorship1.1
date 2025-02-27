<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #007bff;
            color: #fff;
            padding: 15px 0;
            border-radius: 10px 10px 0 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #888;
            text-align: center;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Password Reset Confirmation</h1>
        </div>
        <p>Dear {{ $user->name }},</p>

        <p>We wanted to let you know that your password was successfully reset. You can now log in to your account using your new password. For your security, please ensure that your password remains confidential and unique to your account.</p>

        <h3>Next Steps</h3>
        <ul>
            <li>Log in to your account using your new password: <a href="{{ route('login') }}">Login Page</a>.</li>
            <li>If you didn’t request this password reset, <strong>please contact us immediately</strong> to secure your account.</li>
        </ul>

        <p>If you have any questions or need further assistance, feel free to contact our support team at <a href="mailto:ranjini.forstu@gmail.com">ranjini.forstu@gmail.com</a>.</p>

        <p>Thank you for trusting us.</p>

        <p>Best regards,</p>
        <p>Team Egghead Forstu</p>

        {{--<div class="footer">
            <p>© {{ date('Y') }} Your Company Name. All rights reserved.</p>
            <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
        </div>--}}
    </div>
</body>
</html>
