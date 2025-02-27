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
        <h2>New Session Created</h2>
        
        <p>Hello,</p>
        <p>A new session has been created with the following details:</p>
        
        <ul>
            <li><strong>Title:</strong> {{ $session->session_title }}</li>
            <li><strong>Date and Time:</strong> {{ $session->sessiondatetime }}</li>
            <!-- Add more session details here if needed -->
        </ul>

        <p>Thank you for your participation!</p>
        
        <p class="email-footer">Best Regards,<br>Team Egghead Forstu</p>
    </div>
</body>
</html>
