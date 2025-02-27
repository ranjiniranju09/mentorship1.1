<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Assigned</title>
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
        h2 {
            color: #4CAF50;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Task Assigned</h2>

        <p>Hello {{ $menteeName }},</p>

        <p>You have been assigned a new task by your mentor, <strong>{{ $mentorName }}</strong>.</p>

        <div class="task-details">
            <p><strong>Task Title:</strong> {{ $taskTitle }}</p>
            <p><strong>Task Description:</strong> {{ $taskDescription }}</p>
        </div>

        <p>Thank you for your commitment to your mentorship journey!</p>

        <p class="footer">Best Regards,<br>Team Egghead Forstu</p>
    </div>
</body>
</html>
