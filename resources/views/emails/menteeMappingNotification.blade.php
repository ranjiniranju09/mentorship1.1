<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You Have Been Assigned a Mentor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        p {
            margin: 0 0 15px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .email-footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <p>Hello {{ $mapping->mentee->name }},</p>
        <p>We are delighted to inform you that you have been paired with a mentor as part of our Mentorship Program. Your assigned mentor is:</p>
        <p><strong>{{ $mapping->mentor->name }}</strong></p>
        <p>This partnership is an incredible opportunity to grow, learn, and achieve your goals. Feel free to communicate openly with your mentor and make the most out of this experience.</p>
        <p>If you have any questions or need assistance, don't hesitate to reach out to our support team:</p>
        <ul>
            <li>Email: <a href="mailto:ranjini.forstu@gmail.com">ranjini.forstu@gmail.com</a></li>
            <li>Phone: 9847070183</li>
            
            </ul>
        <p>Wishing you a successful and rewarding mentorship journey!</p>
        <p class="email-footer">Warm regards,<br>The Mentorship Platform Team</p>
    </div>
</body>
</html>
