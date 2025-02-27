<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Quiz Submission Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h1 {
            color: #4CAF50;
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
        <h1>Quiz Submission Confirmation</h1>

        <p>Dear {{ $mentee->mentee_name }},</p>

        <p>Thank you for submitting your quiz. Here are your results:</p>
        
        <p><strong>Score:</strong> {{ $score }}</p>

        <p>We're proud of your progress! Keep up the great work, and continue striving for excellence.</p>

        <p class="email-footer">Best Regards,<br>Team Egghead Forstu</p>
    </div>
</body>
</html>
