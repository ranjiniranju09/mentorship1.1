<!DOCTYPE html>
<html>
<head>
    <title>New Session Notification</title>
</head>
<body>
    <h1>New Session Added</h1>
    <p>Dear Mentee,</p>
    <p>A new session has been added by your mentor.</p>
    <p><strong>Session Name:</strong> {{ $sessionName }}</p>
    <p><strong>Date:</strong> {{ $sessionDate }}</p>
    <p><strong>Start Time:</strong> {{ $startTime }}</p>
    <p><strong>End Time:</strong> {{ $endTime }}</p>
    <p><strong>Session Link:</strong> <a href="{{ $sessionLink }}">{{ $sessionLink }}</a></p>
    <p>Best regards,</p>
    <p>Team Egghead Forstu</p>
</body>
</html>
