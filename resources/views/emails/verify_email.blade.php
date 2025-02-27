<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Hello,</p>
        <p>Please verify your email address by clicking the button below:</p>
        <p>
        <a href="{{ $url }}" style="text-decoration: none;">
            <button style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 5px;">
                Verify Email
            </button>
        </a>

        </p>
        <p><strong>Note:</strong> This link is valid for only 10 minutes. If the link expires, you will need to request a new verification email.</p>
        <p>If you did not request this, please ignore this email.</p>
        
        <p>If you have any questions or need further assistance, feel free to contact our support team at <a href="mailto:ranjini.forstu@gmail.com">ranjini.forstu@gmail.com</a>.</p>

        <p>Thank you for trusting us.</p>

        <p>Best regards,</p>
        <p>Team Egghead Forstu</p>
    </div>
</body>
</html>
