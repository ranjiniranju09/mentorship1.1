<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Session Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h2 {
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
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin: 8px 0;
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
        <h2>New Session Organised by Mentor</h2>
        
        <p>Hello, {{ $session->menteename }}</p>
        <p>A new session has been Scheduled with the following details:</p>
        
        <ul>
            <li><strong>Module:</strong> {{ $session->modulename }}</li>
            <li><strong>Date & Time:</strong> {{ $session->sessiondatetime }}</li>
            <li><strong>Session Link:</strong> <a href="{{ $session->sessionlink }}" target="_blank">{{ $session->sessionlink }}</a></li>
            <li><strong>Session Title:</strong> {{ $session->session_title }}</li>
            <li><strong>Session Duration:</strong> {{ $session->session_duration_minutes }} minutes</li>
        </ul>

        <p>Thank you for your participation!</p>
        
        <p class="email-footer">Best Regards,<br>Team Egghead Forstu</p>
    </div>
</body>
</html>
