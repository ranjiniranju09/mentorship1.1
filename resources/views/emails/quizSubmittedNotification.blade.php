<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Submission Notification</title>
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
        <p>Dear {{ $mentor->name }},</p>
        
        <p>We’re excited to inform you that your mentee, <strong>{{ $mentee->mentee_name }}</strong>, has successfully submitted a quiz for their module.</p>

        @if(isset($quizResult->score) && isset($quizResult->total_points))
            <p><strong>Quiz Results:</strong></p>
            <p>Score: <strong>{{ $quizResult->score }}/{{ $quizResult->total_points }}</strong></p>
        @else
            <p><strong>Quiz Results:</strong> Score information is currently unavailable.</p>
        @endif

        <p>Your guidance plays a vital role in your mentee’s progress. Keep inspiring and supporting them as they achieve new milestones!</p>

        <p class="email-footer">Best Regards,<br>Team Egghead Forstu</p>
    </div>
</body>
</html>
