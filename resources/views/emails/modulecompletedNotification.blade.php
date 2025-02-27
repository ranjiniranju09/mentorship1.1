<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Completion Notification</title>
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
        <p>Dear {{ $mentee->name }},</p>

        <p>Congratulations! Your mentor, <strong>{{ $mentor->name }}</strong>, has marked the module <strong>"{{ $module->name }}"</strong> as completed.</p>

        <p>This is a significant milestone in your journey of learning and growth. Your hard work and dedication are truly commendable, and we encourage you to keep up the momentum as you continue to achieve your goals.</p>

        <p>If you have any questions or need further guidance, don’t hesitate to reach out to your mentor or our support team. We are here to help you succeed every step of the way.</p>

        <p class="email-footer">Best Regards,<br>Team Egghead Forstu</p>
    </div>
</body>
</html>
