<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Session Created</title>
</head>
<body>
    <h2>New Session Created</h2>
    <p>Hello {{ $session->mentorname }},</p>
    <p>A new session has been created:</p>
    <ul>
        <li><strong>Module:</strong> {{ $session->modulename }}</li>
        <li><strong>Date & Time:</strong> {{ $session->sessiondatetime }}</li>
        <li><strong>Session Link:</strong> <a href="{{ $session->sessionlink }}" target="_blank">{{ $session->sessionlink }}</a></li>
        <li><strong>Session Title:</strong> {{ $session->session_title }}</li>
        <li><strong>Session Duration:</strong> {{ $session->session_duration_minutes }} minutes</li>
    </ul>
    <p>Thank you!</p>
</body>
</html>
