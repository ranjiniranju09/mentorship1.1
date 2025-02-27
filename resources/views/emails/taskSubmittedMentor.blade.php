<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Submitted by Mentee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        h1 {
            color: #4CAF50;
            text-align: center;
        }
        p {
            margin: 15px 0;
        }
        .task-details {
            margin-top: 20px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .task-details strong {
            color: #4CAF50;
        }
        .footer {
            font-size: 0.9em;
            color: #555;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Task Submitted by Mentee</h1>

        <p>Hello {{ $mentee->mentor_name }},</p>
        <p>Your mentee, {{ $mentee->name }}, has successfully submitted a task.</p>

        <div class="task-details">
            <p><strong>Task Response:</strong> {{ $taskResponse }}</p>
            @if($submittedFileUrl)
                <p><strong>Submitted File:</strong> <a href="{{ $submittedFileUrl }}" target="_blank">Download File</a></p>
            @endif
        </div>

        <p>Thank you!</p>

        <p class="footer">Best regards,<br>Team Egghead Forstu</p>
    </div>
</body>
</html>
