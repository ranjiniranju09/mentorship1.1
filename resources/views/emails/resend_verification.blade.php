<!DOCTYPE html>
<html>
<head>
    <title>Resend Verification Email</title>
</head>
<body>
    <p>Hello {{ $name ?? '' }},</p>
    <p>Thank you for joining the Mentorship Platform! To verify your email and complete your registration, please click the link below:</p>
    <p><a href="{{ $verification_url }}" style="color: #ffffff; text-decoration: none; background-color: #007BFF; padding: 10px 20px; border-radius: 5px;">Verify Your Email</a></p>
    <p>If you’ve already verified your email, please disregard this message. If you encounter any issues, feel free to contact our support team:</p>
    <ul>
        <li>Email: <a href="mailto:ranjini.forstu@gmail.com">ranjini.forstu@gmail.com</a></li>
        <li>Phone: 9847070183</li>
    </ul>
    <p>We’re excited to have you with us and look forward to your journey as a {{ $role }}.</p>
    
    <p>Warm regards,<br>The Mentorship Platform Team</p>
</body>
</html>
